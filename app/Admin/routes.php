<?php

use App\Admin\Categories\Controllers\CategoryController;
use App\Admin\Clients\Controllers\ClientsController;
use App\Admin\Modules\Controllers\ModulesController;
use App\Admin\Permissions\Controllers\PermissionsController;
use App\Admin\Privileges\Controllers\PrivilegesController;

use App\Admin\Roles\Controllers\RolesController;
use App\Admin\Users\Controllers\UsersController;
use App\Admin\Products\Controllers\ProductsController;
use App\Admin\Subcategory\Controllers\SubcategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('bsadmin/modules')->middleware('auth')->group(function () {
    // Route::get('/', [ModulesController::class, 'index']);
    Route::get('/', [ModulesController::class, 'index'])->middleware('check.permission:modules.view');
    Route::get('/getdetails', [ModulesController::class, 'getDetails'])->name('modules.getdetails');
    Route::post('/save', [ModulesController::class, 'save'])->name('module.save');
    Route::post('/update', [ModulesController::class, 'update'])->name('module.update');
    Route::get('{id}/edit', [ModulesController::class, 'edit'])->name('module.edit');
    Route::get('/delete/{id}', [ModulesController::class, 'delete'])->name('module.delete');
    Route::get('/moveup/{moduleId}', [ModulesController::class, 'moveUP'])->name('module.moveup');
    Route::get('/movedown/{moduleId}', [ModulesController::class, 'moveDown'])->name('module.movedown');
});

Route::prefix('bsadmin/permissions')->middleware('auth')->group(function () {
    // Route::get('/', [PermissionsController::class, 'index']);
    Route::get('/', [PermissionsController::class, 'index'])->middleware('check.permission:permissions.view');
    Route::get('/getdetails', [PermissionsController::class, 'getDetails'])->name('permissions.getdetails');
    Route::post('/save', [PermissionsController::class, 'save'])->name('permissions.save');
    Route::post('/update', [PermissionsController::class, 'update'])->name('permissions.update');
    Route::get('{id}/edit', [PermissionsController::class, 'edit'])->name('permissions.edit');
    Route::get('/delete/{id}', [PermissionsController::class, 'delete'])->name('permissions.delete');
});

Route::prefix('bsadmin/roles')->middleware('auth')->group(function () {
    // Route::get('/', [RolesController::class, 'index']);
    Route::get('/', [RolesController::class, 'index'])->middleware('check.permission:roles.view');
    Route::get('/getdetails', [RolesController::class, 'getDetails'])->name('roles.getdetails');
    Route::post('/save', [RolesController::class, 'save'])->name('roles.save');
    Route::post('/update', [RolesController::class, 'update'])->name('roles.update');
    Route::get('{id}/edit', [RolesController::class, 'edit'])->name('roles.edit');
    Route::get('/delete/{id}', [RolesController::class, 'delete'])->name('roles.delete');
});

Route::prefix('bsadmin/users')->middleware('auth')->group(function () {
    // Route::get('/', [UsersController::class, 'index']);
    Route::get('/', [UsersController::class, 'index'])->middleware('check.permission:users.view');
    Route::get('/getdetails', [UsersController::class, 'getDetails'])->name('users.getdetails');
    Route::post('/save', [UsersController::class, 'save'])->name('users.save');
    Route::post('/update', [UsersController::class, 'update'])->name('users.update');
    Route::get('{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::get('/delete/{id}', [UsersController::class, 'delete'])->name('users.delete');
    Route::post('/changepassword', [UsersController::class, 'changePassword'])->name('users.changepassword');
});

Route::prefix('bsadmin/clients')->middleware('auth')->group(function () {
    // Route::get('/', [ClientsController::class, 'index']);
    Route::get('/', [ClientsController::class, 'index'])->middleware('check.permission:clients.view');
    Route::get('/getdetails', [ClientsController::class, 'getDetails'])->name('clients.getdetails');
    Route::post('/save', [ClientsController::class, 'save'])->name('clients.save');
    Route::post('/update', [ClientsController::class, 'update'])->name('clients.update');
    Route::get('{id}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
    Route::get('/delete/{id}', [ClientsController::class, 'delete'])->name('clients.delete');
});

Route::prefix('bsadmin/privileges')->middleware('auth')->group(function () {
    // Route::get('/', [PrevilegeController::class, 'index']);    
    Route::get('/', [PrivilegesController::class, 'index'])->middleware('check.permission:privileges.view');
    Route::get('/addpermission/{roleId}/{clientId}/{data}', [PrivilegesController::class, 'addPermissionToRole']);
    // Route::post('/addpermission', [PrivilegesController::class, 'addPermissionToRole']);
    Route::get('/getprivilegesbyclientid/{clientID}', [PrivilegesController::class, 'getPrivilegesByClientID']);
    Route::get('/getprivilegesbyroleid/{roleID}', [PrivilegesController::class, 'getPrivilegesByRoleID']);
    Route::get('/getprivilegesbyclientandroleid/{roleID}/{clientID}', [PrivilegesController::class, 'getPrivilegesByClientAndRoldID']);
});

Route::prefix('bsadmin/categories')->middleware('auth')->group(function(){
    Route::get('/', [CategoryController::class, 'index'])->middleware('check.permission:categories.view');
    Route::get('/getdetails', [CategoryController::class, 'getDetails'])->name('categories.getdetails');
    Route::get('/add', [CategoryController::class, 'add'])->name('categories.add');
    Route::get('{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('/delete/{id}', [CategoryController::class, 'delete'])->name('categories.delete');
    Route::post('/save', [CategoryController::class, 'save'])->name('categories.save');
    Route::post('/update', [CategoryController::class, 'update'])->name('categories.update');
    Route::post('/image', [CategoryController::class, 'imageUpload'])->name('categories.image');
});

Route::prefix('bsadmin/products')->middleware('auth')->group(function(){
    Route::get('/', [ProductsController::class, 'index'])->middleware('check.permission:products.view');
    Route::get('/getdetails', [ProductsController::class, 'getDetails'])->name('products.getdetails');
    Route::get('/add', [ProductsController::class, 'add'])->name('products.add');
    Route::get('{id}/edit', [ProductsController::class, 'edit'])->name('products.edit');
    Route::get('/delete/{id}', [ProductsController::class, 'delete'])->name('products.delete');
    Route::post('/save', [ProductsController::class, 'save'])->name('products.save');
    Route::post('/update', [ProductsController::class, 'update'])->name('products.update');
    Route::post('/image', [ProductsController::class, 'imageUpload'])->name('products.image');
});
Route::prefix('bsadmin/subcategory')->middleware('auth')->group(function(){
    Route::get('/', [SubcategoryController::class, 'index'])->middleware('check.permission:subcategory.view');
    Route::get('/getdetails', [SubcategoryController::class, 'getDetails'])->name('subcategory.getdetails');
    Route::get('/add', [SubcategoryController::class, 'add'])->name('subcategory.add');
    Route::get('{id}/edit', [SubcategoryController::class, 'edit'])->name('subcategory.edit');
    Route::get('/delete/{id}', [SubcategoryController::class, 'delete'])->name('subcategory.delete');
    Route::post('/save', [SubcategoryController::class, 'save'])->name('subcategory.save');
    Route::post('/update', [SubcategoryController::class, 'update'])->name('subcategory.update');
});

Route::fallback(function () {
    return redirect()->route('error.404'); // Redirect to a named route
});

// Route::get('/error-404', function () {
//     return view('errors.error_404');
// })->name('error.404');