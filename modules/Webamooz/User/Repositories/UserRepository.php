<?php

namespace Webamooz\User\Repositories;

use Webamooz\RolePermissions\Model\Permission;
use Webamooz\User\Models\User;
use function PHPUnit\Framework\isNull;

class UserRepository
{

    public function getByEmail($email)
    {
        return User::query()->whereEmail($email)->first();
    }

    public function getTeacher()
    {
        //return auth()->user()->permissions -> Get all permissions
        return User::permission(\Webamooz\RolePermissions\Model\Permission::PERMISSION_TEACH)->get();    //get all users has permission teach
    }

    public function findById($user_id)
    {
        return User::findOrFail($user_id);
    }

    public function findByIdFullInfo($user_id)
    {
        return User::where('id', $user_id)->with('settlements', 'payments', 'courses', 'purchases')->firstOrFail();
    }

    public function paginate($paginateCount)
    {
        return User::paginate($paginateCount);
    }

    public function update($user_id, $request)
    {
        $update = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'mobile' => $request->mobile,
            'head_line' => $request->head_line,
            'telegram' => $request->telegram,
            'status' => $request->status,
            'image_id' => $request->image_id,  //$request->request()->add(['image_id' => ''])
            'bio' => $request->bio,
        ];
        if (!is_null($request->password)) {
            $update['password'] = bcrypt($request->password);
        }

        $user = User::find($user_id);
        if ($request->role) {
            $user->syncRoles($request->role);  //delete before roles + add new role
        }
        User::where('id', $user_id)->update($update);
    }

    public function updateProfile($request)
    {
        //----- normaluser
        $user = auth()->user();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        if (auth()->user()->email != $request->email) {  //اگر ایمیلی که کاربر وارد کرد مخالف ایمیل قبلی بود بیا تغییر بده و دوباره باید تایید کنه ایمیلیش
            auth()->user()->email = $request->email;
            auth()->user()->email_verified_at = null;  //email verified
        }
        //----- normaluser

        if ($user->hasPermissionTo(Permission::PERMISSION_TEACH)) {
            $user->card_number = $request->card_number;
            $user->sheba = $request->sheba;
            $user->username = $request->username;
            $user->head_line = $request->head_line;
            $user->telegram = $request->telegram;
            $user->bio = $request->bio;
        }


        if (!is_null($request->password)) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
    }

}
