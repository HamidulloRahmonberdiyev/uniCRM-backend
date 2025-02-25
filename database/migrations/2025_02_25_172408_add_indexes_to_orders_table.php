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
        Schema::table('orders', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('user_id');
            $table->index('company_id');
            $table->index('city_id');
            $table->index('district_id');
            $table->index('neighborhood_id');
            $table->index('quantity');
            $table->index('sum');
            $table->index('date');
            $table->index('address');
            $table->index('note');
            $table->index('status');
            $table->index('source_id');
            $table->index('latitude');
            $table->index('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['city_id']);
            $table->dropIndex(['district_id']);
            $table->dropIndex(['neighborhood_id']);
            $table->dropIndex(['quantity']);
            $table->dropIndex(['sum']);
            $table->dropIndex(['date']);
            $table->dropIndex(['address']);
            $table->dropIndex(['note']);
            $table->dropIndex(['status']);
            $table->dropIndex(['source_id']);
            $table->dropIndex(['latitude']);
            $table->dropIndex(['longitude']);
        });
    }
};
