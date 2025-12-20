<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserRoleController extends Controller
{
    /**
     * Display a listing of users with their roles.
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::all();
        
        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Update the specified user's role.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name'
        ]);

        // Remove all current roles and assign the new one
        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User role updated successfully!');
    }

    /**
     * Remove role from user.
     */
    public function removeRole(User $user, $role)
    {
        $user->removeRole($role);
        
        return redirect()->back()->with('success', 'Role removed successfully!');
    }
}
