<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('medical_record_number')->unique()->comment('Nomor Rekam Medis');
            $table->string('name');
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('diagnosis')->nullable()->comment('Diagnosa utama');
            $table->date('admission_date')->nullable()->comment('Tanggal masuk');
            $table->enum('status', ['active', 'discharged', 'deceased'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['name', 'medical_record_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
