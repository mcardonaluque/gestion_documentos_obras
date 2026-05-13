<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ExpedienteUserAssignments\Pages;

use App\Filament\Obras\Resources\ExpedienteUserAssignments\ExpedienteUserAssignmentResource;
use App\Models\Expediente;
use App\Models\ExpedienteUserAssignment;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;
use Illuminate\Validation\ValidationException;

class EditExpedienteUserAssignment extends EditRecord
{
    protected static string $resource = ExpedienteUserAssignmentResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    protected ?string $heading = 'ASIGNACIÓN DE EXPEDIENTES';

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $expedienteId = (string) $data['expediente_id'];
        $userId = (int) $data['user_id'];

        $alreadyAssigned = ExpedienteUserAssignment::query()
            ->where('expediente_id', $expedienteId)
            ->where('user_id', $userId)
            ->whereKeyNot($this->record->getKey())
            ->exists();

        if ($alreadyAssigned) {
            throw ValidationException::withMessages([
                'user_id' => 'Este usuario ya tiene asignado ese expediente.',
            ]);
        }

        $data['team_id'] = Expediente::query()
            ->where('expediente_id', $expedienteId)
            ->value('team_id');

        return $data;
    }
}
