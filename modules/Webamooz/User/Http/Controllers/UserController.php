<?php

namespace Webamooz\User\Http\Controllers;

use App\Http\Controllers\Controller;
use Webamooz\Common\Responses\AjaxResponses;
use Webamooz\Course\Repositories\CourseRepository;
use Webamooz\Media\Services\MediaFileService;
use Webamooz\RolePermissions\Model\Role;
use Webamooz\RolePermissions\Repositories\RoleRepository;
use Webamooz\User\Http\Requests\AddRoleRequest;
use Webamooz\User\Http\Requests\UpdateProfileInformationRequest;
use Webamooz\User\Http\Requests\UpdateUserPhotoRequest;
use Webamooz\User\Http\Requests\UpdateUserRequest;
use Webamooz\User\Models\User;
use Webamooz\User\Repositories\UserRepository;

class UserController extends controller
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(RoleRepository $roleRepository)
    {
        $this->authorize('index', User::class);
        $users = $this->userRepository->paginate(10);
        $roles = $roleRepository->all();
        return view('User::Admin.index', compact('users', 'roles'));
    }

    public function edit($user_id, RoleRepository $roleRepository)
    {
        $this->authorize('edit', User::class);
        $user = $this->userRepository->findById($user_id);
        $roles = $roleRepository->all();
        return view('User::Admin.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, $user_id)
    {
        $this->authorize('edit', User::class);
        $user = $this->userRepository->findById($user_id);

        if ($request->hasFile('image')) {
            $request->request->add(['image_id' => MediaFileService::publicUpload($request->file('image'))->id]);
            if ($user->image) {  //if image exist -> delete
                $user->image->delete();
            }
        } else {
            $request->request->add(['image_id' => $user->image_id]);
        }

        $this->userRepository->update($user_id, $request);
        $this->newFeedback();
        return redirect()->back();
    }


    public function destroy($user_id)
    {
        $user = $this->userRepository->findById($user_id);
        $user->delete();
        return AjaxResponses::successResponse("کاربر با موفقیت حذف شد");
    }

    /*----- Method update photo , profile , viewProfile -----*/

    public function updatePhoto(UpdateUserPhotoRequest $request)  //route -> user photo
    {
        $this->authorize('updateProfile', User::class);
        $media = MediaFileService::publicUpload($request->file('userPhoto'));
        if (auth()->user()->image) auth()->user()->image->delete();  //image(relationship) -> if user has imaged -> go deleted
        auth()->user()->image_id = $media->id;
        auth()->user()->save();
        $this->newFeedback();
        return back();
    }

    public function profile()
    {
        $this->authorize('updateProfile', User::class);
        return view('User::Admin.profile');
    }

    public function updateProfile(UpdateProfileInformationRequest $request)
    {
        $this->authorize('updateProfile', User::class);
        $this->userRepository->updateProfile($request);
        $this->newFeedback();
        return back();
    }

    /*public function viewProfile()
    {
    }*/

    public function fullInfo($user_id)
    {
        $this->authorize('index', User::class);
        $user = $this->userRepository->findByIdFullInfo($user_id);
        return view('User::Admin.info', compact('user'));
    }




    /*----- Method manualVerify + assignRole + removeRole -----*/
    public function manualVerify($user_id)
    {
        $this->authorize('manualVerify', User::class);
        $user = $this->userRepository->findById($user_id);
        $user->markEmailAsVerified();  //email verify_at -> now()
        return AjaxResponses::successResponse("ایمیل کاربر با موفقیت تایید شد");
    }


    public function addRole(AddRoleRequest $request, User $user)
    {
        $this->authorize('addRole', User::class);
        $user->assignRole($request->role);  //saved $request->role(name role)
        $this->newFeedback("موفقیت آمیز", "نقش کاربری {$request->role} برای کاربر {$user->name} با موفقیت ثیت شد", "success");
        return back();
    }

    public function removeRole($user_id, $role_name)
    {
        $this->authorize('removeRole', User::class);
        $user = $this->userRepository->findById($user_id);
        $user->removeRole($role_name);  //role name
        return AjaxResponses::successResponse("نقش کاربر با موفقیت حذف شد");
    }


    function newFeedback($heading = 'موفقیت آمیر', $text = 'عملیات با موفقیت انجام شد', $type = 'success')
    {
        $session = session()->has('feedbacks') ? session()->get('feedbacks') : [];  //هر چند تا که بسازیم سشن به اسم فیدبکز میاد صدا میزنه فراخوانی میکنه
        $session[] = ["heading" => $heading, "text" => $text, "type" => $type];  //session is array
        session()->flash('feedbacks', $session);
    }


}
