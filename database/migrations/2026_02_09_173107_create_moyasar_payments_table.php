<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('moyasar_payments', function (Blueprint $table) {
            $table->string('moyasar_id')->primary(); // Invoice ID or Payment ID
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('model_type')->nullable(); // wallet, contract, invoice, later
            $table->string('model_id')->nullable();
            $table->integer('amount'); // in minor units
            $table->string('status')->default('0');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moyasar_payments');
    }
};