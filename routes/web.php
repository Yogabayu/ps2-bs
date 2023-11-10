<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\UserActivityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [AuthController::class,'index']);
Route::post('login', [AuthController::class,'login'])->name('login');

// admin
Route::middleware('auth','role:1')->group(function () {
    Route::get('logout', [AuthController::class,'logout'])->name('logout');
    Route::get('dashboard',[DashboardController::class,'index'])->name('indexAdmin');

    //user
    Route::resource('user',UserController::class);
    Route::resource('user-activity',UserActivityController::class);
    Route::resource('office',OfficeController::class);
});
