<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExcludedFromBondsToContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('contracts', 'excluded_from_bonds')) {
            return;
        }

        Schema::table('contracts', function (Blueprint $table) {
            $table->tinyInteger('excluded_from_bonds')->nullable()->after('is_cancelled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('contracts', 'excluded_from_bonds')) {
            return;
        }

        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('excluded_from_bonds');
        });
    }
}
