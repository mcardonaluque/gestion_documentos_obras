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
        Schema::table('alertas', function (Blueprint $table) {
            //
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alertas', function (Blueprint $table) {
            //
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
        });
    }
};
