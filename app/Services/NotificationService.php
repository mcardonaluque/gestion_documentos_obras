<?php
namespace App\Services;

use App\Models\CustomNotification;
use App\Models\User;
use App\Models\Team;
use App\Notifications\GenericDatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Servicio de envío de notificaciones internas.
 *
 * Resuelve destinatarios por usuario, equipo o difusión global y crea
 * registros persistentes compatibles con la interfaz de Filament.
 */
class NotificationService
{
    /**
     * Envía una notificación a uno o varios destinatarios resueltos por criterios.
     *
     * @param array<int, int> $userIds
     * @param array<string, mixed> $data
     */
    public static function sendByTargets(
        string $title,
        string $message,
        string $type = 'info',
        bool $toAllUsers = false,
        array $userIds = [],
        ?int $teamId = null,
        array $data = []
    ): Collection {
        $recipients = self::resolveRecipients($toAllUsers, $userIds, $teamId);
        $notifications = collect();

        foreach ($recipients as $recipient) {
            $payload = array_merge($data, [
                'title' => $title,
                'message' => $message,
                'body' => $message,
                'type' => $type,
                'format' => 'filament',
                'duration' => 'persistent',
            ]);

            $notifications->push(CustomNotification::create([
                'id' => (string) Str::uuid(),
                'type' => GenericDatabaseNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id' => $recipient->id,
                'data' => $payload,
                'read_at' => null,
            ]));
        }

        return $notifications;
    }

    /**
     * Atajo para enviar una notificación a un único usuario.
     *
     * @param array<string, mixed> $data
     */
    public static function sendToUser(
        User $recipient,
        string $title,
        string $message,
        string $type = 'info',
        array $data = []
    ): CustomNotification {
        return self::sendByTargets(
            $title,
            $message,
            $type,
            false,
            [$recipient->id],
            null,
            $data,
        )->first();
    }

    /**
     * Resuelve el conjunto final de usuarios destinatarios.
     *
     * @param array<int, int> $userIds
     */
    protected static function resolveRecipients(bool $toAllUsers, array $userIds, ?int $teamId): Collection
    {
        if ($toAllUsers) {
            return User::query()->get()->unique('id')->values();
        }

        if (!empty($userIds)) {
            return User::query()->whereIn('id', $userIds)->get()->unique('id')->values();
        }

        if ($teamId) {
            $team = Team::find($teamId);
            return $team?->users()->get()->unique('id')->values() ?? collect();
        }

        return collect();
    }
}
