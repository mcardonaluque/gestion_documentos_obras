<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\Subsanaciones\Pages;

use App\Filament\Obras\Resources\Subsanaciones\SubsanacionResource;
use App\Filament\Resources\Users\UserResource;
use App\Services\Subsanaciones\SubsanacionSignatureService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;

/** Alta de una solicitud de subsanación de documento. */
class CreateSubsanacion extends CreateRecord
{
    protected static string $resource = SubsanacionResource::class;

    /**
     * La creación y el envío a NIMEXP se completan o revierten juntos.
     */
    public function hasDatabaseTransactions(): bool
    {
        return true;
    }

    /**
     * Comprueba antes de persistir que el solicitante dispone de su ID de Tramitador.
     */
    protected function beforeCreate(): void
    {
        $usuario = Auth::user();

        if (filled(trim((string) $usuario?->id_usuario))) {
            return;
        }

        Notification::make()
            ->warning()
            ->persistent()
            ->title('No se puede solicitar la firma')
            ->body('El usuario que realiza la solicitud no tiene informado su ID de usuario Tramitador.')
            ->actions([
                Action::make('modificarUsuarioTramitador')
                    ->label('Modificar usuario')
                    ->button()
                    ->url(UserResource::getUrl('edit', ['record' => $usuario], panel: 'admin')),
            ])
            ->send();

        throw (new Halt())->rollBackDatabaseTransaction();
    }

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
