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
        Schema::create('seller_info', function (Blueprint $table) {
            $table->id();                                   // авто-инкрементный PK
            $table->foreignId('user_id')
                ->constrained()                          // FOREIGN KEY → users(id)
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->text('about_shop')->nullable();
            $table->text('warranty_return_policy')->nullable();
            $table->boolean('delivery_in_city')->default(false);
            $table->integer('delivery_in_city_cost')->nullable();
            $table->boolean('delivery_to_transport_company')->default(false);
            $table->boolean('delivery_to_route_taxi')->default(false);
            $table->integer('delivery_to_route_taxi_cost')->nullable();
            $table->boolean('delivery_russian_post')->default(false);
            $table->integer('russian_post_additional_cost')->nullable();
            $table->text('additional_delivery_conditions')->nullable();
            $table->timestamps();                           // created_at & updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seller_info');
    }

};
