<?php

namespace App\Filament\Obras\Resources\Contratistas;

use App\Models\Contratista;
use App\Models\TipoContratista;
use App\Models\TbMunicipio;
use App\Models\TbProvincias;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

    public static function getSiguienteCodigoDisponiblePorTipo(?string $tipoContratista): ?string
    {
        if (blank($tipoContratista)) {
            return null;
        }

        $ultimoCodigo = TipoContratista::query()
            ->where('Tipo_contratista', $tipoContratista)
            ->value('Ultimo_codigo');

        $siguiente = is_numeric((string) $ultimoCodigo)
            ? ((int) $ultimoCodigo + 1)
            : null;

        if ($siguiente === null) {
            return null;
        }

        while (Contratista::query()->where('Codigo_contratista', (string) $siguiente)->exists()) {
            $siguiente++;
        }

        return (string) $siguiente;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['provincia', 'provinciaFiscal', 'tipoContratista']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Forms\Components\TextInput::make('Codigo_contratista')
                    ->label('Codigo de Contratista')
                    ->required()
                    ->helperText('Se propone automaticamente al seleccionar el tipo de contratista.'),
                Forms\Components\Select::make('Tipo_contratista')
                    ->label('Tipo de Contratista')
                    ->options(fn () => TipoContratista::query()
                        ->orderBy('Denominacion')
                        ->pluck('Denominacion', 'Tipo_contratista')
                        ->toArray())
                    ->native(true)
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

                        $codigoSugerido = static::getSiguienteCodigoDisponiblePorTipo((string) $state);

                        if (filled($codigoSugerido)) {
                            $set('Codigo_contratista', $codigoSugerido);
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
                Forms\Components\Select::make('Provincia')
                    ->label('Provincia')
                    ->options(fn () => TbProvincias::orderBy('PR')
                        ->get()
                        ->mapWithKeys(fn ($p) => [$p->PR => trim($p->NOMBRE_PR)])
                        ->toArray())
                    ->placeholder('Selecciona una provincia')
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(fn (callable $set) => $set('Municipio', null)),
                Forms\Components\Select::make('Municipio')
                    ->label('Municipio')
                    ->options(function (callable $get): array {
                        if (blank($get('Provincia'))) {
                            return [];
                        }

                        return TbMunicipio::query()
                            ->where('Codigo_Provincia', $get('Provincia'))
                            ->orderBy('Municipio')
                            ->get(['Codigo_Municipio', 'Municipio'])
                            ->mapWithKeys(fn ($municipio) => [
                                $municipio->Codigo_Municipio => trim((string) $municipio->Municipio),
                            ])
                            ->toArray();
                    })
                    ->placeholder('Selecciona un municipio')
                    ->searchable()
                    ->disabled(fn (callable $get): bool => blank($get('Provincia'))),
                Section::make('Datos fiscales')
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('DomicilioFiscal'),
                        Forms\Components\TextInput::make('CPostalFiscal')
                            ->numeric(),
                        Forms\Components\TextInput::make('LocalidadFiscal'),
                        Forms\Components\Select::make('ProvinciaFiscal')
                            ->label('Provincia Fiscal')
                            ->options(fn () => TbProvincias::orderBy('PR')
                                ->get()
                                ->mapWithKeys(fn ($p) => [$p->PR => trim($p->NOMBRE_PR)])
                                ->toArray())
                            ->placeholder('Selecciona una provincia fiscal')
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn (callable $set) => $set('MunicipioFiscal', null)),
                        Forms\Components\Select::make('MunicipioFiscal')
                            ->label('Municipio Fiscal')
                            ->options(function (callable $get): array {
                                if (blank($get('ProvinciaFiscal'))) {
                                    return [];
                                }

                                return TbMunicipio::query()
                                    ->where('Codigo_Provincia', $get('ProvinciaFiscal'))
                                    ->orderBy('Municipio')
                                    ->get(['Codigo_Municipio', 'Municipio'])
                                    ->mapWithKeys(fn ($municipio) => [
                                        $municipio->Codigo_Municipio => trim((string) $municipio->Municipio),
                                    ])
                                    ->toArray();
                            })
                            ->placeholder('Selecciona un municipio fiscal')
                            ->searchable()
                            ->disabled(fn (callable $get): bool => blank($get('ProvinciaFiscal'))),
                    ]),
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
                Infolists\Components\TextEntry::make('tipoContratista.Denominacion')
                    ->label('Tipo de Contratista')
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
                Infolists\Components\TextEntry::make('municipio_nombre')
                    ->label('Municipio')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('provincia.NOMBRE_PR')
                    ->label('Provincia')
                    ->formatStateUsing(fn ($state) => trim((string) $state))
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('DomicilioFiscal')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('CPostalFiscal')
                    ->numeric()
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('LocalidadFiscal')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('municipio_fiscal_nombre')
                    ->label('Municipio Fiscal')
                    ->placeholder('-'),
                Infolists\Components\TextEntry::make('provinciaFiscal.NOMBRE_PR')
                    ->label('Provincia Fiscal')
                    ->formatStateUsing(fn ($state) => trim((string) $state))
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
                Tables\Columns\TextColumn::make('municipio_nombre')
                    ->label('Municipio')
                    ->searchable(query: static function (Builder $query, string $search): Builder {
                        return $query->whereHas('municipio', static function (Builder $municipioQuery) use ($search): void {
                            $municipioQuery
                                ->whereColumn('TbMunicipios.Codigo_Provincia', 'TBContratistas.Provincia')
                                ->where('TbMunicipios.Municipio', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('provincia.NOMBRE_PR')
                    ->label('Provincia')
                    ->formatStateUsing(fn ($state) => trim((string) $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('Provincia')
                    ->label('Cód. Provincia')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('DomicilioFiscal')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('CPostalFiscal')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('LocalidadFiscal')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('municipio_fiscal_nombre')
                    ->label('Municipio Fiscal')
                    ->searchable(query: static function (Builder $query, string $search): Builder {
                        return $query->whereHas('municipioFiscal', static function (Builder $municipioQuery) use ($search): void {
                            $municipioQuery
                                ->whereColumn('TbMunicipios.Codigo_Provincia', 'TBContratistas.ProvinciaFiscal')
                                ->where('TbMunicipios.Municipio', 'like', "%{$search}%");
                        });
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('provinciaFiscal.NOMBRE_PR')
                    ->label('Provincia Fiscal')
                    ->formatStateUsing(fn ($state) => trim((string) $state))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Telefono')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Telefono2')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Movil')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Fax')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('RepLegal1')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Nif1')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Sexo1')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('RepLegal2')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Nif2')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Sexo2')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('RepLegal3')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Nif3')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Sexo3')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('RepLegal4')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Nif4')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Sexo4')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('PresentadoNif')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('Constitucion')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('RegMercantil')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('DeclResponsable')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('PoderBastanteado')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ClasificacionDef')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ClasificacionProv')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Escritura')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('Registro')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('Estado')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('Provisional')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('Provincia')
                    ->label('Provincia')
                    ->options(fn () => TbProvincias::orderBy('PR')
                        ->get()
                        ->mapWithKeys(fn ($p) => [$p->PR => trim($p->NOMBRE_PR)])
                        ->toArray())
                    ->attribute('Provincia'),
                Tables\Filters\SelectFilter::make('Municipio')
                    ->label('Municipio')
                    ->options(fn () => TbMunicipio::query()
                        ->orderBy('Municipio')
                        ->get(['Codigo_Provincia', 'Codigo_Municipio', 'Municipio'])
                        ->mapWithKeys(fn ($m) => [
                            ((string) $m->Codigo_Provincia . '|' . (string) $m->Codigo_Municipio)
                                => trim((string) $m->Municipio) . ' (' . (string) $m->Codigo_Provincia . ')',
                        ])
                        ->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        $value = $data['value'] ?? null;

                        if (blank($value) || ! str_contains((string) $value, '|')) {
                            return $query;
                        }

                        [$provincia, $municipio] = explode('|', (string) $value, 2);

                        return $query
                            ->where('Provincia', $provincia)
                            ->where('Municipio', $municipio);
                    }),
                Tables\Filters\Filter::make('Nombre')
                    ->label('Nombre')
                    ->form([
                        Forms\Components\TextInput::make('nombre_valor')->label('Nombre'),
                    ])
                    ->query(fn ($query, array $data) => $query->when(
                        filled($data['nombre_valor'] ?? null),
                        fn ($q) => $q->where('Nombre', 'like', '%' . $data['nombre_valor'] . '%')
                    )),
                Tables\Filters\Filter::make('Cif')
                    ->label('CIF')
                    ->form([
                        Forms\Components\TextInput::make('cif_valor')->label('CIF'),
                    ])
                    ->query(fn ($query, array $data) => $query->when(
                        filled($data['cif_valor'] ?? null),
                        fn ($q) => $q->where('Cif', 'like', '%' . $data['cif_valor'] . '%')
                    )),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->recordUrl(fn (Contratista $record): string => static::getUrl('edit', ['record' => $record]))
            ->recordActions([
                Actions\EditAction::make(),
                Actions\ViewAction::make(),
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
