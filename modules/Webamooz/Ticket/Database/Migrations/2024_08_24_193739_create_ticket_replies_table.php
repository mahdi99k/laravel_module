<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('ticket_id');
            $table->foreignId('media_id')->nullable();
            $table->text('body');
            $table->timestamps();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('media_id')->references('id')->on('media')->onDelete('set null');  //اگر مدیا یا عکس حذف شد این قسمت نال کن و حذف نکن تیکت
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_replies');
    }
};
