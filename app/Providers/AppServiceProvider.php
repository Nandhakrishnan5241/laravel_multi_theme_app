<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

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
        parent::boot();
        View::addNamespace('modules', base_path('app/admin/modules/views'));
        View::addNamespace('permissions', base_path('app/admin/permissions/views'));
        View::addNamespace('roles', base_path('app/admin/roles/views'));
        View::addNamespace('users', base_path('app/admin/users/views'));
        View::addNamespace('clients', base_path('app/admin/clients/views'));
        View::addNamespace('privileges', base_path('app/admin/privileges/views'));
        View::addNamespace('categories', base_path('app/admin/categories/views'));
        View::addNamespace('products', base_path('app/admin/products/views'));
        View::addNamespace('subcategory', base_path('app/admin/subcategory/views'));
        View::addNamespace('categorysubcategory', base_path('app/admin/categorysubcategory/views'));

     
        $this->loadModuleRoutes();
    }

    protected function loadModuleRoutes(): void
    {
        $moduleRoutes = base_path('app/admin/routes.php');

        // if (is_dir($modulePath)) {
        //     foreach (scandir($modulePath) as $file) {
        //         if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        //             Route::middleware('web') // Use 'api' middleware if needed
        //                 ->group($modulePath . '/' . $file);
        //         }
        //     }
        // }
        if (file_exists($moduleRoutes)) {
            Route::middleware('web') // Adjust to 'api' if it's an API route
            ->group(function () use ($moduleRoutes) {
                require $moduleRoutes;
            });
        }
    }
}
