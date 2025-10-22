<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseLevel\Http\Controllers\CourseLevelController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('courselevels', CourseLevelController::class)->names('courselevel');
});
