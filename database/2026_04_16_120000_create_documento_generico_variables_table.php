<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documento_generico_variables', function (Blueprint $table): void {
            $table->id();
            $table->integer('documento_generico_id');
            $table->string('variable', 120);
            $table->string('source_type', 30)->default('auto');
            $table->string('source_path')->nullable();
            $table->text('default_value')->nullable();
            $table->string('format', 50)->nullable();
            $table->boolean('is_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_detected_at')->nullable();
            $table->timestamps();

            $table->foreign('documento_generico_id')
                ->references('id')
                ->on('documento_genericos')
                ->cascadeOnDelete();

            $table->unique(['documento_generico_id', 'variable'], 'doc_generico_variable_unique');
            $table->index(['documento_generico_id', 'is_active'], 'doc_generico_variable_active_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documento_generico_variables');
    }
};
