<?php

namespace App\Filament\Obras\Resources\Notifications\Pages;


use Illuminate\Database\Eloquent\Model;
use App\Filament\Obras\Resources\Notifications\NotificationResource;
use App\Models\CustomNotification;
use App\Services\NotificationService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Validation\ValidationException;


class CreateNotification extends CreateRecord
{
    protected static string $resource =NotificationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $toAllUsers = (bool) ($data['to_all'] ?? false);
        $userIds = array_values(array_unique(array_map('intval', $data['user_ids'] ?? [])));
        $teamId = filled($data['team_id'] ?? null) ? (int) $data['team_id'] : null;

        if ($toAllUsers) {
            $userIds = [];
            $teamId = null;
        } elseif (! empty($userIds)) {
            $teamId = null;
        }

        if (! $toAllUsers && empty($teamId) && empty($userIds)) {
            throw ValidationException::withMessages([
                'user_ids' => 'Selecciona uno o varios usuarios, un equipo o marca "Notificar a todos los usuarios".',
            ]);
        }

        $created = NotificationService::sendByTargets(
            title: $data['title'],
            message: $data['message'],
            type: $data['type'],
            toAllUsers: $toAllUsers,
            userIds: $userIds,
            teamId: $teamId,
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
