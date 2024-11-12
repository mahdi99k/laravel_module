<?php

namespace Webamooz\Course\Repositories;

use Webamooz\Course\Models\Course;
use Webamooz\Course\Models\Lesson;
use Webamooz\Media\Services\MediaFileService;


class CourseRepository
{

    public function getAll(string $status = null)
    {
        $query = Course::query();
        if ($status) {  //not null
            return $query->where('confirmation_status', $status)->get();
        }
        return $query->latest()->get();
    }

    public function paginate()  //get permission PERMISSION_MANAGE_COURSES
    {
        return Course::paginate(10);
    }

    public function findById($course_id)
    {
        return Course::findOrFail($course_id);
    }

    public function newestCourses()
    {
        return Course::where('confirmation_status', Course::CONFIRMATION_STATUS_ACCEPTED)
            ->take(8)->latest()->get();
    }

    public function getDurationTimeLesson($course_id)
    {
        return $this->getLessonsQuery($course_id)->sum('time');  //اون دوره مد نظر درس ها بگیره اون هایی که تایید شده زمانشون جمع کن
    }

    public function getLessons($course_id)
    {
        return $this->getLessonsQuery($course_id)->get();
    }

    public function getLessonCount($course_id)
    {
        return $this->getLessonsQuery($course_id)->count();
    }

    public function getLessonsQuery($course_id)  //use 3 method up
    {
        return Lesson::where('course_id', $course_id)
            ->where('confirmation_status', Lesson::CONFIRMATION_STATUS_ACCEPTED);
    }

    public function store($values)
    {
        return Course::create([
            'teacher_id' => $values->teacher_id,
            'category_id' => $values->category_id,
            'banner_id' => $values->banner_id,  //id image +  get $request->request->add([ 'banner_id' => ... ])
            'title' => $values->title,
            'slug' => \Str::slug($values->slug), //word concat _ -> course_php_basic_2024
            'priority' => $values->priority,
            'price' => $values->price,
            'percent' => $values->percent,
            'type' => $values->type,
            'status' => $values->status,
            'confirmation_status' => Course::CONFIRMATION_STATUS_PENDING,
            'body' => $values->body,
        ]);
    }

    public function update($course_id, $values)
    {
        Course::where('id', $course_id)->update([
            'teacher_id' => $values->teacher_id,
            'category_id' => $values->category_id,
            'banner_id' => $values->banner_id,  //id image +  get $request->request->add([ 'banner_id' => ... ])
            'title' => $values->title,
            'slug' => \Str::slug($values->slug), //word concat _ -> course_php_basic_2024
            'priority' => $values->priority,
            'price' => $values->price,
            'percent' => $values->percent,
            'type' => $values->type,
            'status' => $values->status,
            'confirmation_status' => Course::CONFIRMATION_STATUS_PENDING,
            'body' => $values->body,
        ]);
    }

    public function updateConfirmationStatus($course_id, $confirm_status)
    {
        return Course::where('id', $course_id)->update(['confirmation_status' => $confirm_status]);
    }

    public function updateStatus($course_id, $status)
    {
        return Course::where('id', $course_id)->update(['status' => $status]);
    }

    public function getCoursesByTeacherId(int $id)  //must create owner course
    {
        return Course::where('teacher_id', $id)->paginate();
    }

    public function addStudentToCourse(Course $course, $student_id)
    {
        if (!$this->getExistCourseStudentById($course, $student_id)) {  //دانشجوی این دوره نبود اضافه بکن وگنه قبلا این دوره خریده
            $course->students()->attach($student_id);
        }
    }

    public function getExistCourseStudentById(Course $course, $student_id)
    {
        return $course->students()->where('id', $student_id)->first();
    }

    public function hasStudent(Course $course, $student_id)
    {
        return $course->students->contains($student_id);
    }


}
