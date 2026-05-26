<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('histories')) {
            return;
        }

        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->string('hismodel_type');
            $table->unsignedBigInteger('hismodel_id');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 100);
            $table->text('extra')->nullable();
            $table->timestamps();
        });

        Schema::table('histories', function (Blueprint $table) {
            $table->index(['hismodel_type', 'hismodel_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
