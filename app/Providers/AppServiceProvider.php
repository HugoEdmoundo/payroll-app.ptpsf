<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\PengaturanGaji;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom Blade Directives for Permissions
        \Blade::directive('canDo', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->canDo({$expression})): ?>";
        });
        
        \Blade::directive('endcanDo', function () {
            return "<?php endif; ?>";
        });
        
        \Blade::directive('hasPermission', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$expression})): ?>";
        });
        
        \Blade::directive('endhasPermission', function () {
            return "<?php endif; ?>";
        });
        
        // Route model binding for Payroll models
        Route::bind('pengaturanGaji', function ($value) {
            return PengaturanGaji::where('id_pengaturan', $value)->firstOrFail();
        });

        Route::bind('nki', function ($value) {
            return \App\Models\NKI::where('id_nki', $value)->firstOrFail();
        });

        Route::bind('absensi', function ($value) {
            return \App\Models\Absensi::where('id_absensi', $value)->firstOrFail();
        });

        Route::bind('kasbon', function ($value) {
            return \App\Models\Kasbon::where('id_kasbon', $value)->firstOrFail();
        });

        Route::bind('acuanGaji', function ($value) {
            return \App\Models\AcuanGaji::where('id_acuan', $value)->firstOrFail();
        });

        // Share data with sidebar
        view()->composer('partials.sidebar', function ($view) {
            $view->with([
                'currentRoute' => Route::currentRouteName(),
                'role' => auth()->check() && auth()->user()->role ? auth()->user()->role->name : 'No Role',
                'jenisKaryawan' => \App\Models\SystemSetting::getOptions('jenis_karyawan'),
            ]);
        });
    }
}
