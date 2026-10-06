<?php

declare(strict_types=1);

namespace App\Services\Database;

use App\Enums\ProcedimientoAlmacenadoObras;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\ConnectionResolverInterface;
use InvalidArgumentException;

/**
 * Ejecuta procedimientos SQL Server previamente autorizados para la conexión Obras.
 *
 * El enum evita interpolar nombres externos en SQL. Los valores se envían como
 * bindings PDO y los nombres de parámetros se validan antes de formar EXECUTE.
 */
final class EjecutorProcedimientosAlmacenadosSqlServer
{
    public function __construct(
        private readonly ConnectionResolverInterface $connectionResolver,
    ) {
    }

    /**
     * @param array<string, bool|float|int|string|null> $parameters
     * @return list<array<string, mixed>>
        * @throws InvalidArgumentException Cuando un nombre de parámetro es inválido o no se recibe ninguno.
     */
    public function ejecutar(ProcedimientoAlmacenadoObras $procedimiento, array $parameters): array
    {
        $query = sprintf('EXECUTE %s %s', $procedimiento->value, $this->buildParameterList($parameters));

        /** @var list<\stdClass> $rows */
        $rows = $this->connection()->select($query, array_values($parameters));

        $result = [];

        foreach ($rows as $row) {
            /** @var array<string, mixed> $data */
            $data = get_object_vars($row);
            $result[] = $data;
        }

        return $result;
    }

    /**
        * Construye la lista nombrada de parámetros para EXECUTE.
        *
     * @param array<string, bool|float|int|string|null> $parameters
     */
    private function buildParameterList(array $parameters): string
    {
        $items = [];

        foreach (array_keys($parameters) as $parameterName) {
            if (preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $parameterName) !== 1) {
                throw new InvalidArgumentException(sprintf('El nombre del parámetro "%s" no es válido.', $parameterName));
            }

            $items[] = '@' . $parameterName . ' = ?';
        }

        if ($items === []) {
            throw new InvalidArgumentException('Debe indicar al menos un parámetro para ejecutar el procedimiento.');
        }

        return implode(', ', $items);
    }

    private function connection(): ConnectionInterface
    {
        return $this->connectionResolver->connection('Obras');
    }
}
