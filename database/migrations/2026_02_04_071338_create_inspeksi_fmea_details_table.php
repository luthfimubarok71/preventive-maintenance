<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inspeksi_fmea_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspeksi_id')
                  ->constrained('inspeksi_headers')
                  ->onDelete('cascade');

            $table->string('item', 100);
            $table->tinyInteger('severity')->nullable();
            $table->tinyInteger('occurrence')->nullable();
            $table->tinyInteger('detection')->nullable();

            $table->integer('rpn')->nullable();
            $table->decimal('risk_index', 5, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspeksi_fmea_details');
    }
};