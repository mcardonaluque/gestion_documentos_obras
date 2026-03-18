<?php

namespace App\Filament\Widgets;

use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use App\Models\CustomNotification;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\HtmlString;

class NotificationsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    public function table(Table $table): Table
    {
        $user = Filament::auth()->user();

        return $table
            ->query(
                CustomNotification::query()
                    ->where('notifiable_type', User::class)
                    ->where('notifiable_id', $user?->getAuthIdentifier() ?? 0)
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('message')
                    ->label('Mensaje')
                    ->searchable()
                    ->wrap()
                    ->limit(50),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge()
                    ->colors([
                        'primary' => 'info',
                        'success' => 'task',
                        'warning' => 'warning',
                        'danger' => 'urgent',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'task' => 'Tarea',
                        'info' => 'Información',
                        'warning' => 'Advertencia',
                        'urgent' => 'Urgente',
                        default => $state,
                    }),
                IconColumn::make('read_at')
                    ->label('Leída')
                    ->boolean()
                    ->getStateUsing(fn (CustomNotification $record): bool => filled($record->read_at))
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle'),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
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
                Action::make('view')
                    ->label('Ver')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn (?CustomNotification $record) => $record?->title ?? 'Notificación')
                    ->modalContent(function (?CustomNotification $record): HtmlString {
                        if (! $record) {
                            return new HtmlString("<div class='text-sm text-gray-600'>No se pudo cargar la notificación.</div>");
                        }

                        $createdAt = $record->created_at?->format('d/m/Y H:i') ?? '-';

                        return new HtmlString("\n                        <div class='space-y-4'>\n                            <div>\n                                <strong>Mensaje:</strong>\n                                <p class='p-4 mt-2 rounded-lg bg-gray-50'>{$record->message}</p>\n                            </div>\n                            <div>\n                                <strong>Enviado:</strong> {$createdAt}\n                            </div>\n                        </div>\n                    ");
                    }),
            ])
            ->headerActions([
                Action::make('markAllAsRead')
                    ->label('Marcar todas como leídas')
                    ->icon('heroicon-o-check-circle')
                    ->action(function () {
                        $user = Filament::auth()->user();

                        CustomNotification::where('notifiable_id', $user?->getAuthIdentifier() ?? 0)
                            ->where('notifiable_type', User::class)
                            ->whereNull('read_at')
                            ->update([
                                'read_at' => now(),
                            ]);
                    }),
            ]);
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No tienes notificaciones';
    }

    public static function canView(): bool
    {
        return true;
    }
}
