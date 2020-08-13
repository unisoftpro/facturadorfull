<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsTransportToPurchaseOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            
            $table->char('purchase_order_state_id', 2)->nullable()->after('exchange_rate_sale');
            $table->foreign('purchase_order_state_id')->references('id')->on('purchase_order_states');

            $table->char('purchase_order_type_id', 2)->nullable()->after('purchase_order_state_id');
            $table->foreign('purchase_order_type_id')->references('id')->on('purchase_order_types');

            $table->unsignedInteger('line_id')->nullable()->after('purchase_order_type_id');
            $table->foreign('line_id')->references('id')->on('lines');

            $table->unsignedInteger('family_id')->nullable()->after('line_id');
            $table->foreign('family_id')->references('id')->on('families');

            $table->text('observation')->nullable()->after('family_id');
            $table->string('reference')->nullable()->after('family_id');
            $table->string('place_of_delivery')->nullable()->after('family_id');

            $table->unsignedInteger('work_order_id')->nullable()->after('exchange_rate_sale');
            $table->foreign('work_order_id')->references('id')->on('work_orders');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_orders', function (Blueprint $table) {

            $table->dropForeign(['purchase_order_state_id']);
            $table->dropColumn('purchase_order_state_id');

            $table->dropForeign(['purchase_order_type_id']);
            $table->dropColumn('purchase_order_type_id');

            $table->dropForeign(['line_id']);
            $table->dropColumn('line_id');

            $table->dropForeign(['family_id']);
            $table->dropColumn('family_id');

            $table->dropColumn('observation');
            $table->dropColumn('reference');
            $table->dropColumn('place_of_delivery');

            $table->dropForeign(['work_order_id']);
            $table->dropColumn('work_order_id');

        });
    }
}
