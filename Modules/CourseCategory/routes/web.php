<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseCategory\Http\Controllers\CourseCategoryController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('coursecategories', CourseCategoryController::class)->names('coursecategory');
});
