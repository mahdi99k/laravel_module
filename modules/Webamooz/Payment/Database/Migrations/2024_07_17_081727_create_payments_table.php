<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id');  //student
            $table->foreignId('seller_id')->nullable();  //teacher
            $table->foreignId('paymentable_id');  //polymorphic many to many -> id
            $table->string('paymentable_type');  //polymorphic many to many -> Model
            $table->string('amount', 10);
            $table->string('invoice_id');  //transaction_id(Bank unique)
            $table->string('gateway');  //mellat + saman + meli + نام درگاه پرداخت
            $table->enum('status', \Webamooz\Payment\Models\Payment::$statuses);
            $table->tinyInteger('seller_percent')->unsigned();  //درصد فروشنده چه قدر(دوره + پروژه)
            $table->string('seller_share', 10);  //سهم فروشنده چه قدر؟
            $table->string('site_share', 10);  //سهم سایت چه قدر؟
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
