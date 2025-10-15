<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseStatus\Http\Controllers\CourseStatusController;

Route::controller(CourseStatusController::class)
    ->group(function () {
        Route::get('/', 'index')
            ->name('index')
            ->title('انواع وضعیت دوره ها')
            ->showInSidebar();

        Route::get('create', 'create')
            ->name('create')
            ->parentRoute('admin.system-settings.course-settings.course-statuses.index')
            ->title('coursestatus::general.add_new_course_status');

        Route::post('store', 'store')
            ->name('store')
            ->title('coursestatus::general.store_new_course_status');

        Route::prefix('{status}')->group(function () {

            Route::get('/edit', 'edit')
                ->name('edit')
                ->parentRoute('admin.system-settings.course-settings.course-statuses.index')
                ->title('coursestatus::general.edit_course_status');

            Route::put('/update', 'update')
                ->name('update')
                ->title('coursestatus::general.update_course_status');

            Route::delete('/destroy', 'destroy')
                ->name('destroy')
                ->title('coursestatus::general.delete_course_status');
        });

    });
