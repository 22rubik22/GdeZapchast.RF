<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('seller_info', function (Blueprint $table) {
            $table->json('branch_ids')->nullable();
        });
    }

    public function down()
    {
        Schema::table('seller_info', function (Blueprint $table) {
            $table->dropColumn('branch_ids');
        });
    }
};
