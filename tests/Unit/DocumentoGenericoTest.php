<?php

namespace Tests\Unit;

use App\Models\DocumentoGenerico;
use PHPUnit\Framework\TestCase;

class DocumentoGenericoTest extends TestCase
{
    public function test_uses_autoincrementing_primary_key_for_filament_routes(): void
    {
        $model = new DocumentoGenerico();

        $this->assertTrue($model->getIncrementing());
        $this->assertSame('int', $model->getKeyType());
    }
}
