<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expediente_user_assignments', function (Blueprint $table): void {
            $table->id();
            $table->string('expediente_id', 60);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->noActionOnDelete()->noActionOnUpdate();
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete();
            $table->timestamps();

            $table->unique(['expediente_id', 'user_id'], 'exp_user_assignment_unique');
            $table->index(['user_id', 'expediente_id'], 'exp_user_assignment_lookup_idx');
            $table->index('team_id', 'exp_user_assignment_team_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expediente_user_assignments');
    }
};
