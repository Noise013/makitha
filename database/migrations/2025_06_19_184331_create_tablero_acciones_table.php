<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tablero_acciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tablero_id')->constrained('tableros')->onDelete('cascade');
            $table->string('cliente');
            $table->string('servicio')->nullable();
            $table->string('propuesta')->nullable();
            $table->decimal('monto', 15, 2)->nullable();
            $table->date('fecha')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tablero_acciones');
    }

};
