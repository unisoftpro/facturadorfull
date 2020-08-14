<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPurchaseOrderIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_order_income', function (Blueprint $table) {

            $table->increments('id');
            $table->char('soap_type_id', 2);
            $table->date('date_of_issue')->index();
            $table->integer('number')->unique();
            $table->string('invoice_description')->nullable();
            $table->unsignedInteger('warehouse_id');
            $table->unsignedInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');
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
        Schema::dropIfExists('purchase_order_income');
    }

}
