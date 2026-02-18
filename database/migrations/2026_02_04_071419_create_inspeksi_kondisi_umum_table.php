<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inspeksi_kondisi_umum', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspeksi_id')
                  ->constrained('inspeksi_headers')
                  ->onDelete('cascade');

            $table->enum('marker_post', ['baik','rusak'])->nullable();
            $table->enum('hand_hole', ['baik','rusak'])->nullable();
            $table->enum('aksesoris_ku', ['baik','rusak'])->nullable();
            $table->enum('jc_odp', ['baik','rusak'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspeksi_kondisi_umum');
    }
};