<?php

namespace Webamooz\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserStatus;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Webamooz\Comment\Models\Comment;
use Webamooz\Course\Models\Course;
use Webamooz\Course\Models\Lesson;
use Webamooz\Course\Models\Season;
use Webamooz\Media\Models\Media;
use Webamooz\Payment\Models\Payment;
use Webamooz\Payment\Models\Settlement;
use Webamooz\RolePermissions\Model\Role;
use Webamooz\Ticket\Models\Reply;
use Webamooz\Ticket\Models\Ticket;
use Webamooz\User\Notifications\ResetPasswordRequestNotification;
use Webamooz\User\Notifications\VerifyMailNotification;

//class User extends Authenticatable implements MustVerifyEmail
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;

    //user has role(Permission Spatie) + all supports relations(user+permissions)

    const STATUS_ACTIVE = 'active';
    const STATUS_DEACTIVE = 'deactive';
    const STATUS_BAN = 'ban';
    public static array $statuses = [self::STATUS_ACTIVE, self::STATUS_DEACTIVE, self::STATUS_BAN];

    public static array $defaultUsers = [
        [
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin',
            'username' => 'username',
            'role' => Role::ROLE_SUPER_ADMIN,
        ],
        [
            'name' => 'NormalUser',
            'email' => 'normaluser@gmail.com',
            'password' => 'normaluser',
            'role' => Role::ROLE_STUDENT,

        ],
        [
            'name' => 'Teacher',
            'email' => 'teacher@gmail.com',
            'password' => 'teacher',
            'role' => Role::ROLE_TEACHER,
        ]
    ];

    protected $fillable = [
        'name',
        'email',
        'username',
        'mobile',
        'head_line',
        'bio',
        'ip',
        'telegram',
        'image_id',
        'password',
        'email_verified_at',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /*---------- Relationship ----------*/
    public function image()
    {
        return $this->belongsTo(Media::class, 'image_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'teacher_id');  //هر کاربر با نقش مدرس تعداد زیادی دوره دارد
    }

    public function seasons()
    {
        return $this->hasMany(Season::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function purchases()  //relation(student,course) save student buy course
    {
        //relation + table(name) + column(اسم ستون مربوط به این مادل) + column(اسم ستون ریلیشن یا طرف مقابل)
        return $this->belongsToMany(Course::class, 'course_student', 'user_id', 'course_id');
    }

    public function payments() //Model + able
    {
        return $this->hasMany(Payment::class, 'buyer_id');  //1)Model  2)name relation (buyer_id) اسم متود درون پیمنت
    }

    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketReplies()
    {
        return $this->hasMany(Reply::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


    /*---------- Methods ----------*/

    public function profilePath()  //اگر کابر نام کابری داشت تو مسیر مشهده پروفایل قرار بده اگر نداشت دستی بنویس یوزرنیم
    {
        //$this -> auth()->user()
        return $this->username ? route('users.view.profile', $this->username) : route('users.view.profile', 'username');
    }

    public function getThumbAttribute(): string  //کاربر اگر تصویری دااشت نمایش بده وگرنه بیا این تصویر پیش فرض قرار بده
    {
        if ($this->image) {
            return '/storage/' . $this->image->files['300'];  //get image size 300(thumb بند انگشتی)
        }
        return '/panel/img/profile.jpg';
    }

    /*public function hasAccessToCourse(Course $course)  //Move in LessonPolicies
    {
        //یا مدیر سایت یا کسی که مدرس این دوره یا دانشجوی ثبت نامی بتونه دسترسی به دوره داشته باشد
        //contains -> اگر کاربر وجود داشت آیدیش بگیر
        if ($this->can('manage') || $this->id == $course->teacher_id || $this->id == $course->students->contains($this->id)) {
            return true;
        }
        return false;
    }*/

    public function studentsCount()
    {
        return DB::table('courses')->select('id')->where('teacher_id', $this->id)  //$this->id -> همون آیدی مدرس درون صفحه مشخصات مدرس مورد نظر
        ->join("course_student", "courses.id", "=", "course_student.course_id")->count();
    }

    public function routeNotificationForSms()  //ارسال نوتیفیکیشن
    {
        return $this->mobile;
    }


    /* Customize Send Email With Verify By Code 6 Character */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyMailNotification());  //بیا نوتیفیکیشن ارسال کن
    }

    public function sendResetPasswordRequestNotification()
    {
        $this->notify(new ResetPasswordRequestNotification());
    }

}
