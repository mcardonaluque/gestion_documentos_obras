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

/**
 * Página de asignación de expedientes a usuarios (creación múltiple).
 *
 * Permite seleccionar varios expedientes para un usuario: valida que no
 * existan asignaciones duplicadas y crea un registro de
 * {@see ExpedienteUserAssignment} por expediente, resolviendo el team de
 * cada uno y registrando quién lo asignó.
 */
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
     * Valida que se haya seleccionado algún expediente y que ninguno esté
     * ya asignado al usuario; añade el usuario asignador.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $selectedExpedienteIds = collect((array) ($data['expediente_id'] ?? []))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (string) $value)
            ->unique()
            ->values()
            ->all();

        if ($selectedExpedienteIds === []) {
            throw ValidationException::withMessages([
                'expediente_id' => 'Debe seleccionar al menos un expediente.',
            ]);
        }

        $userId = (int) $data['user_id'];
        $existingAssignments = ExpedienteUserAssignment::query()
            ->whereIn('expediente_id', $selectedExpedienteIds)
            ->where('user_id', $userId)
            ->pluck('expediente_id')
            ->map(fn ($value) => (string) $value)
            ->all();

        if ($existingAssignments !== []) {
            throw ValidationException::withMessages([
                'expediente_id' => 'Algunos de los expedientes ya estaban asignados a este usuario: ' . implode(', ', $existingAssignments) . '.',
            ]);
        }

        $data['assigned_by'] = Auth::id();

        return $data;
    }

    /**
     * Crea una asignación por cada expediente seleccionado, resolviendo el
     * team del expediente, y devuelve la primera creada.
     */
    protected function handleRecordCreation(array $data): \App\Models\ExpedienteUserAssignment
    {
        $selectedExpedienteIds = collect((array) ($data['expediente_id'] ?? []))
            ->filter(fn ($value) => filled($value))
            ->map(fn ($value) => (string) $value)
            ->unique()
            ->values();

        $userId = (int) $data['user_id'];
        $createdAssignments = collect();

        foreach ($selectedExpedienteIds as $expedienteId) {
            $teamId = Expediente::query()
                ->where('expediente_id', $expedienteId)
                ->value('team_id');

            $createdAssignments->push(ExpedienteUserAssignment::create([
                'expediente_id' => $expedienteId,
                'user_id' => $userId,
                'assigned_by' => Auth::id(),
                'team_id' => $teamId,
            ]));
        }

        return $createdAssignments->first() ?? new ExpedienteUserAssignment();
    }
}
