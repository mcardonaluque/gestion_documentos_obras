<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdatePasswordsToBcrypt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrar-usuarios-a-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migra usuarios desde Usuarios a users con la misma clave en hash Laravel y team 0';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $legacyUsers = DB::connection('Obras')
            ->table('Usuarios')
            ->select('Id_usuario', 'Nombre_largo', 'Departamento', 'Clave')
            ->whereNotNull('Id_usuario')
            ->get();

        DB::connection('Obras')->beginTransaction();

        try {
            $count = 0;

            foreach ($legacyUsers as $legacyUser) {
                $idUsuario = trim((string) $legacyUser->Id_usuario);

                if ($idUsuario === '') {
                    continue;
                }

                $nombrePersona = trim((string) ($legacyUser->Nombre_largo ?? ''));
                $departamento = trim((string) ($legacyUser->Departamento ?? ''));
                $clave = trim((string) ($legacyUser->Clave ?? ''));
                $email = strtolower(str_replace(' ', '', $idUsuario)) . '@malaga.es';

                $user = User::query()->firstOrNew([
                    'id_usuario' => $idUsuario,
                ]);

                $user->name = $idUsuario;
                $user->email = $email;

                if ($clave !== '') {
                    $user->password = $clave;
                }

                $user->nombre_persona = $nombrePersona !== '' ? $nombrePersona : null;
                $user->departamento = $departamento !== '' ? $departamento : null;
                $user->is_active = true;
                $user->interno = true;
                $user->save();

                $user->teams()->syncWithoutDetaching([0]);

                $count++;
            }

            DB::connection('Obras')->commit();
            $this->info("Usuarios migrados o actualizados: {$count}");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::connection('Obras')->rollBack();
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}

