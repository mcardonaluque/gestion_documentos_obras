<?php

namespace App\Filament\Livewire;

use Filament\Actions\Action;
use Filament\Livewire\DatabaseNotifications as BaseDatabaseNotifications;
use Filament\Notifications\Notification;
use Illuminate\Notifications\DatabaseNotification;

class PersistentDatabaseNotifications extends BaseDatabaseNotifications
{
    public function removeNotification(string $id): void
    {
        $this->getNotificationsQuery()
            ->where('id', $id)
            ->update(['read_at' => now()]);
    }

    public function clearNotifications(): void
    {
        $this->getUnreadNotificationsQuery()->update(['read_at' => now()]);
    }

    public function clearNotificationsAction(): Action
    {
        return parent::clearNotificationsAction()->visible(false);
    }

    public function getNotification(DatabaseNotification $notification): Notification
    {
        $data = $notification->data;

        if (! is_array($data)) {
            $data = [];
        }

        $title = trim((string) ($data['title'] ?? ''));
        $body = trim((string) ($data['body'] ?? ($data['message'] ?? '')));

        if ($title === '') {
            $title = 'Notificación';
        }

        if ($body === '') {
            $body = 'Sin contenido disponible';
        }

        $data['title'] = $title;
        $data['body'] = $body;
        $data['format'] = $data['format'] ?? 'filament';
        $data['duration'] = $data['duration'] ?? 'persistent';

        $notification->data = $data;

        return parent::getNotification($notification);
    }
}
