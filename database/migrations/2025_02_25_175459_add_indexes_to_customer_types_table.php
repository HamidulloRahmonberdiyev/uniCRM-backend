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
        Schema::table('customer_types', function (Blueprint $table) {
            $table->index('label');
            $table->index('number');
            $table->index('color');
            $table->index('sortable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_types', function (Blueprint $table) {
            $table->dropIndex(['label']);
            $table->dropIndex(['number']);
            $table->dropIndex(['color']);
            $table->dropIndex(['sortable']);
        });
    }
};
