<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BroadcastDMController;
use App\Http\Controllers\DirectMessageController;
use App\Http\Controllers\DmAccessController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InboxController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Query\IndexHint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;





// frontend routes
Route::view('/', 'welcome')->name('home');


Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth','verified'])->name('dashboard');


Route::middleware(['auth','role:Super Admin'])->group(function () {
  Route::get('/admin/users/{user}/dm-access', [DmAccessController::class,'edit'])->name('admin.dm.edit');
  Route::post('/admin/users/{user}/dm-access', [DmAccessController::class,'update'])->name('admin.dm.update');
 
    
});

Route::middleware(['auth','can:chat-broadcast']) // ← apni policy/permission lagao
    ->group(function () {
        Route::get('/admin/dm/broadcast', [BroadcastDMController::class, 'create'])
            ->name('dm.broadcast.create');
        Route::post('/admin/dm/broadcast', [BroadcastDMController::class, 'store'])
            ->name('dm.broadcast.store');
});


Route::middleware('auth')->group(function () {
    Route::get('/dm/{user}',  [DirectMessageController::class, 'show'])->name('dm.show');
    Route::post('/dm/{user}', [DirectMessageController::class, 'store'])->name('dm.store');
    // NEW: people directory to start a chat with anyone

     Route::get('/people', [DmAccessController::class, 'index'])
        ->middleware('permission:chat-anyone')
        ->name('dm.people');

          Route::get('/messages', [DirectMessageController::class, 'index'])->name('dm.inbox'); // for everyone

    Route::get('/support-chat', function () {
        $adminId = (int) env('CHAT_SUPER_ADMIN_ID', 1);
        $admin   = User::findOrFail($adminId);
        return redirect()->route('dm.show', $admin->id);
    })->name('dm.admin');
});

// Routes that require authentication
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Permission Routes
    Route::get('permission/index',[PermissionController::class,'index'])->name('permission.index');
    Route::get('permission/create',[PermissionController::class,'create'])->name('permission.create');
    Route::post('permission/store',[PermissionController::class,'store'])->name('permission.store');
    Route::get('permission/edit/{permission}',[PermissionController::class,'edit'])->name('permission.edit');
    Route::post('permission/update/{permission}',[PermissionController::class,'update'])->name('permission.update');
    Route::get('permission/delete/{permission}',[PermissionController::class,'delete'])->name('permission.delete');

    // Role Routes
    Route::get('role/index',[RoleController::class,'index'])->name('role.index');
    Route::get('role/create',[RoleController::class,'create'])->name('role.create');
    Route::post('role/store',[RoleController::class,'store'])->name('role.store');
    Route::get('role/edit/{role}',[RoleController::class,'edit'])->name('role.edit');
    Route::post('role/update/{role}',[RoleController::class,'update'])->name('role.update');
    Route::get('role/delete/{role}',[RoleController::class,'delete'])->name('role.delete');

    // User Routes
    Route::get('user/index',[UserController::class,'index'])->name('user.index');
    Route::get('user/create',[UserController::class,'create'])->name('user.create');
    Route::post('user/store',[UserController::class,'store'])->name('user.store');
    Route::get('user/edit/{user}',[UserController::class,'edit'])->name('user.edit');
    Route::post('user/update/{user}',[UserController::class,'update'])->name('user.update');
    Route::get('user/delete/{user}',[UserController::class,'delete'])->name('user.delete');
    Route::get('/user/permissions/{user}', [UserController::class, 'assignPermissionForm'])->name('user.permission.form');
    Route::post('/user/permissions/{user}', [UserController::class, 'assignPermissionToUser'])->name('user.assign-permission');


});













require __DIR__.'/auth.php';
