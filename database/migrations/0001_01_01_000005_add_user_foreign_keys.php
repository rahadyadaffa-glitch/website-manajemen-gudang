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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->foreign('role_id')->references('id')->on('roles')->restrictOnDelete();
            }

            if (Schema::hasColumn('users', 'minimarket_id')) {
                $table->foreign('minimarket_id')->references('id')->on('minimarkets')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropForeign(['role_id']);
            }

            if (Schema::hasColumn('users', 'minimarket_id')) {
                $table->dropForeign(['minimarket_id']);
            }
        });
    }
};
