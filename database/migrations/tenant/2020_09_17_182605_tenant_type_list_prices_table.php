<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantTypeListPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('type_list_prices', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('description');
        });

        DB::table('type_list_prices')->insert([
            ['id' => '01', 'description' => 'PRECIO PUBLICO'],
            ['id' => '02', 'description' => 'PRECIO POR MAYOR'],
            ['id' => '03', 'description' => 'IMPORTACION'],
            ['id' => '04', 'description' => 'CLIENTES ESPECIALES'],
            ['id' => '10', 'description' => 'PRECIO ESPECIAL PARA EL GRUPO'],

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('type_list_prices');
    }
}
