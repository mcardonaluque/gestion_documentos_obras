<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Templates;

use App\Models\DocumentoGenericoVariable;
use App\Services\Templates\TemplateVariableValueResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TemplateVariableValueResolverTest extends TestCase
{
    public function test_it_resolves_known_aliases_from_the_record(): void
    {
        $record = new class extends Model
        {
            protected $guarded = [];

            public $timestamps = false;
        };

        $plan = new class extends Model
        {
            protected $guarded = [];

            public $timestamps = false;
        };

        $plan->forceFill([
            'denominacion_plan' => 'Plan Provincial 2026',
        ]);

        $record->setRelation('planes', $plan);

        $variable = new DocumentoGenericoVariable([
            'variable' => 'WD_DEFIPLAN',
            'source_type' => 'auto',
        ]);

        $resolver = new TemplateVariableValueResolver();

        self::assertSame('Plan Provincial 2026', $resolver->resolve($variable, $record));
    }

    public function test_it_resolves_system_date_values(): void
    {
        Carbon::setTestNow('2026-04-16 10:15:00');

        $record = new class extends Model
        {
            protected $guarded = [];

            public $timestamps = false;
        };

        $variable = new DocumentoGenericoVariable([
            'variable' => 'WD_FECHA_HOY',
            'source_type' => 'system',
            'source_path' => 'today',
        ]);

        $resolver = new TemplateVariableValueResolver();

        self::assertSame('16/04/2026', $resolver->resolve($variable, $record));

        Carbon::setTestNow();
    }
}
