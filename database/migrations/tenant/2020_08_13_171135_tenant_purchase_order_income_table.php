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
            $table->date('date_of_issue')->index();
            $table->integer('number')->unique();
            $table->string('invoice_description')->nullable();
            $table->unsignedInteger('warehouse_destination_id');
            $table->foreign('warehouse_destination_id')->references('id')->on('warehouses');
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
