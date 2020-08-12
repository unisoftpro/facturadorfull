<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddFamilyLineToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedInteger('line_id')->nullable()->after('brand_id');
            $table->foreign('line_id')->references('id')->on('lines');

            $table->unsignedInteger('family_id')->nullable()->after('brand_id');
            $table->foreign('family_id')->references('id')->on('families');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['line_id']);
            $table->dropColumn('line_id');

            $table->dropForeign(['family_id']);
            $table->dropColumn('family_id');
        });
    }
}
