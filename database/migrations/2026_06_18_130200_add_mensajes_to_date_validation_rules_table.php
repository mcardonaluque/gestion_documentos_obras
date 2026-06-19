<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        Schema::table('date_validation_rules', function (Blueprint $table): void {
            $table->text('mensaje_preventivo')->nullable()->after('mensaje');
            $table->text('mensaje_cumplida')->nullable()->after('mensaje_preventivo');
            $table->text('mensaje_incumplida')->nullable()->after('mensaje_cumplida');
        });
    }

    public function down(): void
    {
        Schema::table('date_validation_rules', function (Blueprint $table): void {
            $table->dropColumn(['mensaje_preventivo', 'mensaje_cumplida', 'mensaje_incumplida']);
        });
    }
};
