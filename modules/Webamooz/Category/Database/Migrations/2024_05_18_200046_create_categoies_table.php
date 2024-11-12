<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('slug')->unique();
            $table->unsignedBigInteger('parent_id')->nullable();  //->unsigned() -> عدد منفی نباید باشد
            //onDelete('SET NULL') -> هر وقت دسته پدر مثل موبایل حذف شد، بیاد دسته سامسونگ که آیدی پدر داره که الان حذف شده بکنه نال که به مشکلی بر نخوره
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('SET NULL')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
