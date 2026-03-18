<?php

namespace App\Filament\Obras\Resources\Notifications;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Obras\Resources\Notifications\Pages\CreateNotification;
use App\Filament\Obras\Resources\Notifications\Pages\EditNotification;
use App\Filament\Obras\Resources\Notifications\Pages\ListNotifications;
use App\Models\CustomNotification;
use App\Models\Team;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class NotificationResource extends Resource
{
    protected static ?string $model = CustomNotification::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bell';

    protected static string | \UnitEnum | null $navigationGroup = 'Sistema';
    protected static ?string $modelLabel = 'Notificación';
    protected static ?string $pluralModelLabel = 'Notificaciones';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Toggle::make('to_all')
                    ->label('Notificar a todos los usuarios')
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state): void {
                        if ($state) {
                            $set('team_id', null);
                            $set('user_ids', []);
                        }
                    })
                    ->default(false),

                Select::make('team_id')
                    ->label('Equipo')
                    ->options(Team::pluck('name', 'id'))
                    ->visible(fn (callable $get) => ! $get('to_all'))
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state): void {
                        if (filled($state)) {
                            $set('user_ids', []);
                        }
                    })
                    ->dehydrated(fn (callable $get) => ! $get('to_all'))
                    ->required(fn (callable $get) => ! $get('to_all') && empty($get('user_ids')))
                    ->placeholder('Selecciona un equipo'),

                Select::make('user_ids')
                    ->label('Usuarios')
                    ->multiple()
                    ->reactive()
                    ->options(User::pluck('name', 'id'))
                    ->visible(fn (callable $get) => ! $get('to_all'))
                    ->afterStateUpdated(function (callable $set, $state): void {
                        if (is_array($state) && count($state) > 0) {
                            $set('team_id', null);
                        }
                    })
                    ->dehydrated(fn (callable $get) => ! $get('to_all'))
                    ->required(fn (callable $get) => ! $get('to_all') && blank($get('team_id')))
                    ->placeholder('Selecciona uno o varios usuarios'),
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                Textarea::make('message')
                    ->label('Mensaje')
                    ->required()
                    ->rows(3),
                Select::make('type')
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
                TextColumn::make('estado')
                    ->label('Estado')
                    ->getStateUsing(fn (CustomNotification $record): string => filled($record->read_at) ? 'Leída' : 'No Leída')
                    ->badge()
                    ->colors([
                        'danger' => fn (string $state): bool => $state === 'No Leída',
                        'success' => fn (string $state): bool => $state === 'Leída',
                    ]),
                TextColumn::make('created_at')
                    ->label('Enviada')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'task' => 'Tarea',
                        'info' => 'Información',
                        'warning' => 'Advertencia',
                        'urgent' => 'Urgente',
                    ]),
                Filter::make('unread')
                    ->label('Solo no leídas')
                    ->query(fn (Builder $query): Builder => $query->whereNull('read_at')),
                Filter::make('read')
                    ->label('Solo leídas')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('read_at')),
            ])
            ->recordActions([
                Action::make('markAsRead')
                    ->label('Marcar como Leída')
                    ->icon('heroicon-o-envelope-open')
                    ->action(fn (CustomNotification $record) => $record->markAsRead())
                    ->hidden(fn (CustomNotification $record) => filled($record->read_at)),
                Action::make('markAsUnread')
                    ->label('Marcar como No Leída')
                    ->icon('heroicon-o-envelope')
                    ->action(fn (CustomNotification $record) => $record->markAsUnread())
                    ->hidden(fn (CustomNotification $record) => blank($record->read_at)),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkAction::make('markAsRead')
                    ->label('Marcar como leídas')
                    ->icon('heroicon-o-envelope-open')
                    ->action(fn ($records) => $records->each->markAsRead()),
                BulkAction::make('markAsUnread')
                    ->label('Marcar como no leídas')
                    ->icon('heroicon-o-envelope')
                    ->action(fn ($records) => $records->each->markAsUnread()),
                DeleteBulkAction::make(),
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
        $user = Filament::auth()->user();

        return parent::getEloquentQuery()
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', $user?->getAuthIdentifier() ?? 0)
            ->orderBy('created_at', 'desc');
    }
}
