<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->nullable()->unique(); // CTR-0001
            $table->string('name', 100);

            // Counter global (nullable). Kalau nanti mau per poli, tinggal diwajibkan di form Filament.
            $table->foreignId('polyclinic_id')
                ->nullable()
                ->constrained('polyclinics')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['polyclinic_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counters');
    }
};
