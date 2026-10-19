<?php

declare(strict_types=1);

namespace Tests\Unit\Filament\Obras\Resources\Documentoexpedientes;

use App\Filament\Obras\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class DocumentoexpedienteResourceTest extends TestCase
{
    #[Test]
    public function it_filters_documents_by_the_expediente_in_the_request(): void
    {
        request()->query->set('expediente_id', 'EXP-001');

        $query = DocumentoexpedienteResource::getEloquentQuery()->getQuery();

        self::assertContains('EXP-001', $query->getBindings());
        self::assertTrue(collect($query->wheres)->contains(
            fn (array $where): bool => ($where['column'] ?? null) === 'expediente_id'
                && ($where['value'] ?? null) === 'EXP-001',
        ));
    }
}
