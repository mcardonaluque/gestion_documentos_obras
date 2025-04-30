<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection='Obras';
    public function up(): void
    {
        Schema::table('datosInicioDeObras', function (Blueprint $table) {
            //
            $table->string('Expediente', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datosInicioDeObras', function (Blueprint $table) {
            //
            
        });
    }
};
