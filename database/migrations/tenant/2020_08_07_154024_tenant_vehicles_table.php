<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {

            $table->increments('id');

            $table->string('license_plate')->index();
            $table->string('old_license_plate')->index()->nullable();
            $table->string('registration_starting');
            $table->string('title');
            $table->date('date');
            $table->string('condition')->nullable();
            $table->string('owner_type');
            $table->string('denomination')->nullable();
            $table->string('lastname');
            $table->string('mother_lastname');
            $table->string('names');
            $table->date('acquisition_date');
            $table->date('expedition_date');
            $table->string('temporary_validity')->nullable();
            $table->string('address');

            $table->string('category');
            $table->unsignedInteger('vehicle_brand_id');
            $table->unsignedInteger('color_id');
            $table->string('motor');
            $table->unsignedInteger('fuel_id');
            $table->string('rolling_form');
            $table->string('vin');
            $table->string('serie');
            $table->string('manufacturing_year');
            $table->string('model_year');
            $table->string('version');

            $table->integer('axes');
            $table->integer('seating');
            $table->integer('passengers');
            $table->integer('wheel');
            
            $table->string('bodywork');
            $table->string('power');
            $table->integer('cylinders');

            $table->decimal('displacement', 12, 3);
            $table->decimal('gross_weight', 12, 3);
            $table->decimal('net_weight', 12, 3);
            $table->decimal('useful_load', 12, 3);
            $table->decimal('length', 12, 3);
            $table->decimal('height', 12, 3);
            $table->decimal('width', 12, 3);
            $table->unsignedInteger('vehicle_type_id');
            $table->unsignedInteger('insurance_id');

            $table->foreign('insurance_id')->references('id')->on('insurance');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_types');
            $table->foreign('vehicle_brand_id')->references('id')->on('vehicle_brands');
            $table->foreign('color_id')->references('id')->on('colors');
            $table->foreign('fuel_id')->references('id')->on('fuels');

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
        Schema::dropIfExists('vehicles');        
    }
}
