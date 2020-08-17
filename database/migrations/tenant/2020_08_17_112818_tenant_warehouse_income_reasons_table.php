<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantWarehouseIncomeReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('warehouse_income_reasons', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('description');
        });
        
        DB::table('warehouse_income_reasons')->insert([ 
            ['id' => '100', 'description' => 'MOVIMIENTO DE INGRESOS'],
            ['id' => '101', 'description' => 'AJUSTE DE INGRESOS'], 
            ['id' => '102', 'description' => 'AJUSTE DE CAMBIO CÓDIGO'], 
            ['id' => '103', 'description' => 'COMPRAS DE ARTICULOS EXTERIOR'], 
            ['id' => '104', 'description' => 'COMPRAS DE ARTICULOS LOCAL'], 
            ['id' => '105', 'description' => 'DEVOLUCIÓN DE MERCADERIA'], 
            ['id' => '106', 'description' => '(P) RECEPCIÓN CONSIGNACIÓN'], 
            ['id' => '107', 'description' => '(P) VTA.MER.REC.EN CONSIGN'], 
            ['id' => '108', 'description' => 'TOMA DE INVENTARIOS'], 
            ['id' => '109', 'description' => '(P) DEV.VTA.MER.REC.CONSIG'], 
            ['id' => '110', 'description' => '(C) DEV.DE.ENTR.EN.CONSIGN'], 
            ['id' => '111', 'description' => 'REGULARIZA CONSIGNACIÓN'], 
            ['id' => '112', 'description' => '(C) ENTREGA EN CONSIGNACIÓN'], 
            ['id' => '113', 'description' => 'INGRESO TRANSFORMACIÓN'], 
            ['id' => '114', 'description' => 'DEVOLUCIÓN'], 
            ['id' => '115', 'description' => 'TRANSFERENCIA'], 
            ['id' => '116', 'description' => 'COMPRAS GRUPO RENZO'], 
            ['id' => '117', 'description' => 'SALDOS INICIALES'], 
            ['id' => '199', 'description' => 'EXTORNO DE SALIDAS'], 
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_income_reasons');        
    }
}
