<?php

namespace Webamooz\Course\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webamooz\Category\Models\Category;
use Webamooz\Comment\Models\Comment;
use Webamooz\Comment\Traits\HasComments;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Discount\Models\Discount;
use Webamooz\Discount\Repositories\DiscountRepo;
use Webamooz\Discount\Services\DiscountService;
use Webamooz\Media\Models\Media;
use Webamooz\Payment\Models\Payment;
use Webamooz\Ticket\Models\Ticket;
use Webamooz\User\Models\User;

class Course extends Model
{
    use HasFactory, HasComments;

    const TYPE_FREE = 'free';
    const TYPE_CASH = 'cash';
    public static array $types = [self::TYPE_FREE, self::TYPE_CASH];

    const STATUS_COMPLETED = 'completed';
    const STATUS_NOT_COMPLETED = 'not_completed';
    const STATUS_LOCKED = 'locked';
    public static array $statuses = [self::STATUS_COMPLETED, self::STATUS_NOT_COMPLETED, self::STATUS_LOCKED];

    const CONFIRMATION_STATUS_ACCEPTED = 'accepted';
    const CONFIRMATION_STATUS_REJECTED = 'rejected';
    const CONFIRMATION_STATUS_PENDING = 'pending';
    public static array $confirmationStatuses = [self::CONFIRMATION_STATUS_ACCEPTED, self::CONFIRMATION_STATUS_REJECTED, self::CONFIRMATION_STATUS_PENDING];

    protected $fillable = [
        'teacher_id',
        'category_id',
        'banner_id',
        'title',
        'slug',
        'priority',
        'price',
        'percent',
        'type',
        'status',
        'confirmation_status',
        'body',
        'image',
    ];


    //---------- Relationship
    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students()  //relation(student,course) save student buy course
    {
        //relation + table(name) + column(اسم ستون مربوط به این مادل) + column(اسم ستون ریلیشن یا طرف مقابل)
        return $this->belongsToMany(User::class, 'course_student', 'course_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'banner_id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'paymentable');  //1)Model  2)name relation (paymentable) اسم ستون درون پیمنت
    }

    public function discounts()
    {
        return $this->morphToMany(Discount::class, 'discountable'); //1)Model  2)name relation(discountable) Model + able
    }

    public function tickets()  //Relationship One To Many + morphMany===hasMany
    {
        return $this->morphMany(Ticket::class, 'ticketable');  //1)Model  2)name relation (paymentable) اسم ستون درون پیمنت
    }


    //---------- Method
    public function payment()
    {
        return $this->payments()->latest()->first();  //آخرین دوره ای که خریده نمایش میده در اطلاعات کامل کاربر
    }

    public function getDuration()
    {
        return resolve(CourseRepository::class)->getDurationTimeLesson($this->id);
    }

    public function formattedDurationTimeLesson()
    {
        $duration = $this->getDuration();
        $h = round($duration / 60) < 10 ? '0' . round($duration / 60) : round($duration / 60);  //زیر یک ساعت بود بیا ی صفر بزار قبلش
        $m = ($duration % 60) < 10 ? '0' . ($duration % 60) : ($duration % 60);  //دقیقه اگر زیر ده بود بیا ی صفر بزار وگرنه عدد دورقمی میشه و خودش بنویس
        return $h . ':' . $m . ":00";
    }

    public function getFormattedPrice()  //number_format
    {
        return number_format($this->price);
    }

    public function getDiscount()  //discount price course -> چند درصد تخفیف داره
    {
        $discountRepo = new DiscountRepo();
        $discount = $discountRepo->getCourseBiggerDiscount($this->id);  //courseDiscount -> تخفیف های دارای کد تخفیف برای دوره خاص یا همه دوره ها
        $globalDiscount = $discountRepo->getGlobalBiggerDiscount();

        if ($discount == null && $globalDiscount == null) return null;  //اگر کد تخفیفی یا تخفیفی سراسری هر دو وجود نداشت نال برگردون
        if ($discount == null && $globalDiscount != null) return $globalDiscount;  //اگر کد تخفیفی نداشت و تخفیفی سراسری برگردون
        if ($discount != null && $globalDiscount == null) return $discount;  //اگر تخفیف سراسری نداشت بیا کد تخفیف برگردون

        if ($globalDiscount->percent > $discount->percent) return $globalDiscount;  //اگر هر دو داشت مقایسه کن اگر تخفیفی سراسری درصدش بیشتر بود برگردون
        return $discount;  //اگر هر دو داشت مقایسه کن اگر کد تخفیف درصدش بیشتر بود برگردون
    }

