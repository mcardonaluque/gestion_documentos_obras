<?php

declare(strict_types=1);

namespace App\Services\Templates;

use App\DTOs\Templates\DefinicionPlantillaWordHeredada;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\ConnectionResolverInterface;
use RuntimeException;

/**
 * Consulta el catálogo documental heredado de SQL Server.
 *
 * RegistroModelos identifica la plantilla por nombre y aporta la clave
 * (CodPro, NumApli, NumMod). Esa misma clave localiza las variables Word
 * definidas para el documento en RegistroDatos.
 */
final class TechnicalAssistanceTemplateRepository
{
    public function __construct(
        private readonly ConnectionResolverInterface $connectionResolver,
    ) {
    }

    /**
     * Localiza una plantilla por su nombre funcional en RegistroModelos.
     *
     * @throws RuntimeException Cuando no existe una plantilla con ese nombre.
     */
    public function findTemplateByName(string $templateName): DefinicionPlantillaWordHeredada
    {
        /** @var \stdClass|null $row */
        $row = $this->connection()
            ->table('RegistroModelos')
            ->select(['CodPro', 'NumApli', 'NumMod', 'Nombre', 'DestinoModelo'])
            ->where('Nombre', $templateName)
            ->first();

        if ($row === null) {
            throw new RuntimeException(sprintf('No existe una plantilla registrada con el nombre "%s".', $templateName));
        }

        return new DefinicionPlantillaWordHeredada(
            (int) $row->CodPro,
            (int) $row->NumApli,
            (int) $row->NumMod,
            (string) $row->Nombre,
            $this->nullableString($row->DestinoModelo ?? null),
        );
    }

    /**
     * Obtiene los nombres de las variables Word activas en el catálogo heredado.
     *
     * @return list<string> Lista sin duplicados de valores RegistroDatos.NombreWord.
     */
    public function getWordVariables(DefinicionPlantillaWordHeredada $template): array
    {
        /** @var list<\stdClass> $rows */
        $rows = $this->connection()
            ->table('RegistroDatos')
            ->select('NombreWord')
            ->where('CodPro', $template->procedureCode)
            ->where('NumApli', $template->applicationNumber)
            ->where('NumMod', $template->modelNumber)
            ->whereNotNull('NombreWord')
            ->orderBy('NombreWord')
            ->get()
            ->all();

        $variables = [];

        foreach ($rows as $row) {
            $name = trim((string) $row->NombreWord);

            if ($name !== '') {
                $variables[] = $name;
            }
        }

        return array_values(array_unique($variables));
    }

    /**
     * Devuelve la conexión SQL Server donde residen RegistroModelos y RegistroDatos.
     */
    private function connection(): ConnectionInterface
    {
        return $this->connectionResolver->connection('Obras');
    }

    private function nullableString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
