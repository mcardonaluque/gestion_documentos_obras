<?php

namespace App\Filament\Obras\Resources\DatosEjecucionObras\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ActaRecepcionRelationManager extends RelationManager
{
    protected static string $relationship = 'actaRecepcion';

    protected static ?string $title = 'Acta de Recepción';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('TipoActaRecepcion')->maxLength(1),
                DateTimePicker::make('Fecha_Acta_RecProv'),
                TextInput::make('Lugar_Acta_Rec')->maxLength(25),
                DateTimePicker::make('Fecha_Com_Inf'),
                DateTimePicker::make('Fecha_Edicto_BOE'),
                DateTimePicker::make('Fecha_BOE'),
                TextInput::make('Num_BOE')->maxLength(3),
                TextInput::make('Plazo_Reclam')->numeric(),
                DateTimePicker::make('Fecha_Certif_NO_Reclam'),
                DateTimePicker::make('Fecha_Com_Inf_2'),
                DateTimePicker::make('Fecha_Com_Gob'),
                DateTimePicker::make('Fecha_Comun_Contrat'),
                DateTimePicker::make('Fecha_Certif_Liquid'),
                DateTimePicker::make('Fecha_Rem_Interv'),
                DateTimePicker::make('Fecha_Rem_MAP'),
                TextInput::make('Admin_ActaRecepcion')->maxLength(60),
                TextInput::make('Dir_ActaRecepcion')->maxLength(60),
                TextInput::make('Alcalde_ActaRecepcion')->maxLength(60),
                TextInput::make('Cont_ActaRecepcion')->maxLength(60),
                TextInput::make('Interv_ActaRecepcion')->maxLength(60),
                TextInput::make('Dipu_ActaRecepcion')->maxLength(60),
                Textarea::make('Texto')->columnSpanFull(),
                DateTimePicker::make('Fecha_Paralizacion_Temporal'),
                TextInput::make('Motivo_Paralizacion')->maxLength(200),
                DateTimePicker::make('Fecha_Aprob_Paralizacion_Temporal'),
                DateTimePicker::make('Fecha_Inicio_Paralizacion'),
                DateTimePicker::make('Fecha_Final_Paralizacion'),
                DateTimePicker::make('Fecha_Acta_Rec'),
                DateTimePicker::make('Fecha_Aviso_Finalizacion'),
                DateTimePicker::make('Fecha_Aviso_FinalizacionMAP'),
                DateTimePicker::make('Fecha_Medicion'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('expediente_id')
            ->columns([
                TextColumn::make('TipoActaRecepcion')->label('Tipo'),
                TextColumn::make('Fecha_Acta_RecProv')->dateTime()->label('Acta prov.'),
                TextColumn::make('Lugar_Acta_Rec')->label('Lugar'),
                TextColumn::make('Fecha_Acta_Rec')->dateTime()->label('Acta recepción'),
                TextColumn::make('Fecha_Medicion')->dateTime()->label('Medición'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['expediente_id'] = $this->getOwnerRecord()->expediente_id;
                        $data['team_id'] = $this->getOwnerRecord()->team_id;

                        return $data;
                    })
                    ->hidden(fn (): bool => filled($this->getRelationship()->first())),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
