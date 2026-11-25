<?php

declare(strict_types=1);

namespace Tests\Unit\Services\DateRules;

use App\Services\DateRules\DateFieldDetector;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class DateFieldDetectorTest extends TestCase
{
    #[Test]
    public function it_detects_fields_with_a_date_cast_whatever_their_name(): void
    {
        $model = $this->makeModel([
            'FecProrroga' => '2026-01-10 00:00:00',
            'FecDecreto' => '2026-01-11',
            'firmado_el' => '2026-01-12',
        ]);

        self::assertSame(
            ['FecProrroga', 'FecDecreto', 'firmado_el'],
            (new DateFieldDetector())->dirtyDateFields($model),
        );
    }

    #[Test]
    public function it_keeps_detecting_legacy_fields_without_cast_that_contain_fecha(): void
    {
        $model = $this->makeModel([
            'Fecha_Inicio_Acta_Replanteo' => '2026-01-10',
            'fecha_convenio' => '2026-01-11',
        ]);

        self::assertSame(
            ['Fecha_Inicio_Acta_Replanteo', 'fecha_convenio'],
            (new DateFieldDetector())->dirtyDateFields($model),
        );
    }

    #[Test]
    public function it_ignores_timestamps_non_date_fields_and_uncast_at_suffixes(): void
    {
        $model = $this->makeModel([
            'created_at' => '2026-01-10 10:00:00',
            'updated_at' => '2026-01-10 10:00:00',
            'nombre_obra' => 'Obra de prueba',
            'importe' => 100,
            'format_at' => 'texto',
        ]);

        self::assertSame([], (new DateFieldDetector())->dirtyDateFields($model));
    }

    /**
     * @param array<string, mixed> $attributes
     */
    private function makeModel(array $attributes): Model
    {
        $model = new class extends Model
        {
            protected $table = 'modelo_de_prueba';

            /**
             * @return array<string, string>
             */
            protected function casts(): array
            {
                return [
                    'FecProrroga' => 'datetime',
                    'FecDecreto' => 'date:Y-m-d',
                    'firmado_el' => 'immutable_date',
                    'importe' => 'decimal:2',
                ];
            }
        };

        return $model->forceFill($attributes);
    }
}
