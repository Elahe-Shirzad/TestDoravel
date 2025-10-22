<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseWorkflow\Http\Controllers\CourseWorkflowController;

Route::controller(CourseWorkflowController::class)
    ->group(function () {
        Route::get('/', 'index')
            ->name('index')
            ->title('گردش کار دوره ها')
            ->showInSidebar();

        Route::get('create', 'create')
            ->name('create')
            ->parentRoute('admin.system-settings.course-settings.course-workflows.index')
            ->title('افرودن گردش کار دوره ها');

        Route::post('store', 'store')
            ->name('store')
            ->title('coursestatus::general.store_new_course_status');

        Route::prefix('{course_workflow}')->group(function () {

            Route::get('/edit', 'edit')
                ->name('edit')
                ->parentRoute('admin.system-settings.course-settings.course-workflows.index')
                ->title('coursestatus::general.edit_course_status');

            Route::put('/update', 'update')
                ->name('update')
                ->title('coursestatus::general.update_course_status');

            Route::delete('/destroy', 'destroy')
                ->name('destroy')
                ->title('coursestatus::general.delete_course_status');
        });

    });
