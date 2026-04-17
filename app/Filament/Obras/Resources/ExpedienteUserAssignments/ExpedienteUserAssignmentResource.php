<?php

declare(strict_types=1);

namespace App\Filament\Obras\Resources\ExpedienteUserAssignments;

use App\Filament\Obras\Resources\ExpedienteUserAssignments\Pages\CreateExpedienteUserAssignment;
use App\Filament\Obras\Resources\ExpedienteUserAssignments\Pages\EditExpedienteUserAssignment;
use App\Filament\Obras\Resources\ExpedienteUserAssignments\Pages\ListExpedienteUserAssignments;
use App\Models\Expediente;
use App\Models\ExpedienteUserAssignment;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

/**
 * Resource Filament para asignar expedientes a usuarios tramitadores.
 *
 * Facilita la segregación funcional del trabajo y la limitación de visibilidad
 * de listados en función de las asignaciones registradas.
 */
class ExpedienteUserAssignmentResource extends Resource
{
    protected static ?string $model = ExpedienteUserAssignment::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Asignacion de Expedientes';

    protected static ?string $modelLabel = 'Asignacion de Expediente';

    protected static ?string $pluralModelLabel = 'Asignaciones de Expedientes';

    protected static string | \UnitEnum | null $navigationGroup = 'Expedientes';

    protected static ?int $navigationSort = 3;

