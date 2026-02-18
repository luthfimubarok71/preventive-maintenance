<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FiturController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Teknisi\FmeaController;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\AccountController;

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

/*
|--------------------------------------------------------------------------
| DASHBOARD (DEFAULT /)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);;
Route::post('/logout', function () {
    
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');
/*
|--------------------------------------------------------------------------
| FMEA DEMO (SETELAH LOGIN)
|--------------------------------------------------------------------------
*/


Route::match(['get','post'], '/fmea-demo', [FmeaController::class, 'index'])
    ->middleware('auth');

Route::get('/hasilfmea/{id?}', [FmeaController::class, 'hasil'])
    ->middleware('auth')
    ->name('hasilfmea');

Route::get('/fmeaoutput', [FmeaController::class, 'output'])
    ->middleware('auth')
    ->name('fmeaoutput');



Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
});

Route::match(['get','post'], '/fnea', function () {
    return view('fnea');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/admin-fitur', [FiturController::class, 'index'])
        ->name('admin.admin-fitur');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])
        ->name('settings.index');
    Route::post('/settings', [App\Http\Controllers\SettingsController::class, 'store'])
        ->name('settings.store');
    Route::put('/settings', [App\Http\Controllers\SettingsController::class, 'update'])
        ->name('settings.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/account', [App\Http\Controllers\AccountController::class, 'index'])
        ->name('account.index');
    Route::put('/account', [App\Http\Controllers\AccountController::class, 'update'])
        ->name('account.update');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/penjadwalan', function () {
        return view('admin.admin-penjadwalan');
    })->name('admin.penjadwalan');
});

Route::post('/inspeksi/store',
    [InspeksiController::class,'store']
)->name('inspeksi.store');

Route::middleware(['auth', 'role:kepala_ro,pusat'])->prefix('approval')->name('approval.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ApprovalDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/schedules', [App\Http\Controllers\ApprovalDashboardController::class, 'pendingSchedules'])->name('pending.schedules');
    Route::get('/reports', [App\Http\Controllers\ApprovalDashboardController::class, 'pendingReports'])->name('pending.reports');
    Route::get('/history', [App\Http\Controllers\ApprovalDashboardController::class, 'approvedHistory'])->name('history');
    Route::get('/rejected', [App\Http\Controllers\ApprovalDashboardController::class, 'rejectedData'])->name('rejected');
    Route::post('/approve/schedule/{id}', [App\Http\Controllers\ApprovalDashboardController::class, 'approveSchedule'])->name('approve.schedule');
    Route::post('/reject/schedule/{id}', [App\Http\Controllers\ApprovalDashboardController::class, 'rejectSchedule'])->name('reject.schedule');
    Route::post('/approve/report/{id}', [App\Http\Controllers\ApprovalDashboardController::class, 'approveReport'])->name('approve.report');
    Route::post('/reject/report/{id}', [App\Http\Controllers\ApprovalDashboardController::class, 'rejectReport'])->name('reject.report');
});