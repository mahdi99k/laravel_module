<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('discount_payment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id');
            $table->foreignId('payment_id');
            $table->timestamps();
            $table->foreign('discount_id')->references('id')->on('discounts')->onDelete('CASCADE');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_payment');
    }
};
