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
    Schema::create('revisiones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tesis_id')->constrained('tesis')->onDelete('cascade');
        $table->foreignId('profesor_id')->constrained('users')->onDelete('cascade');
        $table->text('comentario');
        $table->enum('estado', ['pendiente', 'corregido', 'aprobado'])->default('pendiente');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revisiones');
    }
};
