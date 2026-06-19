<?php

declare(strict_types=1);

namespace App\Services\DateRules;

use App\DTOs\DateRuleEvaluationResult;
use App\Enums\DateRuleAction;
use App\Enums\DateRuleType;
use App\Jobs\DispatchDateRuleNotificationsJob;
use App\Models\CustomNotification;
use App\Models\Expediente;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

final class DateRuleNotifier
{
    /**
     * @param Collection<int, DateRuleEvaluationResult> $results
     */
    public function notify(Model $model, Collection $results, ?int $actorUserId = null): void
    {
        $relevant = $results->filter(function (DateRuleEvaluationResult $result): bool {
            if (! $result->triggered) {
                return false;
            }

            return in_array($result->rule->accion, [DateRuleAction::NOTIFY], true)
                || $result->type->value === 'notification';
        });

        if ($relevant->isEmpty()) {
            return;
        }

        $actorId = $actorUserId ?? $this->resolveActorUserId();
        $teamUserIds = $this->resolveTeamUserIds($model);

        /** @var array<int, array{recipient_ids: array<int, int>, title: string, message: string, level: string}> $messages */
        $messages = [];

        foreach ($relevant as $result) {
            $recipientIds = $this->resolveRecipientsByType($result->type, $actorId, $teamUserIds);

            if ($recipientIds === []) {
                continue;
            }

            $messages[] = [
                'recipient_ids' => $recipientIds,
                'title' => $this->resolveTitle($result->type, $result->messageStage),
                'message' => $this->buildMessage($model, $result),
                'level' => $result->type === DateRuleType::ERROR ? 'danger' : 'warning',
            ];
        }

        if ($messages === []) {
            return;
        }

        if ((bool) config('date_rules.async_notifications', true)) {
            DispatchDateRuleNotificationsJob::dispatch($messages)
                ->onQueue((string) config('date_rules.notifications_queue', 'default'));

            return;
        }

        $this->dispatchNow($messages);
    }

    /**
     * @param array<int, array{recipient_ids: array<int, int>, title: string, message: string, level: string}> $messages
     */
    private function dispatchNow(array $messages): void
    {
        foreach ($messages as $message) {
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

    /**
     * @param array<int, int> $teamUserIds
     * @return array<int, int>
     */
    private function resolveRecipientsByType(DateRuleType $type, ?int $actorId, array $teamUserIds): array
    {
        if ($type === DateRuleType::WARNING) {
            return $actorId === null ? [] : [$actorId];
        }

        if ($type === DateRuleType::NOTIFICATION) {
            return $teamUserIds;
        }

        if ($type === DateRuleType::ERROR && $actorId !== null) {
            return [$actorId];
        }

        return [];
    }

    private function resolveTitle(DateRuleType $type, string $stage): string
    {
        if ($stage === 'preaviso') {
            return 'Preaviso de vencimiento de regla de fechas';
        }

        if ($stage === 'cumplida') {
            return 'Regla de fechas cumplida';
        }

        return match ($type) {
            DateRuleType::WARNING => 'Advertencia de control de fechas',
            DateRuleType::ERROR => 'Error de control de fechas',
            DateRuleType::NOTIFICATION => 'Notificacion de expediente',
        };
    }

    private function resolveActorUserId(): ?int
    {
        $id = Auth::id();

        return is_int($id) ? $id : null;
    }

    /**
     * @return array<int, int>
     */
    private function resolveTeamUserIds(Model $model): array
    {
        $teamId = $this->resolveTeamId($model);

        if ($teamId === null) {
            return [];
        }

        /** @var array<int, int> $ids */
        $ids = Team::query()
            ->whereKey($teamId)
            ->first()?->users()
            ->pluck('users.id')
            ->map(static fn (mixed $value): int => (int) $value)
            ->unique()
            ->values()
            ->all() ?? [];

        return $ids;
    }

    private function resolveTeamId(Model $model): ?int
    {
        $teamId = $model->getAttribute('team_id');

        if (is_numeric($teamId)) {
            return (int) $teamId;
        }

        $expedienteId = $model->getAttribute('expediente_id') ?? $model->getAttribute('Expediente');

        if (! is_scalar($expedienteId)) {
            return null;
        }

        /** @var int|null $id */
        $id = Expediente::query()
            ->where('expediente_id', (string) $expedienteId)
            ->value('team_id');

        return $id;
    }

    private function buildMessage(Model $model, DateRuleEvaluationResult $result): string
    {
        $recordId = (string) $model->getKey();

        return sprintf(
            '[%s #%s] %s',
            class_basename($model),
            $recordId,
            $result->message,
        );
    }
}
