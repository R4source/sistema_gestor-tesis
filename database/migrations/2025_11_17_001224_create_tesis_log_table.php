<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('tesis_log', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tesis_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('accion');           // campo cambiado: título, estado, archivo, etc.
        $table->json('old_values')->nullable();
        $table->json('new_values')->nullable();
        $table->timestamps();
    });
}
};
