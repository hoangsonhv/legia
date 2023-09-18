<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Blade::directive('permission', function ($permission) {
            return "<?php if (\App\Helpers\PermissionHelper::hasPermission({$permission})) : ?>";
        });
        Blade::directive('endpermission', function() {
            return "<?php endif; ?>";
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
