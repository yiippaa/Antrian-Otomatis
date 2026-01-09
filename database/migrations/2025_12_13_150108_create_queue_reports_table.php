<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('queue_reports', function (Blueprint $table) {
            $table->id();

            $table->date('report_date');

            $table->foreignId('polyclinic_id')
                ->constrained('polyclinics')
                ->cascadeOnDelete();

            // jumlah antrian
            $table->unsignedInteger('total_queue')->default(0);
            $table->unsignedInteger('total_done')->default(0);
            $table->unsignedInteger('total_cancelled')->default(0);
            $table->unsignedInteger('total_no_show')->default(0);

            // BPJS vs UMUM
            $table->unsignedInteger('total_bpjs')->default(0);
            $table->unsignedInteger('total_umum')->default(0);

            // rata-rata (dalam detik)
            $table->unsignedInteger('avg_waiting_time')->nullable();  // called_at - created_at
            $table->unsignedInteger('avg_service_time')->nullable();  // finished_at - started_at

            $table->timestamps();

            $table->unique(
                ['report_date', 'polyclinic_id'],
                'queue_reports_unique_per_day_poli'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('queue_reports');
    }
};
