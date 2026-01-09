<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();

            $table->date('queue_date');

            $table->foreignId('polyclinic_id')
                ->constrained('polyclinics')
                ->restrictOnDelete();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->restrictOnDelete();

            // snapshot (biar histori aman walau pasien diubah)
            $table->enum('patient_type', ['BPJS', 'UMUM']);

            // nomor urut per (tanggal + poli + tipe)
            $table->unsignedInteger('number'); // 1,2,3,...

            // yang ditampilin: B-001 / U-001
            $table->string('display_code', 10);

            $table->enum('status', ['waiting', 'called', 'serving', 'done', 'cancelled', 'no_show'])
                ->default('waiting');

            $table->foreignId('counter_id')
                ->nullable()
                ->constrained('counters')
                ->nullOnDelete();

            $table->timestamp('called_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

            // cegah nomor dobel dalam 1 hari-poli-tipe
            $table->unique(['queue_date', 'polyclinic_id', 'patient_type', 'number'], 'queues_unique_number_per_scope');

            // percepat query next queue & laporan
            $table->index(['queue_date', 'polyclinic_id', 'patient_type', 'status'], 'queues_scope_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
