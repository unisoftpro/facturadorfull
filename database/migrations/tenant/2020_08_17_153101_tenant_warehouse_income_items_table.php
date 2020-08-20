<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantWarehouseIncomeItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('warehouse_income_items', function (Blueprint $table) {
             
            $table->increments('id');
            $table->unsignedInteger('warehouse_income_id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('category_id')->nullable();
            $table->unsignedInteger('family_id')->nullable();
            $table->json('item');
            $table->decimal('quantity', 12, 4);
            $table->decimal('list_price', 12, 2)->default(0);
            $table->decimal('discount_one', 12, 2)->default(0);
            $table->decimal('discount_two', 12, 2)->default(0);
            $table->decimal('discount_three', 12, 2)->default(0);
            $table->decimal('discount_four', 12, 2)->default(0);
            $table->decimal('unit_value', 16, 6);
            $table->decimal('unit_price', 16, 6);
            
            $table->decimal('price_fob_alm', 12, 2)->default(0);
            $table->decimal('price_fob_alm_igv', 12, 2)->default(0);
            $table->decimal('sale_profit_factor', 12, 2)->default(0);
            $table->decimal('last_purchase_price', 12, 2)->default(0);
            $table->decimal('warehouse_factor', 12, 2)->default(0);
            $table->decimal('retail_price', 12, 2)->default(0);
            $table->decimal('last_factor', 12, 2)->default(0);
            $table->decimal('num_price', 12, 2)->default(0);
            $table->string('letter_price')->nullable();

            $table->decimal('total_value', 12, 2);
            $table->decimal('total', 12, 2);

            $table->foreign('warehouse_income_id')->references('id')->on('warehouse_income')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('family_id')->references('id')->on('families');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_income_items');        
    }
}
