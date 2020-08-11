<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->uuid('external_id');
            $table->char('soap_type_id', 2);
            $table->unsignedInteger('establishment_id');
            $table->json('establishment');
            $table->unsignedInteger('customer_id');
            $table->json('customer');
            $table->unsignedInteger('process_id');
            $table->date('opening_date');
            $table->char('work_order_state_id', 2);
            $table->unsignedInteger('final_item_warehouse_id');
            $table->unsignedInteger('process_warehouse_id');
            $table->unsignedInteger('origin_warehouse_id');
            $table->text('detail');
            $table->integer('control_number')->unique();
            $table->decimal('incoming_items', 12, 4);
            $table->decimal('result_items', 12, 4);
            $table->decimal('difference', 12, 4);
            $table->char('prefix', 2); 
            $table->integer('number')->unique();
            $table->string('lot_number');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('end_date')->nullable();
            $table->time('end_time')->nullable();
            $table->decimal('hours', 12, 2);
            $table->string('license_plate');
            $table->decimal('mileage', 16, 4);
            $table->unsignedInteger('activity_type_id');
            $table->string('filename')->nullable();

            $table->foreign('work_order_state_id')->references('id')->on('work_order_states');
            $table->foreign('process_id')->references('id')->on('processes');
            $table->foreign('customer_id')->references('id')->on('persons');
            $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('origin_warehouse_id')->references('id')->on('warehouses');
            $table->foreign('process_warehouse_id')->references('id')->on('warehouses');
            $table->foreign('final_item_warehouse_id')->references('id')->on('warehouses');
            $table->foreign('activity_type_id')->references('id')->on('activity_types');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('work_orders');        
    }

}
