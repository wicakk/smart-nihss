<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_sections', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('Kode unik seksi, e.g. 1a, 1b, 2');
            $table->string('name')->comment('Nama seksi NIHSS');
            $table->text('description')->nullable();
            $table->integer('order_number')->default(0)->comment('Urutan tampil');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_sections');
    }
};
