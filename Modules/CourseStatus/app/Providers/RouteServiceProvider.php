<?php

namespace Modules\CourseStatus\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'CourseStatus';

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     */

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->prefix('admin/system-settings/course-settings/course-statuses')
            ->as('admin.system-settings.course-settings.course-statuses.')
            ->group(module_path($this->name, '/routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     */
    protected function mapApiRoutes(): void
    {
//        Route::middleware('web')
//            ->prefix('admin/api/v1/banks')
//            ->as('admin.api.v1.banks.')
//            ->group(module_path($this->name, '/routes/api/v1.php'));
    }




}
