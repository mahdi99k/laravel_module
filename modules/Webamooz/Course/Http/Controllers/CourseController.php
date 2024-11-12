<?php

namespace Webamooz\Course\Http\Controllers;

use MongoDB\BSON\Persistable;
use Webamooz\Common\Responses\AjaxResponses;
use Webamooz\Course\Models\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webamooz\Category\Repositories\CategoryRepository;
use Webamooz\Course\Http\Requests\CourseRequest;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Course\Repositories\LessonRepository;
use Webamooz\Media\Services\MediaFileService;
use Webamooz\Payment\Gateways\Gateway;
use Webamooz\Payment\Providers\PaymentServiceProvider;
use Webamooz\Payment\Repositories\PaymentRepositories;
use Webamooz\Payment\Service\PaymentService;
use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Repositories\UserRepository;

class CourseController extends Controller
{

    public function index(CourseRepository $courseRepository)
    {
        $this->authorize('index', Course::class);  //اسم متود یا قابلیت + اسم مادل که وصل شده به پالیسی + Course::class برای تمامی دوره ها میتونه استفاده کنه

//      if (auth()->user()->hasPermissionTo(Permission::PERMISSION_MANAGE_COURSES) || auth()->user()->hasPermissionTo(Permission::PERMISSION_SUPER_ADMIN)) {
        if (auth()->user()->hasAnyPermission([Permission::PERMISSION_MANAGE_COURSES, Permission::PERMISSION_SUPER_ADMIN])) {
            $courses = $courseRepository->paginate();
        } else {
            $courses = $courseRepository->getCoursesByTeacherId(auth()->id());
        }

        return view('Course::index', compact('courses'));
    }

    public function create(UserRepository $userRepository, CategoryRepository $categoryRepository)
    {
        $this->authorize('create', Course::class);
        $categories = $categoryRepository->pluck();
        $teachers = $userRepository->getTeacher();
        return view('Course::create', compact('teachers', 'categories'));
    }

    public function store(CourseRequest $request)
    {
        //بیا از طریق رکوست بیا بگیر مقدار عکس و آیدی تصویر و بریز درون بنر آیدی و برای ساختنش اسم banner_id صدا میزنیم در store
        $this->authorize('store', Course::class);
        $request->request->add([
            'banner_id' => MediaFileService::publicUpload($request->file('image'))->id
        ]);
        (new CourseRepository())->store($request);
        return to_route('courses.index');
    }

    public function show(Course $course)
    {
        //
    }

    public function edit($id, CourseRepository $courseRepository, CategoryRepository $categoryRepository, UserRepository $userRepository)
    {
        $course = $courseRepository->findById($id);
        $this->authorize('edit', $course);  //need course -> اون دوره ای که ویرایش قرار بشه فرستادیم + $course -> فقط برای این دوره محدودیت میتونیم بزاریم به جز مدیر دوره ها
        $categories = $categoryRepository->pluck();
        $teachers = $userRepository->getTeacher();
        return view('Course::edit', compact('course', 'categories', 'teachers'));
    }

    public function update(CourseRequest $request, $course_id, CourseRepository $courseRepository)
    {
        $course = $courseRepository->findById($course_id);
        $this->authorize('edit', $course);

        if ($request->hasFile('image')) {
            $request->request->add(['banner_id' => MediaFileService::publicUpload($request->file('image'))->id]);
            if ($course->media)
                $course->media->delete();  //call in Model -> protected static function booted() ->  static::deleting(func)
        } else {
            $request->request->add(['banner_id' => $course->banner_id]);  //اگر تصویری وجود نداشت بیا همون عکس قبلی بگیر
        }
        $courseRepository->update($course_id, $request);
        return redirect(route('courses.index'));
    }


    public function destroy($course_id, CourseRepository $courseRepository)
    {
        $course = $courseRepository->findById($course_id);
        $this->authorize('delete', $course);
        if ($course->media) {
            $course->media->delete();  //call in Model -> protected static function booted() ->  static::deleting(func)
        }
        $course->delete();
        return AjaxResponses::successResponse('دوره شما با موفقیت حذف شد');
    }


    public function accept($course_id, CourseRepository $courseRepository)
    {
        $this->authorize('change_confirmation_status', Course::class);  //just chanage manage course
        if ($courseRepository->updateConfirmationStatus($course_id, Course::CONFIRMATION_STATUS_ACCEPTED)) {
            return AjaxResponses::successResponse("دوره با موفقیت تایید شد");
        }
        return AjaxResponses::failedResponse("تایید دوره با خطا مواجه شد!");
    }

