<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\DateRuleOperation;
use App\Services\DateRules\DateRuleEvaluator;
use App\Services\DateRules\DateRuleExecutionLogger;
use App\Services\DateRules\DateRuleNotifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * Observer transversal para validación de reglas de fechas.
 *
 * Intercepta operaciones de guardado en modelos observados para evaluar
 * restricciones temporales, registrar resultados y emitir notificaciones.
 */
final class DateRuleObserver
{
    /**
     * @var array<int, array<int, string>>
     */
    private array $dirtyDateFieldsByObject = [];

    public function __construct(
        private readonly DateRuleEvaluator $evaluator,
        private readonly DateRuleNotifier $notifier,
        private readonly DateRuleExecutionLogger $executionLogger,
    ) {
    }

    /**
     * Ejecuta validaciones bloqueantes antes de persistir el modelo.
     */
    public function saving(Model $model): void
    {
        $dirtyFields = $this->resolveDirtyFields($model);

        if ($dirtyFields === []) {
            return;
        }

        $this->dirtyDateFieldsByObject[spl_object_id($model)] = $dirtyFields;

        $results = $this->evaluator->evaluate($model, $dirtyFields, DateRuleOperation::fromModelState($model->exists));
        $errors = $this->evaluator->blockingErrorsByField($results);

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    /**
     * Registra y comunica las reglas evaluadas después del guardado.
     */
    public function saved(Model $model): void
    {
        $objectId = spl_object_id($model);
        $dirtyFields = $this->dirtyDateFieldsByObject[$objectId] ?? [];
        unset($this->dirtyDateFieldsByObject[$objectId]);

        if ($dirtyFields === []) {
            return;
        }

        $results = $this->evaluator->evaluate($model, $dirtyFields, DateRuleOperation::fromModelState(true));
        $this->executionLogger->log($model, $results);

        $actorId = Auth::id();

        $this->notifier->notify($model, $results, is_int($actorId) ? $actorId : null);
    }

    /**
     * @return array<int, string>
     */
    private function resolveDirtyFields(Model $model): array
    {
        /** @var array<int, string> $dirtyFields */
        $dirtyFields = array_keys($model->getDirty());

        return array_values(array_filter($dirtyFields, static function (string $field): bool {
            return str_contains(strtolower($field), 'fecha') || str_ends_with(strtolower($field), '_at');
        }));
    }
}
