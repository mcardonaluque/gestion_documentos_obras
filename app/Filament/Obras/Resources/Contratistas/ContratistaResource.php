<?php

namespace App\Filament\Obras\Resources\Contratistas;

use App\Models\Contratista;
use App\Models\TipoContratista;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;

class ContratistaResource extends Resource
{
    protected static ?string $model = Contratista::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Nombre';

    public static function actualizarUltimoCodigoTipo(?string $tipoContratista, mixed $codigoContratista): void
    {
        if (blank($tipoContratista) || ! is_numeric((string) $codigoContratista)) {
            return;
        }

        $nuevoCodigo = (int) $codigoContratista;

        $tipo = TipoContratista::query()
            ->where('Tipo_contratista', $tipoContratista)
            ->first();

        if (! $tipo) {
            return;
        }

        $actual = is_numeric((string) $tipo->Ultimo_codigo)
            ? (int) $tipo->Ultimo_codigo
            : 0;

        if ($nuevoCodigo > $actual) {
            $tipo->Ultimo_codigo = (string) $nuevoCodigo;
            $tipo->save();
        }
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('Codigo_contratista')
                    ->label('Codigo de Contratista')
                    ->required()
                    ->helperText('Se propone automaticamente al seleccionar el tipo de contratista.'),
                Forms\Components\Select::make('Tipo_contratista')
                    ->label('Tipo de Contratista')
                    ->relationship('tipoContratista', 'Denominacion')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateHydrated(static function (callable $set, mixed $state): void {
                        if (blank($state)) {
                            $set('ultimo_codigo_tipo_info', null);

                            return;
                        }

                        $ultimoCodigo = TipoContratista::query()
                            ->where('Tipo_contratista', $state)
                            ->value('Ultimo_codigo');

                        $set('ultimo_codigo_tipo_info', filled($ultimoCodigo) ? (string) $ultimoCodigo : null);
                    })
                    ->afterStateUpdated(static function (mixed $state, callable $set, callable $get): void {
                        if (blank($state)) {
                            $set('ultimo_codigo_tipo_info', null);

                            return;
                        }

                        $ultimoCodigo = TipoContratista::query()
                            ->where('Tipo_contratista', $state)
                            ->value('Ultimo_codigo');

                        $set('ultimo_codigo_tipo_info', filled($ultimoCodigo) ? (string) $ultimoCodigo : null);

                        if (blank($get('Codigo_contratista')) && is_numeric((string) $ultimoCodigo)) {
                            $set('Codigo_contratista', (string) ((int) $ultimoCodigo + 1));
                        }
                    })
                    ->required(),
                Forms\Components\TextInput::make('ultimo_codigo_tipo_info')
                    ->label('Ultimo codigo del tipo seleccionado')
                    ->placeholder('Selecciona un tipo de contratista')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\TextInput::make('Empresa'),
                Forms\Components\TextInput::make('Nombre'),
                Forms\Components\TextInput::make('Cif'),
                Forms\Components\Toggle::make('PresentadoCif')
                    ->required(),
                Forms\Components\TextInput::make('Domicilio'),
                Forms\Components\TextInput::make('CPostal')
                    ->numeric(),
                Forms\Components\TextInput::make('Localidad'),
                Forms\Components\TextInput::make('Municipio')
                    ->numeric(),
                Forms\Components\TextInput::make('Provincia')
                    ->numeric(),
                Forms\Components\TextInput::make('DomicilioFiscal'),
                Forms\Components\TextInput::make('CPostalFiscal')
                    ->numeric(),
                Forms\Components\TextInput::make('LocalidadFiscal'),
                Forms\Components\TextInput::make('MunicipioFiscal')
                    ->numeric(),
                Forms\Components\TextInput::make('ProvinciaFiscal')
                    ->numeric(),
                Forms\Components\TextInput::make('Telefono'),
                Forms\Components\TextInput::make('Telefono2'),
                Forms\Components\TextInput::make('Movil'),
                Forms\Components\TextInput::make('Fax'),
                Forms\Components\TextInput::make('Email'),
                Forms\Components\TextInput::make('RepLegal1'),
                Forms\Components\TextInput::make('Nif1'),
                Forms\Components\TextInput::make('Sexo1'),
                Forms\Components\TextInput::make('RepLegal2'),
                Forms\Components\TextInput::make('Nif2'),
                Forms\Components\TextInput::make('Sexo2'),
                Forms\Components\TextInput::make('RepLegal3'),
                Forms\Components\TextInput::make('Nif3'),
                Forms\Components\TextInput::make('Sexo3'),
                Forms\Components\TextInput::make('RepLegal4'),
                Forms\Components\TextInput::make('Nif4'),
                Forms\Components\TextInput::make('Sexo4'),
                Forms\Components\Toggle::make('PresentadoNif')
                    ->required(),
                Forms\Components\Toggle::make('Constitucion')
                    ->required(),
                Forms\Components\Toggle::make('RegMercantil')
                    ->required(),
                Forms\Components\Toggle::make('DeclResponsable')
                    ->required(),
                Forms\Components\Toggle::make('PoderBastanteado')
                    ->required(),
                Forms\Components\Textarea::make('OtrosDoc')
                    ->columnSpanFull(),
                Forms\Components\DateTimePicker::make('ClasificacionDef'),
                Forms\Components\DateTimePicker::make('ClasificacionProv'),
                Forms\Components\DateTimePicker::make('Escritura'),
                Forms\Components\Toggle::make('Registro')
                    ->required(),
                Forms\Components\TextInput::make('Estado'),
                Forms\Components\Toggle::make('Provisional')
                    ->required(),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Infolists\Components\TextEntry::make('Codigo_contratista')
                    ->numeric(),
                Infolists\Components\TextEntry::make('Tipo_contratista')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Empresa')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Nombre')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Cif')
                    ->placeholder('-'),
                Infolists\Components\IconEntry::make('PresentadoCif')
                    ->boolean(),
                Infolists\Components\TextEntry::make('Domicilio')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('CPostal')
                    ->numeric()
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Localidad')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Municipio')
                    ->numeric()
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Provincia')
                    ->numeric()
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('DomicilioFiscal')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('CPostalFiscal')
                    ->numeric()
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('LocalidadFiscal')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('MunicipioFiscal')
                    ->numeric()
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('ProvinciaFiscal')
                    ->numeric()
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Telefono')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Telefono2')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Movil')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Fax')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Email')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('RepLegal1')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Nif1')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Sexo1')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('RepLegal2')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Nif2')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Sexo2')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('RepLegal3')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Nif3')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Sexo3')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('RepLegal4')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Nif4')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Sexo4')
                    ->placeholder('-'),
                Infolists\Components\IconEntry::make('PresentadoNif')
                    ->boolean(),
                Infolists\Components\IconEntry::make('Constitucion')
                    ->boolean(),
                Infolists\Components\IconEntry::make('RegMercantil')
                    ->boolean(),
                Infolists\Components\IconEntry::make('DeclResponsable')
                    ->boolean(),
                Infolists\Components\IconEntry::make('PoderBastanteado')
                    ->boolean(),
                Infolists\Components\TextEntry::make('OtrosDoc')
                    ->placeholder('-')
                    ->columnSpanFull(),
                Infolists\Components\TextEntry::make('ClasificacionDef')
                    ->dateTime()
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('ClasificacionProv')
                    ->dateTime()
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('Escritura')
                    ->dateTime()
                    ->placeholder('-'),
                Infolists\Components\IconEntry::make('Registro')
                    ->boolean(),
                Infolists\Components\TextEntry::make('Estado')
                    ->placeholder('-'),
                Infolists\Components\IconEntry::make('Provisional')
                    ->boolean(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Nombre')
            ->columns([
                Tables\Columns\TextColumn::make('Codigo_contratista')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Tipo_contratista')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Empresa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Cif')
                    ->searchable(),
                Tables\Columns\IconColumn::make('PresentadoCif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('Domicilio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CPostal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Localidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Municipio')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Provincia')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('DomicilioFiscal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CPostalFiscal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('LocalidadFiscal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MunicipioFiscal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ProvinciaFiscal')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Telefono2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Movil')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Fax')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('RepLegal1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Nif1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Sexo1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('RepLegal2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Nif2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Sexo2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('RepLegal3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Nif3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Sexo3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('RepLegal4')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Nif4')
                    ->searchable(),
                Tables\Columns\TextColumn::make('Sexo4')
                    ->searchable(),
                Tables\Columns\IconColumn::make('PresentadoNif')
                    ->boolean(),
                Tables\Columns\IconColumn::make('Constitucion')
                    ->boolean(),
                Tables\Columns\IconColumn::make('RegMercantil')
                    ->boolean(),
                Tables\Columns\IconColumn::make('DeclResponsable')
                    ->boolean(),
                Tables\Columns\IconColumn::make('PoderBastanteado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('ClasificacionDef')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ClasificacionProv')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('Escritura')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('Registro')
                    ->boolean(),
                Tables\Columns\TextColumn::make('Estado')
                    ->searchable(),
                Tables\Columns\IconColumn::make('Provisional')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListContratistas::route('/'),
            'create' => Pages\CreateContratista::route('/create'),
            'view' => Pages\ViewContratista::route('/{record}'),
            'edit' => Pages\EditContratista::route('/{record}/edit'),
        ];
    }
}
