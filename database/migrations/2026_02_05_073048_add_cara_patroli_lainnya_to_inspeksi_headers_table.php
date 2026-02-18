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
        Schema::table('inspeksi_headers', function (Blueprint $table) {
            $table->string('cara_patroli_lainnya')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspeksi_headers', function (Blueprint $table) {
            $table->dropColumn('cara_patroli_lainnya');
        });
    }
};