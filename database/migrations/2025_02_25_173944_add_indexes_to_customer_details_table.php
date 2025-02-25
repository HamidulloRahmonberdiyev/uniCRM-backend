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
        Schema::table('customer_details', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('region_id');
            $table->index('city_id');
            $table->index('district_id');
            $table->index('neighborhood_id');
            $table->index('home');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_details', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['region_id']);
            $table->dropIndex(['city_id']);
            $table->dropIndex(['district_id']);
            $table->dropIndex(['neighborhood_id']);
            $table->dropIndex(['home']);
        });
    }
};
