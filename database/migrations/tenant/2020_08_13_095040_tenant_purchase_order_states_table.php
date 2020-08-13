<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPurchaseOrderStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('purchase_order_states', function (Blueprint $table) {
            $table->char('id', 2)->primary();
            $table->string('description');
        });
        
        DB::table('purchase_order_states')->insert([ 
            ['id' => '01', 'description' => 'COTIZACIÓN'],
            ['id' => '02', 'description' => 'ANÁLISIS'],
            ['id' => '03', 'description' => 'ORDEN DE COMPRA CONFIRMADA'],
            ['id' => '04', 'description' => 'ORDEN COMPRA DOCUMENTACIÓN BANCOS'],
            ['id' => '05', 'description' => 'ORDEN COMPRA EMBARCADA'],
            ['id' => '06', 'description' => 'ORDEN COMPRA FORMULACIÓN'],
            ['id' => '07', 'description' => 'ORDEN COMPRA EN DEPÓSITO ADUANA'],
            ['id' => '08', 'description' => 'ORDEN COMPRA INGRESADA'],
            ['id' => '09', 'description' => 'REQUERIMIENTO DE PRODUCTOS'],
            ['id' => '10', 'description' => 'ORDEN DE COMPRA EXTERIOR'],
            ['id' => '11', 'description' => 'ORDEN DE SERVICIO'],
            ['id' => '12', 'description' => 'ORDEN DE SERV. ADMINISTRATIVOS'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_order_states');        
    }
}
