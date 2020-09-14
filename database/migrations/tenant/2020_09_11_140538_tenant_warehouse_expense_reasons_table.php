<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantWarehouseExpenseReasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('warehouse_expense_reasons', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('description');
        });

        DB::table('warehouse_expense_reasons')->insert([
            ['id' => '100', 'description' => 'MOTIVO 1'],
            ['id' => '101', 'description' => 'MOTIVO 2'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_expense_reasons');

    }
}
