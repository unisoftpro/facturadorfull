<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantMaintenanceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('maintenance_types', function (Blueprint $table) {
            $table->char('id', 2)->primary();
            $table->string('description')->index();
        });
        
        DB::table('maintenance_types')->insert([ 
            ['id' => '01', 'description' => 'PREVENTIVA'],
            ['id' => '02', 'description' => 'CORRECTIVA'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenance_types');        
    }
}
