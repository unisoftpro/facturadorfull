<?php

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth'])->group(function () {


            Route::prefix('imports')->group(function () { 

                Route::get('documents', 'ImportController@index')->name('tenant.imports.documents.index');
                
                Route::get('columns', 'ImportController@columns');
                Route::get('records', 'ImportController@records');
                Route::post('documents', 'ImportController@import');

            });

        });
    });
}