    public function getDiscountPercent()
    {
        $discount = $this->getDiscount();
        if ($discount) return $discount->percent;
        return 0;
    }

    public function getDiscountAmount($percent = null)  //discount amount -> چه قدر تخفیف داره تا الان
    {
        if (is_null($percent)) {  //discount Null -> $percent = 0;
            $discount = $this->getDiscount();
            $percent = $discount ? $discount->percent : 0;
        }
        return DiscountService::calculateDiscountAmount($this->price, $percent);
//      return $this->price * ((float)('0.' . $this->getDiscount()));  //1,000,000 * 0.18 = 180,000
    }

    public function getFormattedDiscountAmount()
    {
        return number_format($this->getDiscountAmount());
    }

    public function getFinalPrice($code = null, $withDiscounts = false)  //یا تخفیفی سراسری ثبت میشه یا کد تخفیف هر دو با هم نمیشه
    {
        $discounts = [];
        $discount = $this->getDiscount();

        $amount = $this->price;  //اول قسمت اصلی دوره مگر در خط پایین تخفیفی وجود داشته باشد
        if ($discount) {
            $discounts[] = $discount;  //تخفیف سراسری اگر وجود داشت میریزه درون یک آرایه
            $amount = $this->price - $this->getDiscountAmount($discount->percent);
        }

        if ($code) {  //اگر کد تخفیفی کاربر وارد کرد
            $discountRepo = new DiscountRepo();
            $discountFromCode = $discountRepo->getValidDiscountByCode($code, $this->id);
            if ($discountFromCode) {  //اگر کد تخفیف صحیح بود
                $discounts[] = $discountFromCode;  //کد تخفیف اگر وجود داشت و صحیح بود میریزه درون یک آرایه
                $amount = $amount - DiscountService::calculateDiscountAmount($amount, $discountFromCode->percent);  //ممکن تخفیف سراسری باش و کد تخفیفی قبلی رو این اجرا بشه
            }
        }
        if ($withDiscounts) {  //اگر همراه تخفیف بود بیا هر دو بگیر هم قیمت و لیست تخفیفات
            return [$amount, $discounts];
        }
        return $amount;
    }

    public function getFormattedFinalPrice()
    {
        return number_format($this->price - $this->getDiscountAmount());
    }

    public function path()
    {
        return route('courses.singleCourse', $this->id . '-' . $this->slug);  //localhost:8000/c-1-course-laravel-11
    }

    public function shortUrl()
    {
        return route('courses.singleCourse', $this->id);
    }

    public function lessonCount()
    {
        return (new CourseRepository())->getLessonCount($this->id);
    }

    public function hasStudent($student_id)
    {
        return resolve(CourseRepository::class)->hasStudent($this, $student_id);
    }

    public function downloadAllLinks(): array
    {
        $links = [];
        foreach (resolve(CourseRepository::class)->getLessons($this->id) as $lesson) {
            $links[] = $lesson->downloadLink();
        }
        return $links;
    }

    //---------- use Trait HasComments
    //method_exists($value,"comments");  //One)درون آبجکت یا مادل دوره برو بگرد  Two)درون مادل بگرد ببین همچین متودی داره
    /*public function comments()  //Relationship One To Many + morphMany===hasMany
    {
        return $this->morphMany(Comment::class, 'commentable');  //1)Model  2)name relation (paymentable) اسم ستون درون پیمنت
    }

    public function approvedComments()
    {
        return $this->morphMany(Comment::class, 'commentable')
            ->where('status', Comment::STATUS_APPROVED)
            ->whereNull('comment_id')
            ->with('comments');  //جواب ها و پاسخ های این کانت بردار بیار
    }*/


}
