<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class AdminRolePermissionController extends Controller
{
    /**
     * Display a listing of the roles.
     */
    public function roles()
    {
        $roles = Role::with('permissions')->get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Display a listing of the permissions.
     */
    public function permissions()
    {
        $permissions = Permission::paginate(25);
        return view('admin.permissions.index', compact('permissions'));
    }
}