    /** Determina si el usuario autenticado puede acceder a la gestión de asignaciones. */
    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User
            && ($user->hasRole('super_admin') || $user->hasAnyRole(['Abogado', 'abogado']));
    }

    /** Precarga relaciones necesarias para la tabla de asignaciones. */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['expediente', 'user']);
    }

    /** Define el formulario guiado de asignación de expedientes por año y usuario. */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('ao_ejecucion_filter')
                    ->label('Año de expedientes')
                    ->columnSpanFull()
                    ->extraFieldWrapperAttributes([
                        'style' => 'width: 10%; min-width: 7rem;',
                    ])
                    ->options(static function (): array {
                        $currentYear = (int) now()->format('Y');
                        $minYear = (int) (Expediente::query()->whereNotNull('ao_ejecucion')->min('ao_ejecucion') ?? $currentYear);

                        if ($minYear > $currentYear) {
                            $minYear = $currentYear;
                        }

                        return collect(range($currentYear, $minYear))
                            ->mapWithKeys(static fn (int $year): array => [(string) $year => (string) $year])
                            ->toArray();
                    })
                    ->default(static fn (?ExpedienteUserAssignment $record): ?string => isset($record?->expediente?->ao_ejecucion)
                        ? (string) $record->expediente->ao_ejecucion
                        : now()->format('Y'))
                    ->searchable()
                    ->preload()
                    ->live()
                    ->dehydrated(false)
                    ->afterStateUpdated(static fn (callable $set): mixed => $set('expediente_id', null))
                    ->required(),
                Select::make('user_id')
                    ->label('Usuario tramitador')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: static fn (Builder $query): Builder => $query
                            ->where(static fn (Builder $query): Builder => $query
                                ->whereHas('teams', static fn (Builder $teamQuery): Builder => $teamQuery->whereKey(0))
                                ->orWhere('interno', true))
                            ->where(static fn (Builder $query): Builder => $query
                                ->whereNull('interno')
                                ->orWhere('interno', true))
                            ->orderBy('name')
                    )
                    ->getOptionLabelFromRecordUsing(
                        static fn (User $record): string => sprintf('%s (%s)', $record->name, (string) $record->email)
                    )
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required(),
                Select::make('expediente_id')
                    ->label('Expediente')
                    ->options(static fn (callable $get): array => Expediente::query()
                        ->select(['expediente_id', 'nombre_obra', 'ao_ejecucion'])
                        ->when(
                            filled($get('ao_ejecucion_filter')),
                            static fn (Builder $query): Builder => $query->where('ao_ejecucion', (string) $get('ao_ejecucion_filter'))
                        )
                        ->orderBy('nombre_obra')
                        ->get()
                        ->mapWithKeys(
                            static fn (Expediente $record): array => [
                                $record->expediente_id => sprintf('%s - %s', $record->expediente_id, (string) ($record->nombre_obra ?? 'Sin nombre')),
                            ]
                        )
                        ->toArray())
                    ->searchable()
                    ->preload()
                    ->live()
                    ->required()
                    ->helperText('Selecciona el año para cargar solo los expedientes correspondientes.'),
                Placeholder::make('asignaciones_existentes')
                    ->label('Asignaciones ya realizadas')
                    ->columnSpanFull()
                    ->content(static function (callable $get): HtmlString {
                        $selectedYear = $get('ao_ejecucion_filter');
                        $selectedExpediente = $get('expediente_id');
                        $selectedUser = $get('user_id');

                        $query = ExpedienteUserAssignment::query()
                            ->with(['expediente', 'user'])
                            ->latest('created_at');

                        if (filled($selectedYear)) {
                            $query->whereHas(
                                'expediente',
                                static fn (Builder $expedienteQuery): Builder => $expedienteQuery->where('ao_ejecucion', (string) $selectedYear)
                            );
                        }

                        if (filled($selectedExpediente)) {
                            $query->where('expediente_id', (string) $selectedExpediente);
                        }

                        $assignments = $query->limit(15)->get();

                        if ($assignments->isEmpty()) {
                            return new HtmlString(
                                '<div class="rounded-xl border border-dashed border-gray-300 p-4 text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">No hay asignaciones registradas para la selección actual.</div>'
                            );
                        }

                        $notice = '';

                        if (filled($selectedUser)) {
                            $selectedUserName = User::query()->whereKey($selectedUser)->value('name');
                            $safeUserName = e((string) ($selectedUserName ?? 'usuario seleccionado'));

                            $notice = '<div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-200">'
                                . 'Se resaltan en verde las asignaciones ya realizadas para ' . $safeUserName . '.'
                                . '</div>';
                        }

                        $rows = $assignments->map(static function (ExpedienteUserAssignment $assignment) use ($selectedUser): string {
                            $isSelectedUserAssignment = filled($selectedUser) && (int) $assignment->user_id === (int) $selectedUser;
                            $rowClass = $isSelectedUserAssignment
                                ? 'bg-emerald-50 dark:bg-emerald-950/20 border-b border-emerald-100 dark:border-emerald-900'
                                : 'border-b border-gray-100 dark:border-gray-800';
                            $badge = $isSelectedUserAssignment
                                ? '<span class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">Ya asignado</span>'
                                : '<span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200">Otra asignación</span>';

                            $expedienteId = e($assignment->expediente_id);
                            $obra = e((string) ($assignment->expediente?->nombre_obra ?? 'Sin obra'));
                            $user = e((string) ($assignment->user?->name ?? 'Sin usuario'));
                            $fecha = e($assignment->created_at?->format('d/m/Y H:i') ?? '-');

                            return "
                                <tr class=\"{$rowClass}\">
                                    <td class=\"w-56 px-4 py-3 align-top font-medium whitespace-nowrap\">{$expedienteId}</td>
                                    <td class=\"w-[45%] px-4 py-3 align-top\">{$obra}</td>
                                    <td class=\"w-56 px-4 py-3 align-top\">{$user}</td>
                                    <td class=\"w-40 px-4 py-3 align-top whitespace-nowrap\">{$fecha}</td>
                                    <td class=\"w-40 px-4 py-3 align-top whitespace-nowrap\">{$badge}</td>
                                </tr>
                            ";
                        })->implode('');

                        return new HtmlString(
                            $notice
                            . '<div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700" style="width: 100%; max-width: none;">'
                            . '<table class="w-full text-sm" style="min-width: 1500px; table-layout: fixed;">'
                            . '<thead class="bg-gray-50 dark:bg-gray-900/40">'
                            . '<tr>'
                            . '<th class="w-56 px-4 py-3 text-left align-top whitespace-nowrap">Expediente</th>'
                            . '<th class="px-4 py-3 text-left align-top">Obra</th>'
                            . '<th class="w-56 px-4 py-3 text-left align-top whitespace-nowrap">Usuario</th>'
                            . '<th class="w-40 px-4 py-3 text-left align-top whitespace-nowrap">Fecha</th>'
                            . '<th class="w-40 px-4 py-3 text-left align-top whitespace-nowrap">Estado</th>'
                            . '</tr>'
                            . '</thead>'
                            . '<tbody>' . $rows . '</tbody>'
                            . '</table>'
                            . '</div>'
                        );
                    }),
                TextInput::make('assigned_by')
                    ->label('Asignado por')
                    ->default(static fn (): ?int => Auth::id())
                    ->numeric()
                    ->hidden(),
                TextInput::make('team_id')
                    ->label('Team')
                    ->numeric()
                    ->hidden(),
            ]);
    }

    /** Muestra el histórico de asignaciones con filtros por año y usuario. */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('expediente_id')
                    ->label('Expediente')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('expediente.nombre_obra')
                    ->label('Obra')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('user.name')
                    ->label('Usuario tramitador')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Fecha asignacion')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('ao_ejecucion')
                    ->label('Año')
                    ->options(static fn (): array => Expediente::query()
                        ->whereNotNull('ao_ejecucion')
                        ->select('ao_ejecucion')
                        ->distinct()
                        ->orderByDesc('ao_ejecucion')
                        ->pluck('ao_ejecucion', 'ao_ejecucion')
                        ->toArray())
                    ->query(static fn (Builder $query, array $data): Builder => $query
                        ->when(
                            filled($data['value'] ?? null),
                            static fn (Builder $query): Builder => $query->whereHas(
                                'expediente',
                                static fn (Builder $expedienteQuery): Builder => $expedienteQuery->where('ao_ejecucion', (string) $data['value'])
                            )
                        )),
                SelectFilter::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpedienteUserAssignments::route('/'),
            'create' => CreateExpedienteUserAssignment::route('/create'),
            'edit' => EditExpedienteUserAssignment::route('/{record}/edit'),
        ];
    }
}
