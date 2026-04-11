<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            // FIX FK USER (AMAN & STABIL)
            $table->unsignedBigInteger('assessor_id')->nullable();

            $table->foreign('assessor_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->integer('total_score')->default(0);

            $table->enum('severity', [
                'normal',
                'mild',
                'moderate',
                'severe',
                'very_severe'
            ])->default('normal');

            $table->dateTime('assessed_at');

            $table->text('clinical_notes')->nullable();

            $table->boolean('is_complete')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'assessed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};