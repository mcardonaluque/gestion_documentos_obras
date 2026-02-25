<?php

namespace App\Filament\Obras\Resources\Notifications\Pages;


use Illuminate\Database\Eloquent\Model;
use App\Filament\Obras\Resources\Notifications\NotificationResource;
use App\Models\CustomNotification;
use App\Services\NotificationService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class CreateNotification extends CreateRecord
{
    protected static string $resource =NotificationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        if (! ($data['to_all'] ?? false) && empty($data['team_id']) && empty($data['user_ids'])) {
            throw ValidationException::withMessages([
                'user_ids' => 'Selecciona uno o varios usuarios, un equipo o marca "Notificar a todos los usuarios".',
            ]);
        }

        $created = NotificationService::sendByTargets(
            title: $data['title'],
            message: $data['message'],
            type: $data['type'],
            toAllUsers: (bool) ($data['to_all'] ?? false),
            userIds: $data['user_ids'] ?? [],
            teamId: $data['team_id'] ?? null,
            senderId: Auth::id(),
            data: [
                'origin' => 'notification_resource',
            ],
        );

        return $created->first()
            ?? CustomNotification::query()->latest('created_at')->first();
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
