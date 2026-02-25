<?php

namespace App\Filament\Obras\Resources\Certificaciones;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\Certificaciones\Pages\ListCertificaciones;
use App\Filament\Obras\Resources\Certificaciones\Pages\CreateCertificaciones;
use App\Filament\Obras\Resources\Certificaciones\Pages\EditCertificaciones;
use App\Filament\Obras\Resources\CertificacionesResource\Pages;
use App\Models\Certificaciones;
use App\Models\DatosDeInicioDeObras;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Forms\Components\ObraGeneralInfo;

class CertificacionesResource extends Resource
{
    protected static ?string $model = Certificaciones::class;

    protected static ?string $modelLabel = 'Certificación';
    protected static ?string $pluralModelLabel = 'Certificaciones';
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    /**protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
   // protected static ?string $navigationGroup="Ejecución";
    protected static ?string $navigationLabel ='Certificaciones de Obras';**/

    public static function form(Schema $schema): Schema
    {
       // $obra =GetObraData(request()->route('record'));
       $record=$schema->getRecord();
        $obra = request()->route('record') ? DatosDeInicioDeObras::find(request()->route('record')) : null;
        return $schema
            ->columns(7)

            ->components([

                ObraGeneralInfo::make('informacion_general')
                ->label('Información General de la Obra')
                ->SetObraData($record ?? null),

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expediente_id')
                ->searchable(),
                TextColumn::make('Codigo_plan')
                    ->searchable(),
                TextColumn::make('Numero_obra')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('Subreferencia')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('numero_certificacion')
                    ->numeric()
                    ->sortable(),
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
                TextColumn::make('fecha_firma_cont')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('Numero_fact')
                    ->searchable(),
                TextColumn::make('Fecha_Fact')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_EnvioAdmin')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_admin')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_devolucion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_rectificacion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_env_dipu')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_env_secret')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('partida_presup')
                    ->searchable(),
                TextColumn::make('fecha_recepcion')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_propuesta')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('numero_propuesta')
                    ->searchable(),
                TextColumn::make('fecha_decreto')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('numero_decreto')
                    ->searchable(),
                TextColumn::make('tipo_aprobacion')
                    ->searchable(),
                IconColumn::make('ultima_certif')
                    ->boolean(),
                TextColumn::make('estado_certif')
                    ->searchable(),
                //Tables\Columns\TextColumn::make('ImporteDescontadoPenalidades_Pts')
                //    ->numeric()
                //    ->sortable(),
                TextColumn::make('NumSecProrConPenal')
                    ->searchable(),
                TextColumn::make('Observaciones')
                    ->searchable(),
                TextColumn::make('importe_certificacion')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('importe_certificacion_sinIVA')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('importe_certificacion_IVA')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('porcentajeIVA')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('importe_certificacion_ajusteIVA')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ImporteDescontadoPenalidades')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fecha_DevoPara')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('fecha_RecepRectif')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ImpCertAdjudicado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ImpCertModificado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('CSV')
                    ->searchable(),
                TextColumn::make('CSVC')
                    ->searchable(),
                IconColumn::make('cert_final')
                    ->boolean(),
                TextColumn::make('expediente_id')
                    ->searchable(),
                TextColumn::make('team_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCertificaciones::route('/'),
            'create' => CreateCertificaciones::route('/create'),
            'edit' => EditCertificaciones::route('/{record}/edit'),
        ];
    }
    protected static function GetObraData(Certificaciones $obra)
    {

        return  DatosDeInicioDeObras::find($obra);

    }
}
