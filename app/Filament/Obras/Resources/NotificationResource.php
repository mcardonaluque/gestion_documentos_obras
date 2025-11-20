<?php

namespace App\Filament\Obras\Resources;

use App\Filament\Obras\Resources\NotificationResource\Pages\CreateNotification;
use App\Filament\Obras\Resources\NotificationResource\Pages\EditNotification;
use App\Filament\Obras\Resources\NotificationResource\Pages\ListNotifications;
use App\Models\CustomNotification;
use App\Models\Team;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class NotificationResource extends Resource
{
    protected static ?string $model = CustomNotification::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Sistema';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('to_all')
                    ->label('Notificar a todos los usuarios')
                    ->reactive()
                    ->default(false),

                Forms\Components\Select::make('team_id')
                    ->label('Equipo')
                    ->options(Team::pluck('name', 'id'))
                    ->visible(fn (callable $get) => ! $get('to_all'))
                    ->reactive()
                    ->placeholder('Selecciona un equipo'),

                Forms\Components\Select::make('user_ids')
                    ->label('Usuarios')
                    ->multiple()
                    ->options(User::pluck('name', 'id'))
                    ->visible(fn (callable $get) => ! $get('to_all'))
                    ->placeholder('Selecciona uno o varios usuarios'),
                Forms\Components\TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->label('Mensaje')
                    ->required()
                    ->rows(3),
                Forms\Components\Select::make('type')
                    ->label('Tipo de Notificación')
                    ->options([
                        'task' => 'Tarea',
                        'info' => 'Información',
                        'warning' => 'Advertencia',
                        'urgent' => 'Urgente',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sender.name')
                    ->label('De')
                    ->sortable(),
                TextColumn::make('recipient.name')
                    ->label('Para')
                    ->sortable(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('message')
                    ->label('Mensaje')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->colors([
                        'primary' => fn ($state) => in_array($state, ['info', 'Información']),
                        'success' => fn ($state) => in_array($state, ['task', 'Tarea']),
                        'warning' => fn ($state) => in_array($state, ['warning', 'Advertencia']),
                        'danger'  => fn ($state) => in_array($state, ['urgent', 'Urgente']),
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'task' => 'Tarea',
                        'info' => 'Información',
                        'warning' => 'Advertencia',
                        'urgent' => 'Urgente',
                        default => $state,
                    }),
                TextColumn::make('is_read')
                    ->label('Estado')
                    ->badge()
                    ->colors([
                        'danger' => false,
                        'success' => true,
                    ])
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Leída' : 'No Leída'),
                TextColumn::make('created_at')
                    ->label('Enviada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'task' => 'Tarea',
                        'info' => 'Información',
                        'warning' => 'Advertencia',
                        'urgent' => 'Urgente',
                    ]),
                Tables\Filters\Filter::make('unread')
                    ->label('Solo no leídas')
                    ->query(fn (Builder $query): Builder => $query->where('is_read', false)),
            ])
            ->actions([
                Tables\Actions\Action::make('markAsRead')
                    ->label('Marcar como Leída')
                    ->icon('heroicon-o-envelope-open')
                    ->action(fn (CustomNotification $record) => $record->markAsRead())
                    ->hidden(fn (CustomNotification $record) => $record->is_read),
                Tables\Actions\Action::make('markAsUnread')
                    ->label('Marcar como No Leída')
                    ->icon('heroicon-o-envelope')
                    ->action(fn (CustomNotification $record) => $record->markAsUnread())
                    ->hidden(fn (CustomNotification $record) => !$record->is_read),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('markAsRead')
                    ->label('Marcar como leídas')
                    ->icon('heroicon-o-check')
                    ->action(fn ($records) => $records->each->markAsRead()),
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListNotifications::route('/'),
            'create' => CreateNotification::route('/create'),
            'edit' => EditNotification::route('/{record}/edit'),
        ];
    }

    // Solo mostrar Notificationes del usuario actual	
    public static function getEloquentQuery(): Builder
    {
      
        return parent::getEloquentQuery()
            ->where('notifiable_id', auth()->id())
            ->orderBy('created_at', 'desc');
    }
}