<?php

use Illuminate\Support\Facades\Route;
use Modules\Bank\Http\Controllers\BankController;
use Modules\Bank\Http\Controllers\BankLocationController;
use Modules\Bank\Models\Bank;

Route::prefix('banks')->as('banks.')->controller(BankController::class)->group(function () {

    Route::get('/', 'index')
        ->name('index')
        ->title('لیست بانک ها')
        ->showInSidebar()
        ->badge(
            value: function () {
                return Bank::withoutTrashed()->count();
            },
            style: 'dark'
        );

    Route::get('create', 'create')
        ->name('create')
        ->title('درج بانک')
        ->parentRoute('admin.base-information.banks.index');

    Route::post('store', 'store')->name('store');

    Route::prefix('{bank}')->group(function () {

        Route::get('show', 'show')
            ->name('show')
            ->title('جزئیات بانک')
            ->parentRoute('admin.base-information.banks.index');

        Route::get('edit', 'edit')
            ->name('edit')
            ->title('ویرایش بانک')
            ->parentRoute('admin.base-information.banks.index');

        Route::put('update', 'update')->name('update');

        Route::delete('destroy', 'destroy')->name('destroy');

    });

});

Route::controller(BankLocationController::class)
    ->prefix('banks')
    ->as('banks.')
    ->group(function () {
        Route::prefix('{bank}')->group(function () {

            Route::get('location', 'location')
                ->name('location')
                ->title('شعبات بانک');
////
            Route::get('locations/edit', 'edit')
                ->name('locations.edit')
                ->title('درج شعبه');

            Route::put('locations/update', 'update')->name('locations.update');


            Route::delete('locations/{location}/destroy', 'destroy')->name('locations.destroy');


        });

    });




