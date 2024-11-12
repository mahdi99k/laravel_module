<?php

namespace Webamooz\Category\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webamooz\Category\Http\Requests\CategoryRequest;
use Webamooz\Category\Http\Requests\CategoryUpdateRequest;
use Webamooz\Category\Models\Category;
use Webamooz\Category\Repositories\CategoryRepository;
use Webamooz\Common\Responses\AjaxResponses;


class CategoryController extends Controller
{

    public $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        //چون قرار درون تمامی متود ها استفاده بشه برای جلوگیری از تگرار و کد نویسی تمیز درون کانستاکتور مینویسیم که صدا بزنه تو کل پرويه
        $this->categoryRepository = $categoryRepository;
    }

    public function index()
    {
        $this->authorize('manage', Category::class);
        if (!auth()->user()->can('manage categories')) {  //can -> exit permission
            dd('User can not permission manage categories');
        }
        $categories = $this->categoryRepository->all();  //use call with laravel
        return view('Category::index', compact('categories'));
    }

    public function create()
    {

    }

    public function store(CategoryRequest $request)
    {
        $this->authorize('manage', Category::class);
        $this->categoryRepository->store($request);
        return back()/*->with(['message' => 'دسته بندی با موفقیت ایجاد شد'])*/ ;
    }


    public function show(Category $category)
    {
        //
    }


    public function edit($category_id)
    {
        $this->authorize('manage', Category::class);
        $category = $this->categoryRepository->getById($category_id);
        $categories = $this->categoryRepository->EditedExceptById($category_id);
        return view('Category::edit', compact('category', 'categories'));
    }


    public function update(CategoryUpdateRequest $request, $category_id)
    {
        $this->authorize('manage', Category::class);
        $this->categoryRepository->update($category_id, $request);;
        return to_route('categories.index');
    }


    public function destroy($category_d)
    {
        $this->authorize('manage', Category::class);
        $this->categoryRepository->delete($category_d);
        return AjaxResponses::successResponse('دسته بندی با موفقیت حذف شد');
    }

}
