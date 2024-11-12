<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('CASCADE');
            $table->foreignId('ticketable_id');  //Model + able + _id
            $table->string('ticketable_type');  //Model + able + _type
            $table->string('title');
            $table->enum('status', [\Webamooz\Ticket\Models\Ticket::$statuses])
                ->default(\Webamooz\Ticket\Models\Ticket::STATUS_OPEN);
            $table->timestamps();
//          $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
