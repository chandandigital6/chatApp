<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view roles', only:['index']),
            new Middleware('permission:edit roles', only:['edit']),
            new Middleware('permission:delete roles', only:['delete']),
            new Middleware('permission:create roles', only:['create']),
        ];
    }
    public function index() {
        $roleData = Role::with('permissions')->get(); // Load roles with permissions
      
        return view('role.index', compact('roleData'));
    }
    


    public function create() {
        $permissions=Permission::all();
        return view('role.create',compact('permissions'));
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array|required', // Fix: 'permissions' instead of 'permission'
            'permissions.*' => 'exists:permissions,id' // Ensure permissions exist in DB
        ]);
    
        // Create the role
        $role = Role::create(['name' => $request->name]);
    
        // Assign permissions correctly using IDs
        if (!empty($request->permissions)) {
            $role->permissions()->sync($request->permissions);
        }
    
        // Redirect with success message
        return redirect()->route('role.index')->with('success', 'Role created successfully.');
    }
    
    
    public function edit(Role $role) {
        $permissions = Permission::all(); 
        $selectedPermissions = $role->permissions->pluck('id')->toArray(); // Get assigned permissions
    
        return view('role.create', compact('role', 'permissions', 'selectedPermissions'));
    }
    
    

    public function update(Request $request, Role $role) {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array|required',
            'permissions.*' => 'exists:permissions,id'
        ]);
    
        // Update role name
        $role->update(['name' => $request->name]);
    
        // Sync permissions
        $role->permissions()->sync($request->permissions);
    
        return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    }
    
    

    public function delete(Role $role)
    {
        // Detach all permissions before deleting the role
        $role->permissions()->detach();
    
        // Delete the role
        $role->delete();
    
        return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
    }

   
    
}
