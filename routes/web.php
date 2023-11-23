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
use App\Http\Controllers\Spv\AllDataController;
use App\Http\Controllers\Spv\DashboardController as SpvDashboardController;
use App\Http\Controllers\Spv\ListUserController;
use App\Http\Controllers\Spv\MonitoringController as SpvMonitoringController;
use App\Http\Controllers\Spv\ProfileController as SpvProfileController;
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

Route::get('/', [AuthController::class, 'index']);
Route::post('login', [AuthController::class, 'login'])->name('login');

//forgt-password
Route::get('forgot-password', [AuthController::class, 'forgot_password'])->name('forgot-password');
Route::post('forgot-password-action', [AuthController::class, 'forgotaction'])->name('forgot-password-action');
Route::get('forgot-email/{token}', [AuthController::class, 'verifyForgot'])->name('forgot-email');

// admin
Route::middleware('auth', 'role:1')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('indexAdmin');

    Route::resource('user', UserController::class);
    Route::post('a-rst', [UserController::class, 'rstpwd'])->name('a-rst');

    Route::resource('user-activity', UserActivityController::class);
    Route::resource('office', OfficeController::class);
    Route::resource('position', PositionController::class);
    Route::resource('place-transc', PlaceTransactionController::class);
    Route::resource('transc-type', TransactionTypeController::class);
    Route::resource('setting-app', SettingController::class);
    Route::resource('profile', ProfileController::class);

    //data
    Route::resource('datas', AdminDataController::class);
    Route::post('a-export', [AdminDataController::class, 'export'])->name('a-export');

    // monitoring
    Route::resource('monitoring', MonitoringController::class);
    Route::post('last-monitoring', [MonitoringController::class, 'lastData'])->name('last-monitoring');

    // subordinate
    Route::resource('subordinate', SubordinateController::class);
    Route::get('detail-subordinate', [SubordinateController::class, 'detail'])->name('detail-subordinate');
});

//spv
Route::middleware('auth', 'role:2')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('s-dashboard', [SpvDashboardController::class, 'index'])->name('s-dashboard');
    Route::resource('s-profile', SpvProfileController::class);

    Route::resource('s-listuser', ListUserController::class);
    Route::post('s-listuser-rst', [ListUserController::class, 'rstpwd'])->name('s-listuser-rst');

    Route::resource('s-datas', AllDataController::class);
    Route::post('s-data-export', [AllDataController::class, 'export'])->name('s-data-export');

    Route::resource('s-monitoring', SpvMonitoringController::class);
    Route::post('s-last-monitoring', [SpvMonitoringController::class, 'lastData'])->name('s-last-monitoring');
});

//user
Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::resource('u-dashboard', UserDashboardController::class);
    Route::resource('u-data', DataController::class);
    Route::resource('u-profile', UserProfileController::class);
    Route::post('u-isprocessing', [DataController::class, 'process'])->name("u-isprocessing");
});
