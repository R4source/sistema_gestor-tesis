<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('tesis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
        $table->string('titulo');
        $table->text('resumen')->nullable();
        $table->string('archivo')->nullable(); // ruta del PDF
        $table->enum('estado', ['enviado', 'en revisión', 'aprobado', 'rechazado'])->default('enviado');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tesis');
    }
};
