<?php

use Illuminate\Support\Facades\Route;
use Modules\BaseModule\Http\Controllers\BaseModuleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('basemodules', BaseModuleController::class)->names('basemodule');
});
