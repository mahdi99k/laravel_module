<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');  //کاربر حذف شد بیا نال بکن آیدی یوزر در این جدول
            $table->string('transaction_id', 50)->nullable();
            $table->json("from")->nullable();  //مبدا
            $table->json("to")->nullable();  //مقصد
            $table->timestamp('settled_at')->nullable();  //تاریخ واریز شده
            $table->integer('amount')->unsigned();
            $table->enum('status', \Webamooz\Payment\Models\Settlement::$statuses)
                ->default(\Webamooz\Payment\Models\Settlement::STATUS_PENDING);
            $table->timestamps();  //created_at -> تاریخ درخواست واریز
        });
    }

    public function down()
    {
        Schema::dropIfExists('settlements');
    }

};
