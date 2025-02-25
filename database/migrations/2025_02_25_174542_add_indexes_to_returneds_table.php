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
        Schema::table('returneds', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('user_id');
            $table->index('quantity');
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('returneds', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['quantity']);
            $table->dropIndex(['date']);
        });
    }
};
