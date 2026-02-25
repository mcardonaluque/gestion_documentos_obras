<?php

namespace App\Filament\Resources\DateValidationRules\Pages;

use App\Filament\Resources\DateValidationRules\DateValidationRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDateValidationRule extends CreateRecord
{
    protected static string $resource = DateValidationRuleResource::class;
}
