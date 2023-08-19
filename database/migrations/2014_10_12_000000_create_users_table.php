<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username' , 50)->nullable();
            $table->string('mobile' , 14)->nullable()->unique();
            $table->string('headline')->nullable();  //job,Individual profile(for example programming)
            $table->string('bio')->nullable();  //biography
            $table->string('website')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('telegram')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('status' , ['active', 'inactive' , 'ban']);  //1-name column  2)allow(I can only use these amounts)
            $table->rememberToken();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('users');
    }
};
