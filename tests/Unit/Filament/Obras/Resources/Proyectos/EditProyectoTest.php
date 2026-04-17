<?php

declare(strict_types=1);

namespace Tests\Unit\Filament\Obras\Resources\Proyectos;

use App\Filament\Obras\Resources\Proyectos\Pages\EditProyecto;
use PHPUnit\Framework\Attributes\Test;
use ReflectionMethod;
use Tests\TestCase;

final class EditProyectoTest extends TestCase
{
    #[Test]
    public function it_preserves_existing_form_data_when_hydrating_the_edit_form(): void
    {
        $page = app(EditProyecto::class);

        $method = new ReflectionMethod(EditProyecto::class, 'mutateFormDataBeforeFill');
        $method->setAccessible(true);

        $data = [
            'expediente_id' => 'EXP-001',
            'AO_PROYECTO' => 2026,
            'autor' => 'Autor de prueba',
            'den_proyecto' => 'Proyecto de prueba',
            'importe_proyecto' => 12345.67,
        ];

        $result = $method->invoke($page, $data);

        self::assertSame($data, $result);
    }
}
