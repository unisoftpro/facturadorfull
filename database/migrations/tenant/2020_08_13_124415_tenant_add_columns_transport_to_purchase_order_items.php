<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsTransportToPurchaseOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {

            $table->decimal('previous_cost', 16, 6)->default(0)->after('quantity'); 

            $table->string('previous_currency_type_id')->nullable()->after('quantity'); 
            $table->foreign('previous_currency_type_id')->references('id')->on('cat_currency_types');

            $table->string('observation')->nullable()->after('previous_currency_type_id'); 

            $table->decimal('attended_quantity',12,4)->default(0)->after('previous_currency_type_id');

            $table->decimal('quantity',12,4)->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {

            $table->dropColumn('previous_cost');

            $table->dropForeign(['previous_currency_type_id']);
            $table->dropColumn('previous_currency_type_id');

            $table->dropColumn('observation');
            $table->dropColumn('attended_quantity');

            $table->integer('quantity')->change();

        });
    }
}
