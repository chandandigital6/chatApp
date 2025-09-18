<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view users', only: ['index']),
            new Middleware('permission:edit users', only: ['edit']),
            new Middleware('permission:delete users', only: ['delete']),
            new Middleware('permission:create users', only: ['create']),
        ];
    }
    public function index()
    {
        // Check if the logged-in user is a Super Admin
        if (Auth::user()->hasRole('Super Admin')) {
            $userData = User::all(); // Show all users
        } else {
            $userData = User::where('id', Auth::id())->get(); // Show only the logged-in user
        }

        return view('user.index', compact('userData'));
    }

    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        $selectedRoles = []; // Empty array for new users (no roles assigned)

        return view('user.edit', compact('roles', 'selectedRoles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => ['nullable', 'string', 'regex:/^\+?[0-9]{7,15}$/'],
            'password' => 'required|min:6',
            'roles' => 'array|required',
            'roles.*' => 'exists:roles,id',
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number'=>$request->phone_number,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        // Assign roles
        $user->roles()->attach($request->roles);

        return redirect()->route('user.index')->with('success', 'User created successfully.');
    }


    public function edit(User $user)
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        $selectedRoles = $user->roles()->pluck('id')->toArray(); // ✅ Get assigned role IDs

        return view('user.edit', compact('user', 'roles', 'selectedRoles'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => ['nullable', 'string', 'regex:/^\+?[0-9]{7,15}$/'],
            'roles' => 'array|required',
            'roles.*' => 'exists:roles,id',
            'password' => 'nullable|min:6', // Password is optional when updating
        ]);

        // Update user data
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number'=>$request->phone_number,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Sync roles to update user roles correctly
        $user->roles()->sync($request->roles);

        return redirect()->route('user.index')->with('success', 'User updated successfully.');
    }


    // public function update(Request $request, User $user) {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'roles' => 'array|required',
    //         'roles.*' => 'exists:roles,id',
    //        'email' => 'required|email|unique:users,email,' . $user->id,

    //     ]);

    //     // Update user data
    //     $user->update(['name' => $request->name]);

    //     // Sync roles to update user roles correctly
    //     $user->roles()->sync($request->roles);

    //     return redirect()->route('user.index')->with('success', 'User updated successfully.');
    // }



    public function assignPermissionForm($user)
    {

        $user = User::findOrFail($user);

        // dd($user);
        $permissions = Permission::all();
        $userPermissions = $user->getDirectPermissions()->pluck('user')->toArray();

        return view('user.assign_permission', compact('user', 'permissions', 'userPermissions'));
    }


    public function assignPermissionToUser(Request $request, $user)
    {
        // dd($request);
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
    
        $user = User::findOrFail($user);
    
        // Convert permission IDs to names
        $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();
    
        // Now sync by names
        $user->syncPermissions($permissionNames);

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }


    public function delete($id)
{
    $user = User::findOrFail($id);

    // Optional: prevent deletion of currently logged-in user
    if (auth()->id() === $user->id) {
        return redirect()->back()->withErrors(['You cannot delete your own account.']);
    }

    $user->delete();

    return redirect()->route('user.index')->with('success', 'User deleted successfully.');
}

}