    public function reject($course_id, CourseRepository $courseRepository)
    {
        $this->authorize('change_confirmation_status', Course::class);  //Course::class -> بر روی تمام دوره ها اعمال بشه بتونه تغییر بده
        if ($courseRepository->updateConfirmationStatus($course_id, Course::CONFIRMATION_STATUS_REJECTED)) {
            return AjaxResponses::successResponse("دوره با موفقیت رد شد");
        }
        return AjaxResponses::failedResponse("رد دوره با خطا مواجه شد!");
    }

    public function lock($course_id, CourseRepository $courseRepository)
    {
        $this->authorize('change_confirmation_status', Course::class);
        if ($courseRepository->updateStatus($course_id, Course::STATUS_LOCKED)) {
            return AjaxResponses::successResponse("دوره با موفقیت قفل شد");
        }
        return AjaxResponses::failedResponse("قفل دوره با خطا مواجه شد!");
    }

    public function details($course_id, CourseRepository $courseRepository, LessonRepository $lessonRepository)
    {
        $course = $courseRepository->findById($course_id);
        $lessons = $lessonRepository->paginate($course_id);
        $this->authorize('details', $course);
        return view('Course::details', compact('course', 'lessons'));
    }

    public function buy($course_id, CourseRepository $courseRepository)
    {
        $course = $courseRepository->findById($course_id);
        if (!$this->courseCanBePurchased($course)) {  //اگر دوره قابل خریداری نیست
            return back();
        }

        if (!$this->authUserCanPurchasedCourse($course)) {  //کسایی که دوره خریدن یا دسترسی دارن مثل ادمین و مدرس
            return back();
        }

        //[$amount, $discounts] -> چون آرایه صفر و یک میاد درون ایندکس صفر قیمت و درون ایندکس یک مقدار تخفیفی که حداکثر دو تا و از نوع آرایه مقدار ولیو
        [$amount, $discounts] = $course->getFinalPrice(\request()->code , true);  //مقدار نهایی که اگر تخفیفی داشه باشد ازش کم میشه + همرااه تخفیف اگر بود لیست آیدی دو تا تخفیفی حداکثر
        if ($amount <= 0) {  //course free
            $courseRepository->addStudentToCourse($course, auth()->id());  //برای زمانی که دوره ای رایگان یا با تخفیف میش عددش صفر نیازی نیست بره درگاه پرداخت
            $this->newFeedback("عملیات موفقیت آمیز", "شما با موفقیت در دوره عضو شدید", "success");
            return redirect($course->path());
        }
        dd(number_format($amount), $course, auth()->user(), $course->teacher_id ,$discounts);
        $payment = PaymentService::generate($amount, $course, auth()->user(), $course->teacher_id, $discounts);
        resolve(Gateway::class)->redirect($payment->invoice_id);
    }

    public function courseCanBePurchased($course)
    {
        //course -> اگر دوره ای رایگان + وضعیتش قفل + یا چیزی جز تایید شده بود قابل خرید نیست
        if ($course->type == Course::TYPE_FREE) {
            $this->newFeedback("عملیات نا موفقی", "دوره های رایگان قابل خریداری نیست!", "error");
            return false;
        }
        if ($course->status == Course::STATUS_LOCKED) {
            $this->newFeedback("عملیات نا موفقی", "این دوره قفل شده است و فعلا قابل خریداری نیست!", "error");
            return false;
        }
        if ($course->confirmation_status != Course::CONFIRMATION_STATUS_ACCEPTED) {
            $this->newFeedback("عملیات نا موفقی", "دوره انتخابی شما هنوز تایید نشده نیست!", "error");
            return false;
        }
        return true;
    }

    public function authUserCanPurchasedCourse($course)
    {
        if (auth()->id() == $course->teacher_id) {
            $this->newFeedback("عملیات نا موفقی", "شما مدرس این دوره هستید", "error");
            return false;
        }

        if (auth()->user()->can("download", $course)) {
            $this->newFeedback("عملیات نا موفقی", "شما به دوره دسترسی دارید", "error");
            return false;
        }

        return true;
    }

    public function downloadAllLinks($course_id, CourseRepository $courseRepository)
    {
        $course = $courseRepository->findById($course_id);
        $this->authorize('download', $course);
        return implode('<br/>', $course->downloadAllLinks());  //convert array to string
    }

    function newFeedback($heading = 'موفقیت آمیر', $text = 'عملیات با موفقیت انجام شد', $type = 'success')
    {
        $session = session()->has('feedbacks') ? session()->get('feedbacks') : [];  //هر چند تا که بسازیم سشن به اسم فیدبکز میاد صدا میزنه فراخوانی میکنه
        $session[] = ["heading" => $heading, "text" => $text, "type" => $type];  //session is array
        session()->flash('feedbacks', $session);
    }


}
