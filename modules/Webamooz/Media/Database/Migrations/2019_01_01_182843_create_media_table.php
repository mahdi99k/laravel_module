<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->json('files');  //multiple files in column
            $table->enum('type', ['image', 'video', 'audio', 'zip', 'doc']);  //enums(columnName  , [allow access])
            $table->string('filename', 255);
            $table->boolean('is_private');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
};
