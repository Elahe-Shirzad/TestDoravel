<?php

use Illuminate\Support\Facades\Route;
use Modules\Instructor\Http\Controllers\InstructorController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('instructors', InstructorController::class)->names('instructor');
});
