<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->unique();
            $table->string('username', 50)->nullable();
            $table->string('mobile', 14)->nullable()->unique();
            $table->string('head_line')->nullable();  //معرفی کوتاه خود
            $table->text('bio')->nullable();
            $table->string('ip')->nullable();
            $table->string('telegram')->nullable();
            $table->unsignedBigInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('media')->onDelete('SET NULL');
            $table->string('card_number', 16)->nullable();
            $table->string('sheba', 24)->nullable();
            $table->bigInteger('balance')->default(0);  //موجودی حساب فعلی

            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->string('password');
            $table->string('status')->default(\App\Enums\UserStatus::Active->value);
//          $table->enum('status', ['active', 'deactive', 'ban']);  //1-name column  2)allow(I can only use these amounts)
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('users');
    }
};
