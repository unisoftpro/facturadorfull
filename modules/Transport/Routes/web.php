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

                Route::prefix('vehicles')->group(function () {
                    Route::get('', 'VehicleController@index')->name('tenant.transport.vehicles.index');
                    Route::get('columns', 'VehicleController@columns');
                    Route::get('tables', 'VehicleController@tables');
                    Route::post('', 'VehicleController@store');
                    Route::get('records', 'VehicleController@records');
                    Route::get('record/{record}', 'VehicleController@record');
                    Route::get('search/customers', 'VehicleController@searchCustomers');
                    Route::get('search/customer/{id}', 'VehicleController@searchCustomerById');
                    Route::delete('{record}', 'VehicleController@destroy');
                });

                Route::prefix('mechanics')->group(function () {
                    Route::get('', 'MechanicController@index')->name('tenant.transport.mechanics.index');
                    Route::get('columns', 'MechanicController@columns');
                    Route::get('tables', 'MechanicController@tables');
                    Route::post('', 'MechanicController@store');
                    Route::get('records', 'MechanicController@records');
                    Route::get('record/{record}', 'MechanicController@record');
                    Route::delete('{record}', 'MechanicController@destroy');
                });

                Route::prefix('service-types')->group(function () {
                    Route::get('', 'ServiceTypeController@index')->name('tenant.transport.service-types.index');
                    Route::get('columns', 'ServiceTypeController@columns');
                    Route::post('', 'ServiceTypeController@store');
                    Route::get('records', 'ServiceTypeController@records');
                    Route::get('record/{record}', 'ServiceTypeController@record');
                    Route::delete('{record}', 'ServiceTypeController@destroy');
                });

                Route::prefix('activity-types')->group(function () {
                    Route::get('', 'ActivityTypeController@index')->name('tenant.transport.activity-types.index');
                    Route::get('columns', 'ActivityTypeController@columns');
                    Route::post('', 'ActivityTypeController@store');
                    Route::get('records', 'ActivityTypeController@records');
                    Route::get('record/{record}', 'ActivityTypeController@record');
                    Route::delete('{record}', 'ActivityTypeController@destroy');
                });

                Route::prefix('processes')->group(function () {
                    Route::get('', 'ProcessController@index')->name('tenant.transport.processes.index');
                    Route::get('columns', 'ProcessController@columns');
                    Route::post('', 'ProcessController@store');
                    Route::get('records', 'ProcessController@records');
                    Route::get('record/{record}', 'ProcessController@record');
                    Route::delete('{record}', 'ProcessController@destroy');
                });

                Route::prefix('work-orders')->group(function () {

                    Route::get('/', 'WorkOrderController@index')->name('tenant.transport.work-orders.index');
                    Route::get('columns', 'WorkOrderController@columns');
                    Route::get('records', 'WorkOrderController@records');
                    Route::get('create/{id?}', 'WorkOrderController@create')->name('tenant.transport.work-orders.create');
                    Route::get('tables', 'WorkOrderController@tables');
                    Route::get('table/{table}', 'WorkOrderController@table');
                    Route::post('/', 'WorkOrderController@store');
                    Route::get('record/{id}', 'WorkOrderController@record');
                    Route::get('search/customers', 'WorkOrderController@searchCustomers');
                    Route::get('search/customer/{id}', 'WorkOrderController@searchCustomerById');
                    Route::get('download/{external_id}/{format?}', 'WorkOrderController@download');
    
                });

            });
 
        });
    });
}
