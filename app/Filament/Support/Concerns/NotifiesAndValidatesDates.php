<?php

declare(strict_types=1);

namespace App\Filament\Support\Concerns;

use App\Enums\DateRuleOperation;
use App\Models\Team;
use App\Services\DateRules\DateRuleEvaluator;
use App\Services\DateRules\DateRuleExecutionLogger;
use App\Services\DateRules\DateRuleNotifier;
use App\Services\NotificationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

/**
 * Trait util para recursos/paginas Filament que necesiten:
 * - enviar notificaciones de forma uniforme;
 * - aplicar controles de fechas en acciones personalizadas.
 */
trait NotifiesAndValidatesDates
{
    /**
     * Ejecuta validacion de un campo fecha en tiempo real (onBlur), antes de guardar.
     *
     * Pensado para usarse desde afterStateUpdated() en campos con ->live(onBlur: true).
     */
    public function validateDateFieldOnBlur(string $field): void
    {
        $model = $this->buildDraftModelFromFormState();

        if (! $model instanceof Model) {
            return;
        }

        $evaluator = app(DateRuleEvaluator::class);
        $operation = DateRuleOperation::fromModelState($model->exists);
        $results = $evaluator->evaluate($model, [$field], $operation);
        $errors = $evaluator->blockingErrorsByField($results);

        $errorPath = 'data.' . $field;

        if ($errors !== []) {
            if (method_exists($this, 'addError')) {
                $message = $errors[$field][0] ?? 'Error de validacion de fecha.';
                $this->addError($errorPath, $message);
            }

            throw ValidationException::withMessages($errors);
        }

        if (method_exists($this, 'resetValidation')) {
            $this->resetValidation($errorPath);
        }

        $warningMessages = [];

        foreach ($results as $result) {
            if (! $result->triggered || $result->type->isBlocking()) {
                continue;
            }

            $warningMessages[] = $result->message;
        }

        if ($warningMessages !== []) {
            Notification::make()
                ->title('Control de fechas')
                ->warning()
                ->body(implode("\n", $warningMessages))
                ->send();
        }
    }

    /**
     * Envia notificacion al usuario autenticado (tramitador).
     *
     * @param array<string, mixed> $data
     */
    protected function notifyCurrentUser(string $title, string $message, string $type = 'info', array $data = []): void
    {
        $userId = Auth::id();

        if (! is_int($userId)) {
            return;
        }

        NotificationService::sendByTargets(
            title: $title,
            message: $message,
            type: $type,
            userIds: [$userId],
            data: $data,
        );
    }

    /**
     * Envia notificacion a todos los usuarios de un team/ayuntamiento.
     *
     * @param array<string, mixed> $data
     */
    protected function notifyTeam(int $teamId, string $title, string $message, string $type = 'info', array $data = []): void
    {
        NotificationService::sendByTargets(
            title: $title,
            message: $message,
            type: $type,
            teamId: $teamId,
            data: $data,
        );
    }

    /**
     * Persiste un modelo aplicando controles de fechas sin duplicar observer.
     *
     * Usar cuando se hace una accion custom y se quiere controlar explicitamente
     * validacion + log + notificaciones en el mismo flujo.
     */
    protected function persistWithDateControls(Model $model): void
    {
        $operation = DateRuleOperation::fromModelState($model->exists);
        $dateFields = $this->resolveDateFieldsFromDirty($model);

        if ($dateFields === []) {
            $model->save();

            return;
        }

        $evaluator = app(DateRuleEvaluator::class);
        $preResults = $evaluator->evaluate($model, $dateFields, $operation);
        $errors = $evaluator->blockingErrorsByField($preResults);

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        $model->saveQuietly();

        $postResults = $evaluator->evaluate($model, $dateFields, $operation);

        app(DateRuleExecutionLogger::class)->log($model, $postResults);

        $actorId = Auth::id();

        app(DateRuleNotifier::class)->notify($model, $postResults, is_int($actorId) ? $actorId : null);
    }

    /**
     * Aplica controles de fechas para cambios manuales (sin save del modelo).
     *
     * @param array<int, string> $dateFields
     */
    protected function applyDateControlsForManualChanges(
        Model $model,
        array $dateFields,
        ?DateRuleOperation $operation = null,
    ): void {
        $normalizedFields = array_values(array_unique(array_filter($dateFields, 'is_string')));

        if ($normalizedFields === []) {
            return;
        }

        $resolvedOperation = $operation ?? DateRuleOperation::fromModelState($model->exists);
        $evaluator = app(DateRuleEvaluator::class);
        $results = $evaluator->evaluate($model, $normalizedFields, $resolvedOperation);
        $errors = $evaluator->blockingErrorsByField($results);

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        app(DateRuleExecutionLogger::class)->log($model, $results);

        $actorId = Auth::id();

        app(DateRuleNotifier::class)->notify($model, $results, is_int($actorId) ? $actorId : null);
    }

    protected function resolveTeamIdFromModel(Model $model): ?int
    {
        $teamId = $model->getAttribute('team_id');

        if (is_numeric($teamId)) {
            return (int) $teamId;
        }

        $expedienteId = $model->getAttribute('expediente_id') ?? $model->getAttribute('Expediente');

        if (! is_scalar($expedienteId)) {
            return null;
        }

        /** @var int|null $resolvedTeamId */
        $resolvedTeamId = Team::query()
            ->whereHas('expedientes', function ($query) use ($expedienteId): void {
                $query->where('expediente_id', (string) $expedienteId);
            })
            ->value('id');

        return $resolvedTeamId;
    }

    /**
     * @return array<int, string>
     */
    private function resolveDateFieldsFromDirty(Model $model): array
    {
        /** @var array<int, string> $dirtyFields */
        $dirtyFields = array_keys($model->getDirty());

        return array_values(array_filter($dirtyFields, static function (string $field): bool {
            $normalized = strtolower($field);

            return str_contains($normalized, 'fecha') || str_ends_with($normalized, '_at');
        }));
    }

    private function buildDraftModelFromFormState(): ?Model
    {
        if (! property_exists($this, 'form') || ! method_exists($this->form, 'getState')) {
            return null;
        }

        /** @var mixed $state */
        $state = $this->form->getState();

        if (! is_array($state)) {
            return null;
        }

        if (method_exists($this, 'getRecord')) {
            $record = $this->getRecord();

            if ($record instanceof Model) {
                $draft = $record->newInstance($record->getAttributes(), true);
                $draft->fill($state);

                return $draft;
            }
        }

        if (! method_exists($this, 'getResource')) {
            return null;
        }

        /** @var mixed $resourceClass */
        $resourceClass = $this::getResource();

        if (! is_string($resourceClass) || ! method_exists($resourceClass, 'getModel')) {
            return null;
        }

        /** @var mixed $modelClass */
        $modelClass = $resourceClass::getModel();

        if (! is_string($modelClass) || ! is_subclass_of($modelClass, Model::class)) {
            return null;
        }

        /** @var Model $draft */
        $draft = new $modelClass();
        $draft->fill($state);

        return $draft;
    }
}
