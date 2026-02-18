<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspeksis', function (Blueprint $table) {

            $table->id();
            $table->string('segment_inspeksi');
            $table->string('jenis_jalur');
            $table->string('nama_pelaksana');
            $table->date('tanggal_inspeksi');
            $table->string('prepared_by');
            $table->string('approved_by')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspeksis');
    }
};