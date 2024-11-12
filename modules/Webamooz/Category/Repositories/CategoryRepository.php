<?php

namespace Webamooz\Category\Repositories;

use Webamooz\Category\Models\Category;

class CategoryRepository
{

    public function all()
    {
        return Category::all();
    }

    public function tree()
    {
        return Category::where('parent_id', null)->with('subCategories')->get();  //subCategories -> کوری سبک تر همراه ریلیشن بگیره
    }

    public function pluck()
    {
        return Category::pluck('title', 'id');
    }


    public function store($values)
    {
        Category::create([
            'title' => $values->title,
            'slug' => $values->slug,
            'parent_id' => $values->parent_id,
        ]);
    }

    public function getById($category_id)
    {
        return Category::query()->findOrFail($category_id);
    }

    public function EditedExceptById($category_id)
    {
        return $this->all()->filter(function ($item) use ($category_id) {  //use -> مقدار پاس میدیم درون این فانکشن که دسترسی داشته باشیم
            return $item->id != $category_id;  //با استفاده از متودی که کل دیتا گرفتیم فیلتر میزنیم اون آیدی ک ههست نمایش نده
        });
//     return Category::where('id', '!=', $category_id)->get();  //این عنوان دسته بندی که ویرایش میکنیم نمایش نده
    }

    public function update($category_id, $values)
    {
        Category::query()->where('id', $category_id)->update([
            'title' => $values->input('title'),
            'slug' => $values->input('slug'),
            'parent_id' => $values->input('parent_id')
        ]);
    }

    public function delete($category_id)
    {
        return Category::where('id', $category_id)->delete();
    }


}
