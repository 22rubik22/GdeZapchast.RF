<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('adverts')) {
            return;
        }

        // Проверяем через information_schema, есть ли индекс ix_adverts_brand_status
        $indexExists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'adverts')
            ->where('index_name', 'ix_adverts_brand_status')
            ->exists();

        if (
            ! $indexExists &&
            Schema::hasColumn('adverts', 'brand') &&
            Schema::hasColumn('adverts', 'status_ad')
        ) {
            Schema::table('adverts', function (Blueprint $table) {
                $table->index(['brand', 'status_ad'], 'ix_adverts_brand_status');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('adverts')) {
            return;
        }

        $indexExists = DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'adverts')
            ->where('index_name', 'ix_adverts_brand_status')
            ->exists();

        if ($indexExists) {
            Schema::table('adverts', function (Blueprint $table) {
                $table->dropIndex('ix_adverts_brand_status');
            });
        }
    }
};
