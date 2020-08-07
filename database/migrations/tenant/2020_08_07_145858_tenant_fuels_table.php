<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantFuelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->index();
            $table->unsignedInteger('fuel_type_id');
            $table->foreign('fuel_type_id')->references('id')->on('fuel_types');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fuels');        
    }
}
