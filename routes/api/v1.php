<?php

use Illuminate\Support\Facades\Route;
use Modules\BaseModule\Http\Controllers\Api\DateTimeController;
use Modules\BaseModule\Http\Controllers\Api\InteractionController;
use Modules\BaseModule\Http\Controllers\Api\StatusController;


Route::prefix('statuses')
    ->name('statuses.')
    ->controller(StatusController::class)
    ->group(function () {
        Route::post('/set-statuses', 'setStatuesForSelectBox')
            ->name('set-statuses-for-select-box')
            ->title('دریافت وضعیت های قابل انتخاب');

        Route::post('/check-exist-active-status', 'checkIfActiveStatusExist')
            ->name('check_exist_active_status')
            ->title('بررسی وجود وضعیت فعال');

        Route::post('/check-exist-is-default', 'checkIfIsDefaultStatusExist')
            ->name('check_exist_is_default_status')
            ->title('بررسی وجود وضعیت پیش فرض');
    });

Route::prefix('interaction')
    ->name('interaction.')
    ->controller(InteractionController::class)
    ->group(function () {
        Route::post('change-status', 'changeStatus')
            ->name('change-status');

        Route::post('show', 'show')
            ->name('show');

        Route::post('delete', 'destroy')
            ->name('delete');
    });

Route::prefix('date-time')
    ->name('date-time.')
    ->controller(DateTimeController::class)
    ->group(function () {
        Route::post('/regenerate', 'regenerateUpdatedAt')
            ->name('regenerate');
    });
