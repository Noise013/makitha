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
        Schema::create('movimientos', function (Blueprint $table) {
        $table->id();
        $table->date('fecha')->nullable();
        $table->text('descripcion')->nullable();
        $table->text('feat_business')->nullable();
        $table->text('big_brothers')->nullable();
        $table->text('g_and_a')->nullable();
        $table->text('corporativo')->nullable();
        $table->decimal('importe', 15, 2)->nullable();
        $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
