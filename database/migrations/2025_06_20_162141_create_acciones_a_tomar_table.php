<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acciones_a_tomar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tablero_id');
            $table->string('cliente');
            $table->string('servicio')->nullable();
            $table->string('propuesta')->nullable();
            $table->decimal('monto', 15, 2)->nullable();
            $table->date('fecha')->nullable();
            $table->timestamps();

            $table->foreign('tablero_id')->references('id')->on('tableros')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acciones_a_tomar');
    }
};
