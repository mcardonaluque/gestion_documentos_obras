<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'Obras';

    public function up(): void
    {
        Schema::connection($this->connection)->create('ActasRecepcionObra', function (Blueprint $table): void {
            $table->string('expediente_id', 50)->primary();

            $table->string('TipoActaRecepcion', 1)->nullable();
            $table->dateTime('Fecha_Acta_RecProv')->nullable();
            $table->string('Lugar_Acta_Rec', 25)->nullable();

            $table->dateTime('Fecha_Com_Inf')->nullable();
            $table->dateTime('Fecha_Edicto_BOE')->nullable();
            $table->dateTime('Fecha_BOE')->nullable();
            $table->string('Num_BOE', 3)->nullable();
            $table->integer('Plazo_Reclam')->nullable();
            $table->dateTime('Fecha_Certif_NO_Reclam')->nullable();
            $table->dateTime('Fecha_Com_Inf_2')->nullable();
            $table->dateTime('Fecha_Com_Gob')->nullable();
            $table->dateTime('Fecha_Comun_Contrat')->nullable();
            $table->dateTime('Fecha_Certif_Liquid')->nullable();
            $table->dateTime('Fecha_Rem_Interv')->nullable();
            $table->dateTime('Fecha_Rem_MAP')->nullable();

            $table->string('Admin_ActaRecepcion', 60)->nullable();
            $table->string('Dir_ActaRecepcion', 60)->nullable();
            $table->string('Alcalde_ActaRecepcion', 60)->nullable();
            $table->string('Cont_ActaRecepcion', 60)->nullable();
            $table->string('Interv_ActaRecepcion', 60)->nullable();
            $table->string('Dipu_ActaRecepcion', 60)->nullable();

            $table->text('Texto')->nullable();

            $table->dateTime('Fecha_Paralizacion_Temporal')->nullable();
            $table->string('Motivo_Paralizacion', 200)->nullable();
            $table->dateTime('Fecha_Aprob_Paralizacion_Temporal')->nullable();
            $table->dateTime('Fecha_Inicio_Paralizacion')->nullable();
            $table->dateTime('Fecha_Final_Paralizacion')->nullable();

            $table->dateTime('Fecha_Acta_Rec')->nullable();
            $table->dateTime('Fecha_Aviso_Finalizacion')->nullable();
            $table->dateTime('Fecha_Aviso_FinalizacionMAP')->nullable();
            $table->dateTime('Fecha_Medicion')->nullable();

            $table->unsignedBigInteger('team_id')->nullable();
            $table->timestamps();

            $table->index('team_id');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('ActasRecepcionObra');
    }
};
