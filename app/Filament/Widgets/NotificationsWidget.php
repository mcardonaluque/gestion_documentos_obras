<?php

namespace App\Filament\Widgets;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\Action;
use App\Models\CustomNotification;
use App\Models\User;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;

class NotificationsWidget extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 1;

    public function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 5;
    }

    protected function getTableQuery(): Builder
    {
        return CustomNotification::query()
            ->where('notifiable_type', User::class)
            ->where('notifiable_id', Auth::id())
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
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
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('markAsRead')
                ->label('Marcar como Leída')
                ->icon('heroicon-o-check')
                ->action(fn (CustomNotification $record) => $record->markAsRead())
                ->hidden(fn (CustomNotification $record) => filled($record->read_at)),
            Action::make('view')
                ->label('Ver')
                ->icon('heroicon-o-eye')
                ->modalHeading(fn (CustomNotification $record) => $record->title)
                ->modalContent(fn (CustomNotification $record) => new HtmlString("
                    <div class='space-y-4'>
                        <div>
                            <strong>De:</strong> " . ($record->sender?->name ?? 'Sistema') . "
                        </div>
                        <div>
                            <strong>Mensaje:</strong>
                            <p class='mt-2 p-4 bg-gray-50 rounded-lg'>{$record->message}</p>
                        </div>
                        <div>
                            <strong>Enviado:</strong> {$record->created_at->format('d/m/Y H:i')}
                        </div>
                    </div>
                "))

        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Action::make('markAllAsRead')
                ->label('Marcar todas como leídas')
                ->icon('heroicon-o-check-circle')
                ->action(function () {
                    CustomNotification::where('notifiable_id', Auth::id())
                        ->whereNull('read_at')
                        ->update([
                            'read_at' => now(),
                        ]);
                }),
        ];
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No tienes notificaciones no leídas';
    }

    public static function canView(): bool
    {
        return true;
    }
}
