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
    Schema::table('movimientos', function (Blueprint $table) {
        $table->string('evento')->nullable()->after('importe');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('movimientos', function (Blueprint $table) {
        $table->dropColumn('evento');
    });
}

};
