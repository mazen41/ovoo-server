<?php

namespace App\Traits;

use App\Constants\Status;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

trait RoleOperation
{
    public function list()
    {
        $roles     = Role::searchable(['name'])->orderBy('id', getOrderBy())->get();
        $pageTitle = 'Manage Role';
        $view      = "admin.role.list";
        return responseManager("roles", $pageTitle, 'success', compact('roles', 'view', 'pageTitle'));
    }

    public function save(Request $request, $id = 0)
    {
        $request->validate([
            'name' => "required|unique:roles,name," . $id,
        ]);

        if ($id) {
            $role    = Role::findOrFail($id);
            $message = "Role updated successfully";
            $remark  = "role-updated";
        } else {
            $role    = new Role();
            $message = "Role added successfully";
            $remark  = "role-added";
        }

        $role->name       = $request->name;
        $role->guard_name = 'admin';
        $role->save();

        return responseManager("role", $message, 'success', compact('role'));
    }


    public function permission($id)
    {
        $role                = Role::findOrFail($id);
        $pageTitle           = 'Edit Permission - ' . $role->name;
        $view                = "admin.role.permission";
        $permissions         = Permission::get();
        $existingPermissions = $role->getAllPermissions();

        return responseManager("role", $pageTitle, 'success', compact('role', 'view', 'pageTitle', 'permissions', 'existingPermissions'));
    }

    public function permissionUpdate(Request $request, $id)
    {
        $request->validate([
            'permissions'   => "nullable|array|min:1",
            'permissions.*' => "nullable|integer",
        ]);

        $role = Role::findOrFail($id);
        if ($role->id == Status::SUPER_ADMIN_ROLE_ID) { //super admin permission never changed
            $message = "The super admin permission cannot be modified anymore.";
            return responseManager("role", $message, 'error');
        }
        $permissions = Permission::whereIn('id', $request->permissions ?? [])->pluck('id')->toArray();
        $role->syncPermissions($permissions ?? []);

        $message = "Permission updated successfully";
        return responseManager("role", $message, 'success', compact('role'));
    }
}
