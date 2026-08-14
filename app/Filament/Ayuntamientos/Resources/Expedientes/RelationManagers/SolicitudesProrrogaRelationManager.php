<?php

namespace App\Filament\Ayuntamientos\Resources\Expedientes\RelationManagers;

use App\Enums\ProrrogaAlcance;
use App\Enums\ProrrogaOrigen;
use App\Enums\ProrrogaTipo;
use App\Models\DocumentoExpediente;
use App\Models\Expediente;
use App\Models\PlazoObraActivo;
use App\Models\Prorroga;
use App\Services\Prorrogas\ProrrogaRulesService;
use App\Services\Prorrogas\SolicitudProrrogaService;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SolicitudesProrrogaRelationManager extends RelationManager
{
    protected static string $relationship = 'solicitudesProrroga';

    protected static ?string $title = 'Solicitudes de prórroga';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components(static::getFormComponents());
    }

    public static function getFormComponents(): array
    {
        return [
                Select::make('tipo_prorroga')
                    ->label('Tipo de prórroga')
                    ->options(ProrrogaTipo::options())
                    ->required(),
                Select::make('alcance_prorroga')
                    ->label('Alcance')
                    ->options(ProrrogaAlcance::options())
                    ->visible(fn (callable $get): bool => in_array($get('tipo_prorroga'), [ProrrogaTipo::NORMATIVA->value, ProrrogaTipo::PROYECTO->value, ProrrogaTipo::PROYECTODC->value, ProrrogaTipo::DOCUMENTACION->value], true)),
                Select::make('origen_prorroga')
                    ->label('Origen')
                    ->options(ProrrogaOrigen::options())
                    ->default(ProrrogaOrigen::SOLICITUD->value)
                    ->disabled(),
                DatePicker::make('fecha_limite_solicitada')
                    ->label('Fecha límite solicitada')
                    ->nullable(),
                TextInput::make('dias_solicitados')
                    ->label('Días solicitados')
                    ->numeric()
                    ->minValue(1)
                    ->nullable(),
                Textarea::make('MotivoProrroga')
                    ->label('Motivo')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('archivo_solicitud')
                    ->label('Documento de solicitud')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(10240)
                    ->directory('documentos-expedientes')
                    ->preserveFilenames()
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('observaciones_tramitacion')
                    ->label('Observaciones')
                    ->columnSpanFull(),
        ];
    }

    public static function createForExpediente(Expediente $owner, array $data): void
    {
        $plazo = $owner->obraEjecucion?->plazosActivos()->where('activo', true)->orderByDesc('id')->first();

        if (! $plazo) {
            Notification::make()->title('No hay un plazo activo para este expediente')->danger()->send();
            return;
        }

        $rulesService = app(ProrrogaRulesService::class);
        $requestWindow = $rulesService->getRequestWindow($owner, $plazo);

        if (! $requestWindow['fecha_maxima_solicitud']) {
            Notification::make()->title('No se ha configurado la fecha límite de la normativa aplicable')->danger()->send();
            return;
        }

        if (($data['tipo_prorroga'] ?? null) !== $requestWindow['tipo']) {
            Notification::make()
                ->title('El tipo de prórroga no corresponde a la fase activa')
                ->body("Para la fase {$requestWindow['fase']} debe solicitarse una prórroga de {$requestWindow['tipo']}.")
                ->danger()
                ->send();
            return;
        }

        if (now()->greaterThan($requestWindow['fecha_maxima_solicitud'])) {
            Notification::make()
                ->title('La solicitud está fuera de plazo')
                ->body('Debía presentarse antes del ' . $requestWindow['fecha_maxima_solicitud']->format('d/m/Y') . '.')
                ->danger()
                ->send();
            return;
        }

        $service = app(SolicitudProrrogaService::class);
        $requestData = $service->buildRequestData($plazo, $requestWindow['normativa'], $data);

        if (! $requestData['valid']) {
            Notification::make()->title($requestData['message'])->danger()->send();
            return;
        }

        $phaseRule = $service->validatePhaseRestriction($plazo, (string) ($data['tipo_prorroga'] ?? ''));
        if (! $phaseRule['valid']) {
            Notification::make()->title($phaseRule['message'])->danger()->send();
            return;
        }

        $payload = [
            'NumSec' => ((int) (Prorroga::query()->max('NumSec') ?? 0)) + 1,
            'expediente_id' => $owner->expediente_id,
            'team_id' => $owner->team_id,
            'tipo_prorroga' => $data['tipo_prorroga'],
            'alcance_prorroga' => $data['alcance_prorroga'] ?? null,
            'origen_prorroga' => ProrrogaOrigen::SOLICITUD->value,
            'fecha_limite_anterior' => $requestData['fecha_limite_anterior'],
            'fecha_limite_nueva' => $requestData['fecha_limite_nueva'],
            'dias_concedidos' => $requestData['dias_solicitados'],
            'MotivoProrroga' => $data['MotivoProrroga'],
            'observaciones_tramitacion' => $data['observaciones_tramitacion'] ?? null,
            'FecSolicitudProrrogaDeCont' => now(),
        ];

        $owner->obraEjecucion?->prorrogas()->create($payload);

        if (! empty($data['archivo_solicitud'])) {
            $documentoPayload = [
                'expediente_id' => $owner->expediente_id,
                'referencia' => $owner->referencia,
                'subreferencia' => $owner->subreferencia,
                'ao_ejecucion' => $owner->ao_ejecucion,
                'descripcion' => 'Solicitud de prórroga',
                'cod_documento' => null,
                'fechaincorporacion' => now()->toDateString(),
                'nsecuencia' => DocumentoExpediente::nextSequenceForExpediente($owner->expediente_id),
                'csv' => $data['archivo_solicitud'],
                'archivo' => $data['archivo_solicitud'],
                'estado' => 'Nuevo',
                'team_id' => $owner->team_id,
            ];

            $documentoPayload = DocumentoExpediente::applyExpedienteDefaults($documentoPayload);
            $owner->documentos()->create($documentoPayload);
        }

        Notification::make()->title('Solicitud de prórroga creada')->success()->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('NumSec')
            ->columns([
                TextColumn::make('tipo_prorroga')
                    ->label('Tipo')
                    ->badge(),
                TextColumn::make('fecha_limite_nueva')
                    ->label('Fecha límite nueva')
                    ->date(),
                TextColumn::make('dias_concedidos')
                    ->label('Días concedidos'),
                TextColumn::make('MotivoProrroga')
                    ->label('Motivo')
                    ->limit(80),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Solicitar prórroga')
                    ->action(function (array $data, RelationManager $livewire): void {
                        static::createForExpediente($livewire->getOwnerRecord(), $data);
                    }),
            ]);
    }
}
