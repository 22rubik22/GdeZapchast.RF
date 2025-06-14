<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seller_info', function (Blueprint $table) {
            // уже существующие колонки пропускаем
            $table->integer('delivery_to_transport_company_cost')
                ->nullable();

            $table->string('banner_url')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('seller_info', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_to_transport_company_cost',
                'banner_url',
            ]);
        });
    }
};
