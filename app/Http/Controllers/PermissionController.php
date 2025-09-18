<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view permission', only:['index']),
            new Middleware('permission:edit permission', only:['edit']),
            new Middleware('permission:delete permission', only:['delete']),
            new Middleware('permission:create permission', only:['create']),
        ];
    }

    public function index() {
        $permissionData=Permission::all();
        return view('permission.index',compact('permissionData'));
    }


    public function create() {
        return view('permission.create');
    }

    public function store(Request $request) {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);
    
        // Create the permission
        Permission::create($request->all());
    
        // Redirect with success message
        return redirect()->route('permission.index')->with('success', 'Permission created successfully.');
    }
    

    public function edit(Permission $permission) {
        return view('permission.create',compact('permission'));
    }


    public function update(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $id,
        ]);
    
        // Find the permission and update it
        $permission = Permission::findOrFail($id);
        $permission->update($request->all());
    
        // Redirect with success message
        return redirect()->route('permission.index')->with('success', 'Permission updated successfully!');
    }
    

    public function delete(Permission $permission) {
        $permission->delete();
        return redirect()->back()->with('success','Permission deleted');
    }
}
