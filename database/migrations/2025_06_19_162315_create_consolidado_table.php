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
        Schema::create('consolidado', function (Blueprint $table) {
            $table->id();
            $table->date('fecha')->nullable();
            $table->text('descripcion')->nullable();
            $table->text('feat_business')->nullable();
            $table->text('cargar_a')->nullable();
            $table->decimal('importe', 15, 2)->nullable();
            $table->unsignedBigInteger('tablero_id')->nullable();
            $table->string('evento_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consolidado');
    }
};
