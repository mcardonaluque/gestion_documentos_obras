<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\CertificacionesResource\Pages;
use App\Filament\Obras\Resources\CertificacionesResource\RelationManagers;
use App\Models\Certificaciones;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificacionesResource extends Resource
{
    protected static ?string $model = Certificaciones::class;

    protected static ?string $modelLabel = 'Certificación';
    protected static ?string $pluralModelLabel = 'Certificaciones'; 
    protected static ?string $tenantOwnershipRelationshipName = 'team';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationColor = 'custom-blue';
    protected static ?string $navigationGroup="Ejecución";
    protected static ?string $navigationLabel ='Certificaciones de Obras';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('Codigo_plan')
                    ->required()
                    ->maxLength(7),
                Forms\Components\TextInput::make('Numero_obra')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('Subreferencia')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ao_ejecucion')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('numero_certificacion')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('tipo_justificante')
                ->label('Tipo de Justificante')
                ->relationship('tipojustificante', 'descripcion')
                ->columnSpan(2),
                Forms\Components\TextInput::make('importe_certificacion_Pts')
                    ->numeric(),
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
                Forms\Components\TextInput::make('ImporteDescontadoPenalidades_Pts')
                    ->numeric()
                    ->default(0),
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
                Forms\Components\TextInput::make('team_id')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('Codigo_plan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Numero_obra')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Subreferencia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_ejecucion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero_certificacion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipo_justificante')
                    ->searchable(),
                Tables\Columns\TextColumn::make('importe_certificacion_Pts')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('mes_certificacion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ao_certificacion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_documento')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_firma_cont')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Numero_fact')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Fecha_Fact')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_EnvioAdmin')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_admin')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_devolucion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_rectificacion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_env_dipu')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_env_secret')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partida_presup')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_recepcion')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_propuesta')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero_propuesta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_decreto')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('numero_decreto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipo_aprobacion')
                    ->searchable(),
                Tables\Columns\IconColumn::make('ultima_certif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('estado_certif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ImporteDescontadoPenalidades_Pts')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('NumSecProrConPenal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Observaciones')
                    ->searchable(),
                Tables\Columns\TextColumn::make('importe_certificacion')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('importe_certificacion_sinIVA')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('importe_certificacion_IVA')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('porcentajeIVA')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('importe_certificacion_ajusteIVA')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ImporteDescontadoPenalidades')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_DevoPara')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_RecepRectif')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ImpCertAdjudicado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ImpCertModificado')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CSV')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CSVC')
                    ->searchable(),
                Tables\Columns\IconColumn::make('cert_final')
                    ->boolean(),
                Tables\Columns\TextColumn::make('Expediente')
                    ->searchable(),
                Tables\Columns\TextColumn::make('team_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCertificaciones::route('/'),
            'create' => Pages\CreateCertificaciones::route('/create'),
            'edit' => Pages\EditCertificaciones::route('/{record}/edit'),
        ];
    }
}
