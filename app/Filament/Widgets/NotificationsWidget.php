<?php

namespace App\Filament\Widgets;

use App\Models\CustomNotification;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

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
            ->where('recipient_id', auth()->id())
            ->orderBy('created_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('title')
                ->label('Título')
                ->searchable()
                ->wrap(),
            Tables\Columns\TextColumn::make('message')
                ->label('Mensaje')
                ->searchable()
                ->wrap()
                ->limit(50),
            Tables\Columns\TextColumn::make('type')
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
            Tables\Columns\IconColumn::make('is_read')
                ->label('Leída')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Fecha')
                ->dateTime()
                ->sortable(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('markAsRead')
                ->label('Marcar como Leída')
                ->icon('heroicon-o-check')
                ->action(fn (CustomNotification $record) => $record->markAsRead())
                ->hidden(fn (CustomNotification $record) => $record->is_read),
            Tables\Actions\Action::make('view')
                ->label('Ver')
                ->icon('heroicon-o-eye')
                ->modalHeading(fn (CustomNotification $record) => $record->title)
                ->modalContent(fn (CustomNotification $record) => new HtmlString("
                    <div class='space-y-4'>
                        <div>
                            <strong>De:</strong> {$record->sender->name}
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
            Tables\Actions\Action::make('markAllAsRead')
                ->label('Marcar todas como leídas')
                ->icon('heroicon-o-check-circle')
                ->action(function () {
                    CustomNotification::where('recipient_id', auth()->id())
                        ->where('is_read', false)
                        ->update([
                            'is_read' => true,
                            'read_at' => now(),
                        ]);
                }),
        ];
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'No tienes Notificationes';
    }

    public static function canView(): bool
    {
        return true;
    }
}