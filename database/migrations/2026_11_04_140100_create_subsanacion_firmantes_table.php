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
        Schema::create('subsanacion_firmantes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('subsanacion_id')->constrained('subsanaciones')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->string('id_rol', 100);
            $table->string('tipo', 1);
            $table->unsignedSmallInteger('orden');
            $table->timestamps();

            $table->unique(['subsanacion_id', 'orden']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subsanacion_firmantes');
    }
};
