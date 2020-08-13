<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddPurchaseOrderIncomeIdToInventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->unsignedInteger('purchase_order_income_id')->nullable()->after('lot_code');
            $table->foreign('purchase_order_income_id')->references('id')->on('purchase_order_income');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropForeign(['purchase_order_income_id']);
            $table->dropColumn(['purchase_order_income_id']);
        });
    }
}
