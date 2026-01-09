<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->nullable()->unique(); // PSN-0001
            $table->string('name', 150);
            $table->string('phone', 30)->nullable();

            $table->enum('patient_type', ['BPJS', 'UMUM']);
            $table->string('bpjs_number', 30)->nullable();

            $table->timestamps();

            $table->index(['patient_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
