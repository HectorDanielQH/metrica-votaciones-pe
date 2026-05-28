<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()->with('permissions')->orderBy('name')->get()->keyBy('name');

        $permissionGroups = Permission::query()
            ->orderBy('name')
            ->get()
            ->groupBy(function (Permission $permission) {
                return explode('.', $permission->name)[0];
            });

        return view('admin.permissions.index', [
            'roles' => $roles,
            'permissionGroups' => $permissionGroups,
        ]);
    }
}
