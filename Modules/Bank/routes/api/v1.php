<?php

use Modules\Bank\Http\Controllers\Api\DateTimeController;

Route::prefix('date-time')
    ->name('date-time.')
    ->controller(DateTimeController::class)
    ->group(function () {
        Route::post('/regenerate', 'regenerateUpdatedAt')
            ->name('regenerate');
    });
