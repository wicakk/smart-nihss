<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('form_questions')->onDelete('cascade');
            $table->string('option_text');
            $table->integer('score')->default(0)->comment('Nilai skor untuk pilihan ini');
            $table->text('description')->nullable()->comment('Deskripsi tambahan opsi');
            $table->integer('order_number')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_options');
    }
};
