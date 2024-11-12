<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');  //اگر مدرس دوره حذف شد بیا دوره هم پاک کن به صورت آبشاری
            $table->unsignedBigInteger('category_id')->nullable();  //اگر دسته بندی حذف شد دوره ها بشن نال نه پاک بشن
            $table->unsignedBigInteger('banner_id')->nullable();  //اگر مدیا حذف شد دوره ها بشن نال نه پاک بشن
            $table->string('title');
            $table->string('slug')->unique();
            $table->float('priority')->nullable();  //تربیت اعداد برای مرتب سازی دوره + فلوت چون بشه بین دو دوره گذاشت مثل 1.3
            $table->string('price', 10);
            $table->string('percent', 5);  //درصد مدرس از دوره که تا پنح حرف
            $table->enum('type', [\Webamooz\Course\Models\Course::$types]);  //allow(پارامتر دوم این مقدار مجازی بفرستی) + $types -> ['free','cash]
            $table->enum('status', [\Webamooz\Course\Models\Course::$statuses]);  //$statuses -> ['completed','not_completed','locked]
            $table->enum('confirmation_status', [\Webamooz\Course\Models\Course::$confirmationStatuses]);
            $table->longText('body')->nullable();

            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('SET NULL');
            $table->foreign('banner_id')->references('id')->on('media')->onDelete('SET NULL');  //image id
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
