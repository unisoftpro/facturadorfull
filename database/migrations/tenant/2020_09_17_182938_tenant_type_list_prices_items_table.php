<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantTypeListPricesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_price_item', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->string('currency_type_id');
            $table->string('list_type_id');

            $table->decimal('price_fob', 12, 2);
            $table->decimal('factor', 12, 2);
            $table->decimal('price_list', 12, 2);
            $table->decimal('discount_one', 12, 2);
            $table->decimal('discount_two', 12, 2);
            $table->decimal('discount_three', 12, 2);

            $table->timestamps();


            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
            $table->foreign('list_type_id')->references('id')->on('type_list_prices');
            $table->foreign('item_id')->references('id')->on('items');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('list_price_item');
    }
}
