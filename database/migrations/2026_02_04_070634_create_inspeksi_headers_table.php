<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inspeksi_headers', function (Blueprint $table) {
            $table->id();

            $table->string('segment_inspeksi', 150)->nullable();
            $table->enum('jalur_fo', ['backbone', 'non_backbone'])->nullable();

            $table->string('nama_pelaksana', 100)->nullable();
            $table->string('driver', 100)->nullable();
            $table->enum('cara_patroli', ['mobil','motor','jalan_kaki','lainnya'])->nullable();
            $table->date('tanggal_inspeksi')->nullable();

            $table->enum('priority', ['RENDAH','SEDANG','KRITIS'])->nullable();
            $table->string('schedule_pm', 100)->nullable();

            $table->string('prepared_by', 100)->nullable();
            $table->string('approved_by', 100)->nullable();
            $table->text('prepared_signature')->nullable();
            $table->text('approved_signature')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspeksi_headers');
    }
};