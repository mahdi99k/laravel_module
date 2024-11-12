<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('season_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('media_id')->nullable();
            $table->string('title');
            $table->string('slug');  //اگر کاربر وارد نکرد خودمون خودکار میسازیم از طریق عنوان درس
            $table->boolean('is_free')->default(false);
            $table->longText('body')->nullable();
            $table->unsignedTinyInteger('time')->nullable();
            $table->unsignedInteger('number')->nullable();
            $table->enum('confirmation_status', [\Webamooz\Course\Models\Lesson::$confirmationStatuses])
                ->default(\Webamooz\Course\Models\Lesson::CONFIRMATION_STATUS_PENDING);
            $table->enum('status', [\Webamooz\Course\Models\Lesson::$statuses])
                ->default(\Webamooz\Course\Models\Lesson::STATUS_OPENED);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('CASCADE');
            $table->foreign('season_id')->references('id')->on('seasons')->onDelete('SET NULL');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('SET NULL');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lessons');
    }
};
