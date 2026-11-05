<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Subsanaciones\Pages;

use App\Filament\Obras\Resources\Subsanaciones\SubsanacionResource;
use App\Services\Subsanaciones\SubsanacionSignatureService;
use Filament\Resources\Pages\CreateRecord;

/** Alta de una solicitud de subsanación de documento. */
class CreateSubsanacion extends CreateRecord
{
    protected static string $resource = SubsanacionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['documento_id'] ??= request()->query('documento_id');
        $data['expediente_id'] ??= request()->query('expediente_id');

        return $data;
    }

    protected function afterCreate(): void
    {
        app(SubsanacionSignatureService::class)->requestSignature($this->record);
    }
}
