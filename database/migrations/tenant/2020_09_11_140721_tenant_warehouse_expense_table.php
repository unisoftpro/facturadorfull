<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantWarehouseExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_expense', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->char('soap_type_id', 2);
            $table->uuid('external_id');
            $table->unsignedInteger('warehouse_expense_id');
            $table->unsignedInteger('warehouse_destination_id');
            $table->string('warehouse_expense_reason_id');
            $table->date('date_of_issue')->index();
            $table->unsignedInteger('supplier_id');
            $table->json('supplier');
            $table->string('currency_type_id');
            $table->string('observation')->nullable();
            $table->integer('number')->unique();
            $table->date('reference_date');
            $table->decimal('exchange_rate_sale', 13, 3)->default(0);

            $table->unsignedInteger('work_order_id')->nullable();

            $table->decimal('original_total', 12, 2);
            $table->decimal('national_total', 12, 2);
            $table->decimal('total_value', 12, 2);
            $table->decimal('total', 12, 2);
            $table->string('filename')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');

            $table->foreign('warehouse_expense_id')->references('id')->on('warehouses');
            $table->foreign('warehouse_destination_id')->references('id')->on('warehouses');

            $table->foreign('warehouse_expense_reason_id')->references('id')->on('warehouse_expense_reasons');
            $table->foreign('supplier_id')->references('id')->on('persons');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
            $table->foreign('work_order_id')->references('id')->on('work_orders');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_expense');
    }
}
