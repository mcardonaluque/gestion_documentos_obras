<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Forms\Components\ObraGeneralInfo;
use App\Models\DatosDeInicioDeObras;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificacionesRelationManager extends RelationManager
{
    protected static string $relationship = 'certificaciones';

    public function form(Schema $schema): Schema
    {
        $record=$schema->getRecord();
        $obra = request()->route('record') ? DatosDeInicioDeObras::find(request()->route('record')) : null;
        return $schema
            ->columns(7)

            ->components([

               /* ObraGeneralInfo::make('informacion_general')
                ->label('Información General de la Obra')
                ->SetObraData($record ?? null),*/

                TextInput::make('numero_certificacion')
                    ->required()
                    ->numeric(),
                Select::make('tipo_justificante')
                ->label('Tipo de Justificante')
                ->relationship('tipojustificante', 'descripcion')
                ->columnSpan(2),

                TextInput::make('mes_certificacion')
                    ->numeric(),
                TextInput::make('ao_certificacion')
                    ->numeric(),
                DateTimePicker::make('fecha_documento'),
                DateTimePicker::make('fecha_firma_cont'),
                TextInput::make('Numero_fact')
                    ->maxLength(12),
                DateTimePicker::make('Fecha_Fact'),
                DateTimePicker::make('fecha_EnvioAdmin'),
                DateTimePicker::make('fecha_admin'),
                DateTimePicker::make('fecha_devolucion'),
                DateTimePicker::make('fecha_rectificacion'),
                DateTimePicker::make('fecha_env_dipu'),
                DateTimePicker::make('fecha_env_secret'),
                TextInput::make('partida_presup')
                    ->maxLength(60),
                DateTimePicker::make('fecha_recepcion'),
                DateTimePicker::make('fecha_propuesta'),
                TextInput::make('numero_propuesta')
                    ->maxLength(10),
                DateTimePicker::make('fecha_decreto'),
                TextInput::make('numero_decreto')
                    ->maxLength(10),
                TextInput::make('tipo_aprobacion')
                    ->maxLength(1),
                Toggle::make('ultima_certif'),
                TextInput::make('estado_certif')
                    ->maxLength(3),

                TextInput::make('NumSecProrConPenal')
                    ->maxLength(5),
                TextInput::make('Observaciones')
                    ->maxLength(250),
                TextInput::make('importe_certificacion')
                    ->numeric(),
                TextInput::make('importe_certificacion_sinIVA')
                    ->numeric(),
                TextInput::make('importe_certificacion_IVA')
                    ->numeric(),
                TextInput::make('porcentajeIVA')
                    ->numeric(),
                TextInput::make('importe_certificacion_ajusteIVA')
                    ->numeric(),
                TextInput::make('ImporteDescontadoPenalidades')
                    ->numeric(),
                DateTimePicker::make('fecha_DevoPara'),
                DateTimePicker::make('fecha_RecepRectif'),
                TextInput::make('ImpCertAdjudicado')
                    ->numeric(),
                TextInput::make('ImpCertModificado')
                    ->numeric(),
                TextInput::make('CSV')
                    ->maxLength(50),
                TextInput::make('CSVC')
                    ->maxLength(50),
                Toggle::make('cert_final'),
                TextInput::make('expediente_id')
                    ->maxLength(100),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero_certificacion')
            ->columns([
                TextColumn::make('numero_certificacion'),
                TextColumn::make('tipoJustificante.descripcion')
                    ->searchable(),
                //Tables\Columns\TextColumn::make('importe_certificacion_Pts')
                //    ->numeric()
                //    ->sortable(),
                TextColumn::make('mes_certificacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_certificacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fecha_documento')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expediente_id')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
