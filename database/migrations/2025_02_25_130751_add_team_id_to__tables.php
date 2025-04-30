<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    protected $connection='Obras';
    public function up(): void
    {        
           Schema::table('DatosInicioDeObras', function (Blueprint $table) {
                //
              //  $table->string('Expediente',50);
              //$table->bigInteger('team_id');
              $table->timestamps();
    
            });
            Schema::table('ImportesDeObras', function (Blueprint $table) {
                //
               // $table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('Datos_Ejecucion_Obras', function (Blueprint $table) {
                //
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('ImportesPorOrganismo', function (Blueprint $table) {
                //
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('Datosadicionales', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('Documentos_de_fases_de_proyectos', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('CertificacionesDeObras', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('Certificaciones_obras_org', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('FasesdeProyectos', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('Honorarios', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            
            });
            Schema::table('Ayuda_Tecnica', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('Avisos', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('ObrasCedidas', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('ObrasRelacionadas', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('Prorrogas', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('ProrrogasConPenalidades', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('Proyectos', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
            Schema::table('Subvenciones', function (Blueprint $table) {
                //$table->string('Expediente',50)->nullable();
                $table->bigInteger('team_id')->nullable();
                $table->timestamps();
            });
        }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('_tables', function (Blueprint $table) {
            //
        });
    }
};
