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
        Schema::create('date_rule_executions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('date_validation_rule_id')->constrained('date_validation_rules')->cascadeOnDelete();
            $table->string('model_type', 180);
            $table->string('model_id', 120);
            $table->string('expediente_id', 120)->nullable();
            $table->string('source_table', 120);
            $table->string('source_field', 120);
            $table->text('evaluated_value')->nullable();
            $table->text('compared_value')->nullable();
            $table->boolean('passed');
            $table->boolean('triggered');
            $table->string('severity', 20);
            $table->string('action', 30);
            $table->text('message');
            $table->string('fingerprint', 64);
            $table->timestamp('evaluated_at');
            $table->timestamps();

            $table->unique('fingerprint', 'uniq_date_rule_execution_fingerprint');
            $table->index(['source_table', 'source_field'], 'idx_date_rule_execution_field');
            $table->index(['model_type', 'model_id'], 'idx_date_rule_execution_model');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('date_rule_executions');
    }
};
