<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('sector_id');
            $table->unsignedInteger('beach_id');
            $table->unsignedInteger('unit_id');
            $table->string('tenant_name');
            $table->string('with_tenant_name');
            $table->string('from');
            $table->string('to');
            $table->float('rent_value');
            $table->string('car_type1');
            $table->string('car_serial1');
            $table->string('car_type2');
            $table->string('car_serial2');
            $table->string('car_type3');
            $table->string('car_serial3');
            $table->string('attachment');
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
        Schema::dropIfExists('contracts');
    }
}
