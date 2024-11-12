<?php

namespace Webamooz\Category\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webamooz\Course\Models\Course;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'slug',
        'parent_id',
    ];

    //---------- Relationship
    public function getParentAttribute()  //get + Name + Attribute -> با صدا زدن نام که میدیم دسترسی داریم به عملیاتی که مینویسیم
    {
        return (is_null($this->parent_id)) ? 'ندارد' : $this->parentCategory->title;  //اگر دسته پدر نال بود بنویس ندارد اگر نبود بیا عنوان دسته پدرش نمایش بده
    }

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    //---------- Method
    public function path()
    {
        return route('categories.show', $this->id);
    }


}
