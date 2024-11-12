<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('code')->nullable();  //کد تخفیف
            $table->unsignedTinyInteger('percent');  //درصد تخفیف
            $table->unsignedBigInteger('usage_limitation')->nullable();  //تعداد محدودیت افراد برای استفاده از کد تخفیف + میتونه نال باش و نامحدود
            $table->timestamp('expire_at')->nullable();  //تاریخ انقضا + اگر نال بود محدودیت زمانی نداره
            $table->string('link', 300)->nullable();  //لینک اطلاعات بیشتر
            $table->string('description')->nullable();  //توضیحات
            $table->enum('type', [\Webamooz\Discount\Models\Discount::$types])
                ->default(\Webamooz\Discount\Models\Discount::TYPE_ALL);
            $table->unsignedBigInteger('uses')->default(0);  //تا چند نفر تا الان از این کد تخفیف استفاده کردن
            $table->timestamps();
        });

        Schema::create('discountables', function (Blueprint $table) {
            $table->foreignId('discount_id');  //آیدی اون تخفیف
            $table->foreignId('discountable_id');  //آیدی تخفیف برای دوره یا محصول یا ی دسته بندی مثل وب
            $table->string('discountable_type');  //اسم مادل برای تخفیف محصول یا دوره یا ی دسته بندی مثل فرانت اند
            //برای انتخاب پرایمری میاد اسمش میشه نام سه تا ستون که زیاد و خطا میده مای اسکول میایم یک اسم تعریف میکنیم این در حالت آرایه خطا میده نه تکی
            $table->primary(['discount_id', 'discountable_id', 'discountable_type'], 'discountable_key');
        });

    }

    public function down()
    {
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('discountables');
    }
};
