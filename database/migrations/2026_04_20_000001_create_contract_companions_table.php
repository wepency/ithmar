<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateContractCompanionsTable extends Migration
{
    public function up()
    {
        Schema::create('contract_companions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->string('title', 191);
            $table->string('name', 191);
            $table->string('id_number', 10);
            $table->string('nationality', 191);
            $table->string('barcode_image')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('contracts')
            ->select('id', 'with_tenant_title', 'with_tenant_name', 'with_tenant_name_code', 'with_tenant_nationality', 'attachment_2')
            ->whereNotNull('with_tenant_name')
            ->where('with_tenant_name', '!=', '')
            ->orderBy('id')
            ->chunk(500, function ($contracts) {
                $rows = [];
                $now = now();

                foreach ($contracts as $contract) {
                    $rows[] = [
                        'contract_id'   => $contract->id,
                        'title'         => $contract->with_tenant_title ?: 'others',
                        'name'          => $contract->with_tenant_name,
                        'id_number'     => $contract->with_tenant_name_code ?: '0000000000',
                        'nationality'   => $contract->with_tenant_nationality ?: '',
                        'barcode_image' => $contract->attachment_2,
                        'sort_order'    => 0,
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ];
                }

                if (!empty($rows)) {
                    DB::table('contract_companions')->insert($rows);
                }
            });
    }

    public function down()
    {
        Schema::dropIfExists('contract_companions');
    }
}
