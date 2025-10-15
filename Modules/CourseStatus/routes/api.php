<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseStatus\Http\Controllers\CourseStatusController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('coursestatuses', CourseStatusController::class)->names('coursestatus');
});
