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
        Schema::table('pm_schedules', function (Blueprint $table) {
            $table->foreignId('teknisi_1')->nullable()->constrained('users');
            $table->foreignId('teknisi_2')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'pending', 'pending_pusat', 'approved', 'rejected'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pm_schedules', function (Blueprint $table) {
            $table->dropForeign(['teknisi_1']);
            $table->dropForeign(['teknisi_2']);
            $table->dropColumn(['teknisi_1', 'teknisi_2', 'notes']);
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft')->change();
        });
    }
};