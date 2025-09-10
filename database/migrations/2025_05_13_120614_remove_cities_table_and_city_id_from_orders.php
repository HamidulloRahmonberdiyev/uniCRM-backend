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
            if (Schema::hasColumn('orders', 'city_id')) {
                $table->dropIndex(['city_id']);
                $table->dropColumn('city_id');
            }
        });

        Schema::table('customer_details', function (Blueprint $table) {
            if (Schema::hasColumn('customer_details', 'city_id')) {
                $table->dropIndex(['city_id']);
                $table->dropColumn('city_id');
            }
        });

        Schema::table('neighborhoods', function (Blueprint $table) {
            if (Schema::hasColumn('neighborhoods', 'city_id')) {
                $table->dropIndex(['city_id']);
                $table->dropColumn('city_id');
            }
        });

        Schema::dropIfExists('cities');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
