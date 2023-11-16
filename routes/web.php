<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DataController as AdminDataController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\OfficeController;
use App\Http\Controllers\Admin\PlaceTransactionController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SubordinateController;
use App\Http\Controllers\Admin\TransactionTypeController;
use App\Http\Controllers\Admin\UserActivityController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\DataController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
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

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index']);
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

// admin
Route::middleware('auth', 'role:1')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('indexAdmin');

    Route::resource('user', UserController::class);
    Route::resource('user-activity', UserActivityController::class);
    Route::resource('office', OfficeController::class);
    Route::resource('position', PositionController::class);
    Route::resource('place-transc', PlaceTransactionController::class);
    Route::resource('transc-type', TransactionTypeController::class);
    Route::resource('setting-app', SettingController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('datas', AdminDataController::class);
    Route::resource('monitoring', MonitoringController::class);

    // subordinate
    Route::resource('subordinate', SubordinateController::class);
    Route::get('detail-subordinate', [SubordinateController::class, 'detail'])->name('detail-subordinate');
});

//user
Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('u-dashboard', UserDashboardController::class);
    Route::resource('u-data', DataController::class);
    Route::resource('u-profile', UserProfileController::class);
});
