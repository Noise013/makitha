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
        Schema::create('proyecciones', function (Blueprint $table) {
        $table->id(); // idProyeccion
        $table->string('mes');
        $table->decimal('proyeccion', 15, 2); 
        $table->string('evento_id'); // idEvento
        $table->timestamps();

        $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyeccions');
    }
};
