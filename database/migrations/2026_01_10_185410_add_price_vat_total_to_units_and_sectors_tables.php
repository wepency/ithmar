<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceVatTotalToUnitsAndSectorsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0)->after('user_id');
            $table->decimal('vat', 10, 2)->default(0)->after('price');
            $table->decimal('total', 10, 2)->default(0)->after('vat');
        });

        Schema::table('units', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0)->after('status');
            $table->decimal('vat', 10, 2)->default(0)->after('price');
            $table->decimal('total', 10, 2)->default(0)->after('vat');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->dropColumn(['price', 'vat', 'total']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn(['price', 'vat', 'total']);
        });
    }
}