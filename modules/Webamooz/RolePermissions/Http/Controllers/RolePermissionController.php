<?php

namespace Webamooz\RolePermissions\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Webamooz\RolePermissions\Http\Requests\RoleRequest;
use Webamooz\RolePermissions\Http\Requests\RoleUpdateRequest;
use Webamooz\RolePermissions\Model\Role;
use Webamooz\RolePermissions\Repositories\PermissionRepository;
use Webamooz\RolePermissions\Repositories\RoleRepository;
use Webamooz\Common\Responses\AjaxResponses;


class RolePermissionController extends Controller
{

    private $roleRepository;
    private $permissionRepository;

    public function __construct(RoleRepository $roleRepository, PermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index()
    {
        $this->authorize('index', Role::class);
        $roles = $this->roleRepository->all();
        $permissions = $this->permissionRepository->all();
        return view('RolePermissions::index', compact('roles', 'permissions'));
    }


    public function create()
    {
        //
    }


    public function store(RoleRequest $request)
    {
        $this->authorize('create', Role::class);
        $this->roleRepository->store($request);
        return redirect(route('role-permissions.index'));
    }


    public function show($id)
    {
        //
    }


    public function edit($role_id)
    {
        $this->authorize('edit', Role::class);
        $role = $this->roleRepository->getById($role_id);
        $permissions = $this->permissionRepository->all();
        return view('RolePermissions::edit', compact('role', 'permissions'));
    }


    public function update(RoleUpdateRequest $request, $role_id)
    {
        $this->authorize('edit', Role::class);
        $this->roleRepository->update($role_id, $request);
        return to_route('role-permissions.index');
    }

    public function destroy($role_id)
    {
        $this->authorize('delete', Role::class);
        $this->roleRepository->destroy($role_id);
        return AjaxResponses::successResponse('نقش کاربری با موفقیت حذف شد');
    }

}
