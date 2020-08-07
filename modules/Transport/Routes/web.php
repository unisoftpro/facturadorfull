<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function() {

            Route::prefix('transport')->group(function () { 
                
                Route::prefix('vehicle-brands')->group(function () {
                    Route::get('', 'VehicleBrandController@index')->name('tenant.transport.vehicle-brands.index');
                    Route::get('columns', 'VehicleBrandController@columns');
                    Route::post('', 'VehicleBrandController@store');
                    Route::get('records', 'VehicleBrandController@records');
                    Route::get('record/{record}', 'VehicleBrandController@record');
                    Route::delete('{record}', 'VehicleBrandController@destroy');
                });

                
                Route::prefix('vehicle-types')->group(function () {
                    Route::get('', 'VehicleTypeController@index')->name('tenant.transport.vehicle-types.index');
                    Route::get('columns', 'VehicleTypeController@columns');
                    Route::post('', 'VehicleTypeController@store');
                    Route::get('records', 'VehicleTypeController@records');
                    Route::get('record/{record}', 'VehicleTypeController@record');
                    Route::delete('{record}', 'VehicleTypeController@destroy');
                });


                Route::prefix('colors')->group(function () {
                    Route::get('', 'ColorController@index')->name('tenant.transport.colors.index');
                    Route::get('columns', 'ColorController@columns');
                    Route::post('', 'ColorController@store');
                    Route::get('records', 'ColorController@records');
                    Route::get('record/{record}', 'ColorController@record');
                    Route::delete('{record}', 'ColorController@destroy');
                });

                Route::prefix('insurance')->group(function () {
                    Route::get('', 'InsuranceController@index')->name('tenant.transport.insurance.index');
                    Route::get('columns', 'InsuranceController@columns');
                    Route::post('', 'InsuranceController@store');
                    Route::get('records', 'InsuranceController@records');
                    Route::get('record/{record}', 'InsuranceController@record');
                    Route::delete('{record}', 'InsuranceController@destroy');
                });

                Route::prefix('fuel-types')->group(function () {
                    Route::get('', 'FuelTypeController@index')->name('tenant.transport.fuel-types.index');
                    Route::get('columns', 'FuelTypeController@columns');
                    Route::post('', 'FuelTypeController@store');
                    Route::get('records', 'FuelTypeController@records');
                    Route::get('record/{record}', 'FuelTypeController@record');
                    Route::delete('{record}', 'FuelTypeController@destroy');
                });
                
                Route::prefix('fuels')->group(function () {
                    Route::get('', 'FuelController@index')->name('tenant.transport.fuels.index');
                    Route::get('columns', 'FuelController@columns');
                    Route::get('tables', 'FuelController@tables');
                    Route::post('', 'FuelController@store');
                    Route::get('records', 'FuelController@records');
                    Route::get('record/{record}', 'FuelController@record');
                    Route::delete('{record}', 'FuelController@destroy');
                });

            });
 
        });
    });
}
