<?php
namespace App\Filament\Obras\Pages;

use App\Filament\Ayuntamientos\Resources\Documentoexpedientes\DocumentoexpedienteResource;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getTitle(): string
    {
        return 'Planes de Obras Provinciales';
    }

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('documentos_proyecto')
                    ->label('Documentos de Proyecto')
                    ->icon('heroicon-o-document')
                    ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'proyecto'])),
                Action::make('documentos_aprobacion')
                    ->label('Documentos de Aprobación')
                    ->icon('heroicon-o-document-check')
                    ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'aprobacion'])),
                Action::make('documentos_cesion')
                    ->label('Documentos de Cesión')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'cesion'])),
                Action::make('documentos_contratacion')
                    ->label('Documentos de Contratación')
                    ->icon('heroicon-o-document-text')
                    ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'contratacion'])),
                Action::make('documentos_ejecucion')
                    ->label('Documentos de Ejecución')
                    ->icon('heroicon-o-document-chart-bar')
                    ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'ejecucion'])),
                Action::make('documentos_justificacion')
                    ->label('Documentos de Justificación')
                    ->icon('heroicon-o-document-plus')
                    ->url(DocumentoexpedienteResource::getUrl('index', ['fase' => 'justificacion'])),
            ])
                ->label('Documentos por fase')
                ->icon('heroicon-o-folder-open')
                ->color('gray'),
        ];
    }
}
