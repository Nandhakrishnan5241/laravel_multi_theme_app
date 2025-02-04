<?php

use App\Admin\Modules\Models\Module;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
Route::get('/', function () {
    return redirect('bsadmin/dashboard');
    return view('admin.dashboard');
})->middleware(['auth', 'verified']);

Route::get('bsadmin/dashboard', function () {
    $modules   = Module::where('dashboard', 1)->get();
    return view('admin.dashboard',compact('modules'));
})->middleware(['auth', 'verified'])->name('bsadmin.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/changepassword', [AuthController::class, 'changePassword'])->middleware('auth')->name('changepassword');

require __DIR__.'/auth.php';
