<?php

use Illuminate\Support\Facades\Route;
use Modules\CourseWorkflow\Http\Controllers\CourseWorkflowController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('courseworkflows', CourseWorkflowController::class)->names('courseworkflow');
});
