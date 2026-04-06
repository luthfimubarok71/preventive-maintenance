<?php

use App\Http\Controllers\SegmentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FiturController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InspeksiController;
use App\Http\Controllers\MaintenanceTaskController;
use App\Http\Controllers\Teknisi\FmeaController;
use App\Http\Controllers\Teknisi\TeknisiController;

use Illuminate\Support\Facades\Route;



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
    | segment
    |--------------------------------------------------------------------------
    */
    Route::resource('segments', SegmentController::class);
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


Route::post('/inspeksi/store',
    [InspeksiController::class,'store']
)->name('inspeksi.store');

Route::post('/inspeksi/submit/{id}',
    [InspeksiController::class,'submitForApproval']
)->name('inspeksi.submit');

Route::get('/inspeksi/risk-summary',
    [InspeksiController::class,'riskSummary']
)->name('inspeksi.risk-summary');

Route::middleware(['auth', 'role:teknisi,admin'])->group(function () {
    Route::get('/inspeksi/my-reports', [InspeksiController::class, 'myReports'])->name('inspeksi.my-reports');
});

/*
|--------------------------------------------------------------------------
| PM SCHEDULES (Technician Schedule Planning)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // PM Schedule routes - teknisi can create, admin can view all
    Route::get('/pm-schedules', [App\Http\Controllers\PmScheduleController::class, 'index'])->name('pm-schedules.index');
    Route::get('/pm-schedules/create', [App\Http\Controllers\PmScheduleController::class, 'create'])->name('pm-schedules.create')->middleware('role:teknisi,admin');
    Route::post('/pm-schedules', [App\Http\Controllers\PmScheduleController::class, 'store'])->name('pm-schedules.store')->middleware('role:teknisi,admin');
    
    // Risk summary API for priority auto-fill
    Route::get('/pm-schedules/risk-summary', [App\Http\Controllers\PmScheduleController::class, 'getRiskSummary'])->name('pm-schedules.risk-summary');
    
    // Existing approval routes
    Route::post('/pm-schedules/{id}/submit', [App\Http\Controllers\PmScheduleController::class, 'submitForApproval'])->name('pm-schedules.submit');
    Route::get('/pm-schedules/{id}', [App\Http\Controllers\PmScheduleController::class, 'show'])->name('pm-schedules.show');
    Route::get('/pm-schedules/{id}/edit', [App\Http\Controllers\PmScheduleController::class, 'edit'])->name('pm-schedules.edit');
    Route::put('/pm-schedules/{id}', [App\Http\Controllers\PmScheduleController::class, 'update'])->name('pm-schedules.update');
    Route::delete('/pm-schedules/{id}', [App\Http\Controllers\PmScheduleController::class, 'destroy'])->name('pm-schedules.destroy');
});


Route::get('/tasks', [MaintenanceTaskController::class, 'index'])
    ->name('tasks.index');
    
Route::middleware(['auth'])
    ->prefix('approval')
    ->name('approval.')
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard',
        [App\Http\Controllers\PmScheduleController::class, 'approvalDashboard']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | KEPALA RO
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:kepala_ro,admin')->group(function () {

        Route::get('/schedules',
            [App\Http\Controllers\PmScheduleController::class, 'pendingSchedules']
        )->name('pending.schedules');

        Route::post('/approve/{id}',
            [App\Http\Controllers\PmScheduleController::class, 'approveByRo']
        )->name('approve');

        Route::post('/reject/{id}',
            [App\Http\Controllers\PmScheduleController::class, 'rejectSchedule']
        )->name('reject');
        Route::post('/approve-group',
    [App\Http\Controllers\PmScheduleController::class, 'approveGroup']
)->name('approve-group');

Route::post('/reject-group',
    [App\Http\Controllers\PmScheduleController::class, 'rejectGroup']
)->name('reject-group');
    });


   


    /*
    |--------------------------------------------------------------------------
    | HISTORY & REJECTED
    |--------------------------------------------------------------------------
    */
    Route::get('/history',
        [App\Http\Controllers\PmScheduleController::class, 'approvalHistory']
    )->name('history');

    Route::get('/rejected',
        [App\Http\Controllers\PmScheduleController::class, 'rejectedSchedules']
    )->name('rejected');

    Route::get('/reports',
        [App\Http\Controllers\PmScheduleController::class, 'pendingReports']
    )->name('pending.reports');
    });


    
    
    Route::middleware('auth')->get('/tasks', [MaintenanceTaskController::class,'index'])
    ->name('tasks.index');

    Route::get('/task/{schedule}', [MaintenanceTaskController::class, 'show'])
    ->name('tasks.show');

     Route::post('/task/{schedule}', [InspeksiController::class, 'store'])
    ->name('tasks.store');

    Route::post('/approval/report/{id}/approve-ro', [InspeksiController::class,'approveByRo'])
    ->name('reports.approve.ro');

Route::post('/approval/report/{id}/reject-ro', [InspeksiController::class,'rejectByRo'])
    ->name('reports.reject.ro');

Route::post('/approval/report/{id}/approve-pusat', [InspeksiController::class,'approveByPusat'])
    ->name('reports.approve.pusat');

Route::post('/approval/report/{id}/reject-pusat', [InspeksiController::class,'rejectByPusat'])
    ->name('reports.reject.pusat');

    Route::get('/approval/ro-reports', [InspeksiController::class,'pendingRO'])
    ->name('approval.ro.reports');

    Route::get('/approval/pusat-reports', [InspeksiController::class,'pendingPusat'])
    ->name('approval.pusat.reports');

  Route::get('/maintenance/info', [MaintenanceTaskController::class,'info'])
    ->name('maintenance.info');



    Route::get('/report/modal/{id}', [InspeksiController::class,'modal'])
    ->name('report.modal');

    Route::get('/teknisi/dashboard', [TeknisiController::class, 'dashboard'])
    ->name('teknisi.dashboard')
    ->middleware('auth');