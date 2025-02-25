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
        Schema::table('customers', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('company_id');
            $table->index('first_name');
            $table->index('last_name');
            $table->index('middle_name');
            $table->index('date_of_birth');
            $table->index('phone');
            $table->index('phone2');
            $table->index('status');
            $table->index('type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['company_id']);
            $table->dropIndex(['first_name']);
            $table->dropIndex(['last_name']);
            $table->dropIndex(['middle_name']);
            $table->dropIndex(['date_of_birth']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['phone2']);
            $table->dropIndex(['status']);
            $table->dropIndex(['type_id']);
        });
    }
};
