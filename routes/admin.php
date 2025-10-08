<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->as('admin.')->group(function (){

    Route::get('/', function () {
        return redirect()->route('admin.dashboard.index');
    });


    Route::middleware('authorized')->group(function (){

        Route::get('dashboard',[DashboardController::class,'index'])
            ->title('داشبورد')
            ->showInSidebar()
            ->name('dashboard.index');
    });


});
