<?php

namespace App\Providers;

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
    }
}
