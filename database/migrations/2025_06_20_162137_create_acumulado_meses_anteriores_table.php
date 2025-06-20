<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acumulado_meses_anteriores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tablero_id');
            $table->string('cliente');
            $table->decimal('total', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('tablero_id')->references('id')->on('tableros')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acumulado_meses_anteriores');
    }
};

