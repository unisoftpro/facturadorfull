<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantWarehouseIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('warehouse_income', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->char('soap_type_id', 2);
            $table->uuid('external_id');
            $table->unsignedInteger('warehouse_id');
            $table->string('warehouse_income_reason_id');
            $table->date('date_of_issue')->index();
            $table->unsignedInteger('supplier_id');
            $table->json('supplier');
            $table->string('currency_type_id'); 
            $table->string('observation')->nullable();
            $table->integer('number')->unique();
            $table->date('reference_date');

            $table->unsignedInteger('purchase_order_id')->nullable();
            $table->unsignedInteger('work_order_id')->nullable();
 
            $table->decimal('original_total', 12, 2);
            $table->decimal('national_total', 12, 2);
            $table->string('filename')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('warehouse_income_reason_id')->references('id')->on('warehouse_income_reasons');
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
        Schema::dropIfExists('warehouse_income');        
    }
}
