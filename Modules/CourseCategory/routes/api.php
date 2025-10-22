<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseCategory\Http\Controllers\CourseCategoryController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('coursecategories', CourseCategoryController::class)->names('coursecategory');
});
