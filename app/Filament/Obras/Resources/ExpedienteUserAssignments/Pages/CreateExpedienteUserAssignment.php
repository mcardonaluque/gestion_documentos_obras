<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ExpedienteUserAssignments\Pages;

use App\Filament\Obras\Resources\ExpedienteUserAssignments\ExpedienteUserAssignmentResource;
use App\Models\Expediente;
use App\Models\ExpedienteUserAssignment;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CreateExpedienteUserAssignment extends CreateRecord
{
    protected static string $resource = ExpedienteUserAssignmentResource::class;

    protected Width | string | null $maxContentWidth = Width::Full;

    protected ?string $heading = 'ASIGNACIÓN DE EXPEDIENTES';

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Asignar');
    }

    protected function getCreateAnotherFormAction(): Action
    {
        return parent::getCreateAnotherFormAction()
            ->label('Asignar y asignar otro');
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $expedienteId = (string) $data['expediente_id'];
        $userId = (int) $data['user_id'];

        $alreadyAssigned = ExpedienteUserAssignment::query()
            ->where('expediente_id', $expedienteId)
            ->where('user_id', $userId)
            ->exists();

        if ($alreadyAssigned) {
            throw ValidationException::withMessages([
                'user_id' => 'Este usuario ya tiene asignado ese expediente.',
            ]);
        }

        $data['assigned_by'] = Auth::id();
        $data['team_id'] = Expediente::query()
            ->where('expediente_id', $expedienteId)
            ->value('team_id');

        return $data;
    }
}
