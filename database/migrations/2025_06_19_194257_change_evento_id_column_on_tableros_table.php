<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tableros', function (Blueprint $table) {
            $table->string('evento_id', 255)->change(); // Cambio de integer a string
        });
    }

    public function down(): void
    {
        Schema::table('tableros', function (Blueprint $table) {
            $table->unsignedBigInteger('evento_id')->change(); // Solo si antes era esto
        });
    }
};
