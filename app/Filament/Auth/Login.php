<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Support\Enums\Alignment;

class Login extends BaseLogin
{
    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->extraAttributes([
                'style' => 'background-color: rgb(28, 20, 99) !important; border-color: rgb(28, 20, 99) !important; color: #fff !important; justify-self: center !important; margin-left: auto !important; margin-right: auto !important;',
            ]);
    }

    protected function getMultiFactorAuthenticateFormAction(): Action
    {
        return parent::getMultiFactorAuthenticateFormAction()
            ->extraAttributes([
                'style' => 'background-color: rgb(28, 20, 99) !important; border-color: rgb(28, 20, 99) !important; color: #fff !important; justify-self: center !important; margin-left: auto !important; margin-right: auto !important;',
            ]);
    }

    public function getFormActionsAlignment(): string | Alignment
    {
        return Alignment::Center;
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }
}
