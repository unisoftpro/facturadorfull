<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDocumentTypeOfWarehouseIncome extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_income', function(Blueprint $table) {
            $table->string('cat_document_types_id')->nullable()->after('observation');
            $table->string('document_reference')->nullable()->after('cat_document_types_id');
            $table->foreign('cat_document_types_id')->references('id')->on('cat_document_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('cat_document_types');
            $table->dropColumn('document_reference');
            $table->dropForeign(['cat_document_types_id']);
            //
        });
    }
}
