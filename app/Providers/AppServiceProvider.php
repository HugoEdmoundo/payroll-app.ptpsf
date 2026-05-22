<?php

namespace App\Providers;

use App\Models\Absensi;
use App\Models\AcuanGaji;
use App\Models\Kasbon;
use App\Models\NKI;
use App\Models\PengaturanGaji;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        // Register Observers for Data Cascade
        \App\Models\PengaturanGaji::observe(\App\Observers\PengaturanGajiObserver::class);
        \App\Models\AcuanGaji::observe(\App\Observers\AcuanGajiObserver::class);
        \App\Models\HitungGaji::observe(\App\Observers\HitungGajiObserver::class);
        \App\Models\NKI::observe(\App\Observers\NKIObserver::class);
        \App\Models\Absensi::observe(\App\Observers\AbsensiObserver::class);

        // Custom Blade Directives for Permissions
        \Blade::directive('canDo', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->canDo({$expression})): ?>";
        });

        \Blade::directive('endcanDo', function () {
            return '<?php endif; ?>';
        });

        \Blade::directive('hasPermission', function ($expression) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission({$expression})): ?>";
        });

        \Blade::directive('endhasPermission', function () {
            return '<?php endif; ?>';
        });

        // Route model binding for Payroll models
        Route::bind('pengaturanGaji', function ($value) {
            return PengaturanGaji::findOrFail($value);
        });

        Route::bind('nki', function ($value) {
            return NKI::findOrFail($value);
        });

        Route::bind('absensi', function ($value) {
            return Absensi::findOrFail($value);
        });

        Route::bind('kasbon', function ($value) {
            return Kasbon::findOrFail($value);
        });

        Route::bind('acuanGaji', function ($value) {
            return AcuanGaji::findOrFail($value);
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
