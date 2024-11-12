<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->unsignedTinyInteger('number');  //if null -> autoincrement increase -> دستی میایم میگیم اضافه بشه خودکار اگر خالی بود
            $table->enum('confirmation_status', [\Webamooz\Course\Models\Season::$confirmationStatuses])
                ->default(\Webamooz\Course\Models\Season::CONFIRMATION_STATUS_PENDING);
            $table->enum('status', [\Webamooz\Course\Models\Season::$statuses])
                ->default(\Webamooz\Course\Models\Season::STATUS_OPENED);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('CASCADE');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seasons');
    }
};
