<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;

// Authentication routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Middleware group untuk semua authenticated users
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
    
    // Karyawan Management (SEMUA USER BISA AKSES DULU)
    Route::prefix('karyawan')->name('karyawan.')->group(function () {
        Route::get('/', [KaryawanController::class, 'index'])->name('index');
        Route::get('/create', [KaryawanController::class, 'create'])->name('create');
        Route::post('/', [KaryawanController::class, 'store'])->name('store');
        Route::get('/import', [KaryawanController::class, 'import'])->name('import');
        Route::post('/import', [KaryawanController::class, 'importStore'])->name('import.store');
        Route::get('/export', [KaryawanController::class, 'export'])->name('export');
        Route::get('/{karyawan}', [KaryawanController::class, 'show'])->name('show');
        Route::get('/{karyawan}/edit', [KaryawanController::class, 'edit'])->name('edit');
        Route::put('/{karyawan}', [KaryawanController::class, 'update'])->name('update');
        Route::delete('/{karyawan}', [KaryawanController::class, 'destroy'])->name('destroy');
    });
    
    // Payroll routes
    Route::prefix('payroll')->name('payroll.')->group(function () {
        // Pengaturan Gaji
        Route::resource('pengaturan', \App\Http\Controllers\Payroll\PengaturanGajiController::class);
        
        // Acuan Gaji
        Route::prefix('acuan')->name('acuan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Payroll\AcuanGajiController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Payroll\AcuanGajiController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Payroll\AcuanGajiController::class, 'store'])->name('store');
            Route::get('/{acuanGaji}', [\App\Http\Controllers\Payroll\AcuanGajiController::class, 'show'])->name('show');
            Route::get('/{acuanGaji}/edit', [\App\Http\Controllers\Payroll\AcuanGajiController::class, 'edit'])->name('edit');
            Route::put('/{acuanGaji}', [\App\Http\Controllers\Payroll\AcuanGajiController::class, 'update'])->name('update');
            Route::delete('/{acuanGaji}', [\App\Http\Controllers\Payroll\AcuanGajiController::class, 'destroy'])->name('destroy');
            Route::post('/generate', [\App\Http\Controllers\Payroll\AcuanGajiController::class, 'generate'])->name('generate');
        });
        
        // Hitung Gaji
        Route::prefix('hitung')->name('hitung.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Payroll\HitungGajiController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Payroll\HitungGajiController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Payroll\HitungGajiController::class, 'store'])->name('store');
            Route::get('/{hitungGaji}', [\App\Http\Controllers\Payroll\HitungGajiController::class, 'show'])->name('show');
            Route::get('/{hitungGaji}/edit', [\App\Http\Controllers\Payroll\HitungGajiController::class, 'edit'])->name('edit');
            Route::put('/{hitungGaji}', [\App\Http\Controllers\Payroll\HitungGajiController::class, 'update'])->name('update');
            Route::delete('/{hitungGaji}', [\App\Http\Controllers\Payroll\HitungGajiController::class, 'destroy'])->name('destroy');
            Route::get('/{hitungGaji}/preview', [\App\Http\Controllers\Payroll\HitungGajiController::class, 'preview'])->name('preview');
            Route::post('/{hitungGaji}/approve', [\App\Http\Controllers\Payroll\HitungGajiController::class, 'approve'])->name('approve');
        });
        
        // Slip Gaji
        Route::prefix('slip')->name('slip.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Payroll\SlipGajiController::class, 'index'])->name('index');
            Route::get('/{slipGaji}', [\App\Http\Controllers\Payroll\SlipGajiController::class, 'show'])->name('show');
            Route::get('/{slipGaji}/print', [\App\Http\Controllers\Payroll\SlipGajiController::class, 'print'])->name('print');
            Route::post('/{slipGaji}/send', [\App\Http\Controllers\Payroll\SlipGajiController::class, 'send'])->name('send');
        });
        
        // NKI
        Route::resource('nki', \App\Http\Controllers\Payroll\NKIController::class);
        
        // Absensi
        Route::resource('absensi', \App\Http\Controllers\Payroll\AbsensiController::class);
        
        // Kasbon
        Route::prefix('kasbon')->name('kasbon.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Payroll\KasbonController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Payroll\KasbonController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Payroll\KasbonController::class, 'store'])->name('store');
            Route::get('/{kasbon}', [\App\Http\Controllers\Payroll\KasbonController::class, 'show'])->name('show');
            Route::get('/{kasbon}/edit', [\App\Http\Controllers\Payroll\KasbonController::class, 'edit'])->name('edit');
            Route::put('/{kasbon}', [\App\Http\Controllers\Payroll\KasbonController::class, 'update'])->name('update');
            Route::delete('/{kasbon}', [\App\Http\Controllers\Payroll\KasbonController::class, 'destroy'])->name('destroy');
            Route::post('/{kasbon}/approve', [\App\Http\Controllers\Payroll\KasbonController::class, 'approve'])->name('approve');
            Route::post('/{kasbon}/reject', [\App\Http\Controllers\Payroll\KasbonController::class, 'reject'])->name('reject');
            Route::post('/{kasbon}/cicilan', [\App\Http\Controllers\Payroll\KasbonController::class, 'addCicilan'])->name('cicilan.add');
        });
    });
    
    // Admin routes (SUPERADMIN ONLY)
    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        // CMS Dashboard
        Route::get('/cms', [\App\Http\Controllers\Admin\CMSController::class, 'index'])->name('cms.index');
        
        // Modules Management
        Route::resource('modules', \App\Http\Controllers\Admin\ModuleController::class);
        
        // Dynamic Fields Management
        Route::resource('fields', \App\Http\Controllers\Admin\DynamicFieldController::class);
        
        // Users Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/create', [UserController::class, 'create'])->name('create');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            
            // User Permissions Management
            Route::get('/{user}/permissions', [\App\Http\Controllers\Admin\UserPermissionController::class, 'edit'])->name('permissions.edit');
            Route::put('/{user}/permissions', [\App\Http\Controllers\Admin\UserPermissionController::class, 'update'])->name('permissions.update');
        });
        
        // Roles Management
        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::get('/create', [RoleController::class, 'create'])->name('create');
            Route::post('/', [RoleController::class, 'store'])->name('store');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
            Route::put('/{role}', [RoleController::class, 'update'])->name('update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
        });
        
        // System Settings - PASTIKAN INI
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::post('/{group}', [SettingController::class, 'update'])->name('update');
            Route::delete('/{group}/{id}', [SettingController::class, 'destroy'])->name('destroy');
        });
    });
});