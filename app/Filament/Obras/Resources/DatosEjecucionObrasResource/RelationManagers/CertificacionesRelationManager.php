<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObrasResource\RelationManagers;

use App\Forms\Components\ObraGeneralInfo;
use App\Models\DatosDeInicioDeObras;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificacionesRelationManager extends RelationManager
{
    protected static string $relationship = 'certificaciones';

    public function form(Form $form): Form
    {
        $record=$form->getRecord();
        $obra = request()->route('record') ? DatosDeInicioDeObras::find(request()->route('record')) : null;
        return $form
            ->columns(7)
            
            ->schema([
               
               /* ObraGeneralInfo::make('informacion_general')
                ->label('Información General de la Obra')
                ->SetObraData($record ?? null),*/
               
                Forms\Components\TextInput::make('numero_certificacion')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('tipo_justificante')
                ->label('Tipo de Justificante')
                ->relationship('tipojustificante', 'descripcion')
                ->columnSpan(2),
               
                Forms\Components\TextInput::make('mes_certificacion')
                    ->numeric(),
                Forms\Components\TextInput::make('ao_certificacion')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('fecha_documento'),
                Forms\Components\DateTimePicker::make('fecha_firma_cont'),
                Forms\Components\TextInput::make('Numero_fact')
                    ->maxLength(12),
                Forms\Components\DateTimePicker::make('Fecha_Fact'),
                Forms\Components\DateTimePicker::make('fecha_EnvioAdmin'),
                Forms\Components\DateTimePicker::make('fecha_admin'),
                Forms\Components\DateTimePicker::make('fecha_devolucion'),
                Forms\Components\DateTimePicker::make('fecha_rectificacion'),
                Forms\Components\DateTimePicker::make('fecha_env_dipu'),
                Forms\Components\DateTimePicker::make('fecha_env_secret'),
                Forms\Components\TextInput::make('partida_presup')
                    ->maxLength(60),
                Forms\Components\DateTimePicker::make('fecha_recepcion'),
                Forms\Components\DateTimePicker::make('fecha_propuesta'),
                Forms\Components\TextInput::make('numero_propuesta')
                    ->maxLength(10),
                Forms\Components\DateTimePicker::make('fecha_decreto'),
                Forms\Components\TextInput::make('numero_decreto')
                    ->maxLength(10),
                Forms\Components\TextInput::make('tipo_aprobacion')
                    ->maxLength(1),
                Forms\Components\Toggle::make('ultima_certif'),
                Forms\Components\TextInput::make('estado_certif')
                    ->maxLength(3),
                
                Forms\Components\TextInput::make('NumSecProrConPenal')
                    ->maxLength(5),
                Forms\Components\TextInput::make('Observaciones')
                    ->maxLength(250),
                Forms\Components\TextInput::make('importe_certificacion')
                    ->numeric(),
                Forms\Components\TextInput::make('importe_certificacion_sinIVA')
                    ->numeric(),
                Forms\Components\TextInput::make('importe_certificacion_IVA')
                    ->numeric(),
                Forms\Components\TextInput::make('porcentajeIVA')
                    ->numeric(),
                Forms\Components\TextInput::make('importe_certificacion_ajusteIVA')
                    ->numeric(),
                Forms\Components\TextInput::make('ImporteDescontadoPenalidades')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('fecha_DevoPara'),
                Forms\Components\DateTimePicker::make('fecha_RecepRectif'),
                Forms\Components\TextInput::make('ImpCertAdjudicado')
                    ->numeric(),
                Forms\Components\TextInput::make('ImpCertModificado')
                    ->numeric(),
                Forms\Components\TextInput::make('CSV')
                    ->maxLength(50),
                Forms\Components\TextInput::make('CSVC')
                    ->maxLength(50),
                Forms\Components\Toggle::make('cert_final'),
                Forms\Components\TextInput::make('expediente_id')
                    ->maxLength(100),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('numero_certificacion')
            ->columns([
                Tables\Columns\TextColumn::make('numero_certificacion'),
                Tables\Columns\TextColumn::make('tipoJustificante.descripcion')
                    ->searchable(),
                //Tables\Columns\TextColumn::make('importe_certificacion_Pts')
                //    ->numeric()
                //    ->sortable(),
                Tables\Columns\TextColumn::make('mes_certificacion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_certificacion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_documento')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expediente_id')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
