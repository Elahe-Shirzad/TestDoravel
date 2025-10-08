<?php

use Illuminate\Support\Facades\Route;
use Modules\Bank\Models\Location;
use Modules\Location\Http\Controllers\LocationController;

Route::prefix('locations')->as('locations.')->controller(LocationController::class)->group(function () {

    Route::get('/', 'index')
        ->name('index')
        ->title('لیست  شعبات بانک ها')
        ->showInSidebar()
        ->badge(
            value: function () {
                return Location::withoutTrashed()->count();
            },
            style: 'dark'
        );

    Route::get('create', 'create')
        ->name('create')
        ->title('درج شعبه')
        ->parentRoute('admin.base-information.locations.index');

    Route::post('store', 'store')->name('store');

    Route::prefix('{bank}')->group(function () {

        Route::get('show', 'show')
            ->name('show')
            ->title('جزئیات شعبه')
            ->parentRoute('admin.base-information.locations.index');

        Route::get('edit', 'edit')
            ->name('edit')
            ->title('ویرایش شعبه')
            ->parentRoute('admin.base-information.locations.index');

        Route::put('update', 'update')->name('update');

        Route::delete('destroy', 'destroy')->name('destroy');

    });

});
