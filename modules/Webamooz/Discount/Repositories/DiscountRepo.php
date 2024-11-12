<?php

namespace Webamooz\Discount\Repositories;

use Morilog\Jalali\Jalalian;
use phpDocumentor\Reflection\Types\Null_;
use Webamooz\Discount\Models\Discount;

class DiscountRepo
{

    public function findById($discount_id)
    {
        return Discount::where('id', $discount_id)->first();
    }

    public function paginate($count = 10)
    {
        return Discount::query()->latest()->paginate($count);
    }


    //---------- Discount Course
    public function getValidDiscountsQuery($type, $course_id = null)
    {
        $query = Discount::query()
            ->where('type', $type)
            ->where('expire_at', '>', now())  //تاریخ انتضا مثلا برای 7 روز دیگ و باید بزرگتر از تاریخ الان باشد
            ->whereNull('code');  //اگر کد تخفیف بزاریم هیچ تخفیف کلی در سایت اعمال نمیشه

        if ($course_id) $query->whereHas('courses', function ($q) use ($course_id) {
            $q->where('id', $course_id);  //Course ->where('id' , $course_id) -> میره توی ریلیشن تخففیف و جدول واسط اونایی که آیدی دوره خاص انتخاب کردیم میگیره با آیدی دوره مقایسه
        });

        $query->where(function ($query) {
            $query->where("usage_limitation", ">", "0")  //یا نال باش تعداد نامحدود + یا تعداد محدود بزگتر از صفر باشن بتونن نمایش بده
            ->orWhereNull("usage_limitation");  //این شرط اگر وجود داشته باشد میاد شرط بالایی فقط خنثی میکنه و روی بقیه تاثیری نداره
        })->orderByDesc("percent");
        return $query;
    }

    public function getGlobalBiggerDiscount()  //بزرگترین تخفیفی که در بین همه تخفیف ها
    {
        return $this->getValidDiscountsQuery(Discount::TYPE_ALL)->first();
    }

    public function getCourseBiggerDiscount($course_id)  //Model Course -> methode (getDiscountPercent)
    {
        return $this->getValidDiscountsQuery(Discount::TYPE_SPECIAL, $course_id)->first();
    }
    //---------- Discount Course


    //---------- Discount Code Front
    public function getValidDiscountByCode($code, $course_id)  //درست بود کد تخفیف بر روی دوره در فرانت سایت
    {
        return Discount::query()
            ->where('code', $code)
            ->where(function ($query) use ($course_id) {
                $query->whereHas("courses", function ($q) use ($course_id) {
                    return $q->where('id', $course_id);
                })->orWhereDoesntHave('courses');  //orWhereDoesntHave -> یا اگر ریلیشنی بر قرار نبود و این کد تخفیف سراسری بود و برای دوره خاصی نبود
            })->first();
    }
    //---------- Discount Code Front


    public function store($data)
    {
        $discount = Discount::create([
            "user_id" => auth()->id(),
            "code" => $data["code"],
            "percent" => $data["percent"],
            "usage_limitation" => $data["usage_limitation"],
            "expire_at" => $data['expire_at'] ? Jalalian::fromFormat("Y/m/d H:i:s", $data['expire_at'])->toCarbon() : null,
            "link" => $data["link"],
            "description" => $data["description"],
            "type" => $data["type"],
            "uses" => 0,  //تعداد افرادی که استفاده کردن از کد تخفیف در حالت ایجاد صفر
        ]);

        if ($discount->type == Discount::TYPE_SPECIAL) {
            $discount->courses()->sync($data['courses']);
        }
    }

    public function update($discount, array $data)
    {
        Discount::query()->where('id', $discount->id)->update([
            "code" => $data["code"],
            "percent" => $data["percent"],
            "usage_limitation" => $data["usage_limitation"],
            "expire_at" => $data['expire_at'] ? Jalalian::fromFormat("Y/m/d H:i:s", $data['expire_at'])->toCarbon() : null,
            "link" => $data["link"],
            "description" => $data["description"],
            "type" => $data["type"],
        ]);
        if ($discount->type == Discount::TYPE_SPECIAL) {
            $discount->courses()->sync($data['courses']);
        } else {
            $discount->courses()->sync([]);  //اگر حالت دوره ویژه تبدیل کرد به همه دوره ها بیا مقدار های جدول واسط حذف کن یا آرایه خالی
        }
    }

}
