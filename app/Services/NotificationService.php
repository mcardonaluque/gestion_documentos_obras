<?php
namespace App\Services;

use App\Models\CustomNotification;
use App\Models\User;
use App\Models\Team;
use App\Notifications\GenericDatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationService
{
    public static function sendByTargets(
        string $title,
        string $message,
        string $type = 'info',
        bool $toAllUsers = false,
        array $userIds = [],
        ?int $teamId = null,
        ?int $senderId = null,
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
            ]);

            $notifications->push(CustomNotification::create([
                'id' => (string) Str::uuid(),
                'type' => GenericDatabaseNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id' => $recipient->id,
                'sender_id' => $senderId ?? Auth::id() ?? $recipient->id,
                'recipient_id' => $recipient->id,
                'data' => $payload,
                'read_at' => null,
            ]));
        }

        return $notifications;
    }

    public static function sendToUser(
        User $recipient,
        string $title,
        string $message,
        string $type = 'info',
        array $data = [],
        ?int $senderId = null
    ): CustomNotification {
        return self::sendByTargets(
            title: $title,
            message: $message,
            type: $type,
            toAllUsers: false,
            userIds: [$recipient->id],
            teamId: null,
            senderId: $senderId,
            data: $data,
        )->first();
    }

    protected static function resolveRecipients(bool $toAllUsers, array $userIds, ?int $teamId): Collection
    {
        if ($toAllUsers) {
            return User::all();
        }

        if ($teamId) {
            $team = Team::find($teamId);
            return $team?->users()->get() ?? collect();
        }

        if (!empty($userIds)) {
            return User::whereIn('id', $userIds)->get();
        }

        return collect();
    }
}
