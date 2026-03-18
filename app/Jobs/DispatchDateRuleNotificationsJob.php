<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\CustomNotification;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class DispatchDateRuleNotificationsJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param array<int, array{recipient_ids: array<int, int>, title: string, message: string, level: string}> $messages
     */
    public function __construct(
        private readonly array $messages,
    ) {
    }

    public function handle(): void
    {
        foreach ($this->messages as $message) {
            foreach ($message['recipient_ids'] as $recipientId) {
                CustomNotification::query()->create([
                    'type' => 'date-rule',
                    'notifiable_type' => User::class,
                    'notifiable_id' => $recipientId,
                    'data' => [
                        'title' => $message['title'],
                        'message' => $message['message'],
                        'type' => $message['level'],
                    ],
                ]);
            }
        }
    }
}
