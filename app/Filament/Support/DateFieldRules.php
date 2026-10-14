<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Filament\Forms\Components\DateTimePicker;

final class DateFieldRules
{
    /** Valida el campo con las reglas de fecha al perder el foco (requiere NotifiesAndValidatesDates en la página). */
    public static function apply(DateTimePicker $field): DateTimePicker
    {
        return $field
            ->live(onBlur: true)
            ->afterStateUpdated(static function ($livewire, DateTimePicker $component): void {
                if (method_exists($livewire, 'validateDateFieldOnBlur')) {
                    $livewire->validateDateFieldOnBlur($component->getName());
                }
            });
    }
}
