<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        Schema::create('expediente_tramitador_user', function (Blueprint $table) {
            $table->id();
            $table->string('expediente_id', 50);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

            $table->unique(['expediente_id', 'user_id'], 'expediente_tramitador_unique');
            $table->index(['user_id', 'is_active'], 'expediente_tramitador_user_active_idx');
            $table->index(['expediente_id', 'is_active'], 'expediente_tramitador_exp_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expediente_tramitador_user');
    }
};
