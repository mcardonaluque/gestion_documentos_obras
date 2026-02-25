<?php

namespace App\Jobs;

use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class DispatchNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $title;
    public string $message;
    public string $type;
    public bool $toAllUsers;
    public array $userIds;
    public ?int $teamId;
    public ?int $senderId;
    public array $data;

    public function __construct(
        string $title,
        string $message,
        string $type,
        bool $toAllUsers = false,
        array $userIds = [],
        ?int $teamId = null,
        ?int $senderId = null,
        array $data = []
    )
    {
        $this->title    = $title;
        $this->message  = $message;
        $this->type     = $type;
        $this->toAllUsers = $toAllUsers;
        $this->userIds = $userIds;
        $this->teamId = $teamId;
        $this->senderId = $senderId;
        $this->data     = $data;
    }

    public function handle(): void
    {
        NotificationService::sendByTargets(
            $this->title,
            $this->message,
            $this->type,
            $this->toAllUsers,
            $this->userIds,
            $this->teamId,
            $this->senderId,
            $this->data
        );
    }
}
