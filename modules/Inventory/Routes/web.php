<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->group(function() {
            // Config inventory

            Route::prefix('warehouses')->group(function () {
                Route::get('/', 'WarehouseController@index')->name('warehouses.index');
                Route::get('records', 'WarehouseController@records');
                Route::get('columns', 'WarehouseController@columns');
                Route::get('tables', 'WarehouseController@tables');
                Route::get('record/{warehouse}', 'WarehouseController@record');
                Route::post('/', 'WarehouseController@store');
                Route::get('initialize', 'WarehouseController@initialize');
            });

            Route::prefix('inventory')->group(function () {
                Route::get('/', 'InventoryController@index')->name('inventory.index');
                Route::get('records', 'InventoryController@records');
                Route::get('columns', 'InventoryController@columns');
                Route::get('tables', 'InventoryController@tables');
                Route::get('tables/transaction/{type}', 'InventoryController@tables_transaction');
                Route::get('record/{inventory}', 'InventoryController@record');
                Route::post('/', 'InventoryController@store');
                Route::post('/transaction', 'InventoryController@store_transaction');
                Route::post('move', 'InventoryController@move');

                Route::get('moves', 'MovesController@index')->name('inventory.moves.index');

                Route::post('remove', 'InventoryController@remove');
                Route::get('initialize', 'InventoryController@initialize');
            });

            Route::prefix('reports')->group(function () {
                Route::get('inventory', 'ReportInventoryController@index')->name('reports.inventory.index');
                Route::post('inventory/search', 'ReportInventoryController@search')->name('reports.inventory.search');
                Route::post('inventory/pdf', 'ReportInventoryController@pdf')->name('reports.inventory.pdf');
                Route::post('inventory/excel', 'ReportInventoryController@excel')->name('reports.inventory.report_excel');

                // Route::get('kardex', 'ReportKardexController@index')->name('reports.kardex.index');
                // Route::get('kardex/search', 'ReportKardexController@search')->name('reports.kardex.search');
                // Route::post('kardex/pdf', 'ReportKardexController@pdf')->name('reports.kardex.pdf');
                // Route::post('kardex/excel', 'ReportKardexController@excel')->name('reports.kardex.report_excel');



                Route::get('kardex', 'ReportKardexController@index')->name('reports.kardex.index');
                Route::get('kardex/pdf', 'ReportKardexController@pdf')->name('reports.kardex.pdf');
                Route::get('kardex/excel', 'ReportKardexController@excel')->name('reports.kardex.excel');
                Route::get('kardex/filter', 'ReportKardexController@filter')->name('reports.kardex.filter');
                Route::get('kardex_lots/filter', 'ReportKardexController@filter')->name('reports.kardex.filter');
                Route::get('kardex_series/filter', 'ReportKardexController@filter')->name('reports.kardex.filter');

                Route::get('kardex/records', 'ReportKardexController@records')->name('reports.kardex.records');
                Route::get('kardex/lots/filter', 'ReportKardexController@records_lots');

                Route::get('kardex_lots/records', 'ReportKardexController@records_lots_kardex')->name('reports.kardex_lots.records');
                Route::get('kardex_lots/pdf', 'ReportKardexLotsController@pdf');
                Route::get('kardex_lots/excel', 'ReportKardexLotsController@excel');


                Route::get('kardex_series/records', 'ReportKardexController@records_series_kardex')->name('reports.kardex_series.records');
                Route::get('kardex_series/pdf', 'ReportKardexSeriesController@pdf');
                Route::get('kardex_series/excel', 'ReportKardexSeriesController@excel');


                Route::get('valued-kardex', 'ReportValuedKardexController@index')->name('reports.valued_kardex.index');
                Route::get('valued-kardex/excel', 'ReportValuedKardexController@excel');
                Route::get('valued-kardex/filter', 'ReportValuedKardexController@filter');
                Route::get('valued-kardex/records', 'ReportValuedKardexController@records');

                Route::get('precie-list','ReportPriceListController@index')->name('reports.precie-list.index');
                Route::get('precie-list/tables', 'ReportPriceListController@filters');
                Route::get('precie-list/records', 'ReportPriceListController@records');
                Route::get('precie-list/pdf', 'ReportPriceListController@pdf');

                Route::get('valuted-balances','ReportValutedBalancesController@index')->name('reports.valuted-balances.index');
                Route::get('valuted-balances/tables', 'ReportValutedBalancesController@filters');
                Route::get('valuted-balances/records', 'ReportValutedBalancesController@records');

            });


            Route::prefix('inventories')->group(function () {

                Route::get('configuration', 'InventoryConfigurationController@index')->name('tenant.inventories.configuration.index');
                Route::get('configuration/record', 'InventoryConfigurationController@record');
                Route::post('configuration', 'InventoryConfigurationController@store');
            });

            Route::prefix('moves')->group(function () {

                Route::get('/', 'MovesController@index')->name('moves.index');
                Route::get('records', 'MovesController@records');
                Route::get('columns', 'MovesController@columns');

            });


            Route::prefix('transfers')->group(function () {
                Route::get('/', 'TransferController@index')->name('transfers.index');
                Route::get('records', 'TransferController@records');
                Route::get('columns', 'TransferController@columns');
                Route::get('tables', 'TransferController@tables');
                Route::get('record/{inventory}', 'TransferController@record');
                Route::post('/', 'TransferController@store');

                Route::delete('{inventory}', 'TransferController@destroy');

                Route::get('create', 'TransferController@create')->name('transfer.create');

                Route::get('stock/{item_id}/{warehouse_id}', 'TransferController@stock');

                Route::get('items/{warehouse_id}', 'TransferController@items');


            });


            Route::prefix('purchase-order-income')->group(function () {

                Route::get('/', 'PurchaseOrderIncomeController@index')->name('tenant.purchase-order-income.index');
                Route::get('records', 'PurchaseOrderIncomeController@records');
                Route::get('columns', 'PurchaseOrderIncomeController@columns');
                Route::get('tables', 'PurchaseOrderIncomeController@tables');
                Route::get('record/{inventory}', 'PurchaseOrderIncomeController@record');
                Route::post('/', 'PurchaseOrderIncomeController@store');
                Route::get('create', 'PurchaseOrderIncomeController@create')->name('tenant.purchase-order-income.create');
                // Route::get('stock/{item_id}/{warehouse_id}', 'PurchaseOrderIncomeController@stock');
                // Route::get('items/{warehouse_id}', 'PurchaseOrderIncomeController@items');

            });


            Route::prefix('warehouse-income')->group(function () {

                Route::get('/', 'WarehouseIncomeController@index')->name('tenant.warehouse-income.index');
                Route::get('records', 'WarehouseIncomeController@records');
                Route::get('columns', 'WarehouseIncomeController@columns');
                Route::get('item/tables', 'WarehouseIncomeController@item_tables');
                Route::get('tables', 'WarehouseIncomeController@tables');
                Route::get('record/{record}', 'WarehouseIncomeController@record');
                Route::post('/', 'WarehouseIncomeController@store');
                Route::get('create', 'WarehouseIncomeController@create')->name('tenant.warehouse-income.create');
                Route::get('item/list-price/{item_id}/{purchase_order_id}', 'WarehouseIncomeController@getListPrice');
                Route::get('exchange-rate/{date_reference}/{supplier_id}', 'WarehouseIncomeController@getExchangeRate');
                Route::get('item/additional-values/{item_id}', 'WarehouseIncomeController@getAdditionalValues');
                Route::get('download/{external_id}/{template}', 'WarehouseIncomeController@download');


            });

            Route::prefix('warehouse-expense')->group(function () {

                Route::get('/', 'WarehouseExpenseController@index')->name('tenant.warehouse-expense.index');
                Route::get('records', 'WarehouseExpenseController@records');
                Route::get('columns', 'WarehouseExpenseController@columns');
                Route::get('item/tables', 'WarehouseExpenseController@item_tables');
                Route::get('tables', 'WarehouseExpenseController@tables');
                Route::get('record/{record}', 'WarehouseExpenseController@record');
                Route::post('/', 'WarehouseExpenseController@store');
                Route::get('create', 'WarehouseExpenseController@create')->name('tenant.warehouse-income.create');
                Route::get('item/list-price/{item_id}/{purchase_order_id}', 'WarehouseExpenseController@getListPrice');
                Route::get('exchange-rate/{date_reference}/{supplier_id}', 'WarehouseExpenseController@getExchangeRate');
                Route::get('item/additional-values/{item_id}', 'WarehouseExpenseController@getAdditionalValues');
                Route::get('download/{id}', 'WarehouseExpenseController@download');


            });


            Route::prefix('price-list')->group(function () {

                Route::post('/', 'PriceListController@store');
            });


        });
    });
}
