<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*Schema::table('consolidado', function (Blueprint $table) {
            $table->unsignedBigInteger('tablero_id')->nullable()->after('id');
            $table->foreign('tablero_id')->references('id')->on('tableros')->onDelete('cascade');
        });*/
    }

    public function down(): void
    {
        Schema::table('consolidado', function (Blueprint $table) {
            $table->dropForeign(['tablero_id']);
            $table->dropColumn('tablero_id');
        });
    }
};

