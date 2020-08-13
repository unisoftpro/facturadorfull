<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPurchaseOrderTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('purchase_order_types', function (Blueprint $table) {
            $table->char('id', 2)->primary();
            $table->string('description');
        });
        
        DB::table('purchase_order_types')->insert([ 
            ['id' => '01', 'description' => 'COMPRA LOCAL'],
            ['id' => '02', 'description' => 'COMPRA EXTERIOR'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_types');        
    }
}
