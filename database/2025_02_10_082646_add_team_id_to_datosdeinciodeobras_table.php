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
        Schema::table('datosiniciodeobras', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('team_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('datosiniciodeobras', function (Blueprint $table) {
            //
        });
    }
};
