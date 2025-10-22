<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseLevel\Http\Controllers\CourseLevelController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('courselevels', CourseLevelController::class)->names('courselevel');
});
