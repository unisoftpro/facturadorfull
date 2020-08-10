<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantWorkOrderStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('work_order_states', function (Blueprint $table) {
            $table->char('id', 2)->primary();
            $table->string('description');
        });
        
        DB::table('work_order_states')->insert([ 
            ['id' => '01', 'description' => 'ACTIVA'],
            ['id' => '02', 'description' => 'EN PROCESO'],
            ['id' => '03', 'description' => 'TERMINADA'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_order_states');        
    }
}
