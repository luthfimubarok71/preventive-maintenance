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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->morphs('approvable');
            $table->foreignId('approver_id')->constrained('users');
            $table->enum('role', ['teknisi', 'kepala_ro', 'pusat']);
            $table->enum('status', ['approved', 'rejected', 'pending'])->default('pending');
            $table->text('comments')->nullable();
            $table->text('signature')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};