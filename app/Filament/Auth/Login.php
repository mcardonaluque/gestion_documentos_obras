<?php

namespace App\Filament\Auth;

use Filament\Actions\Action;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Facades\Filament;
use Filament\Support\Enums\Alignment;
use Throwable;

class Login extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        $channel = app('log')->channel('sqlsrv_diag');
        $panel = Filament::getCurrentOrDefaultPanel();

        $channel->debug('Filament login authenticate start', [
            'panel' => $panel?->getId(),
            'path' => request()->path(),
            'method' => request()->method(),
            'host' => request()->getHost(),
            'default_connection' => config('database.default'),
            'obras_options' => config('database.connections.Obras.options'),
        ]);

        try {
            $response = parent::authenticate();

            $channel->debug('Filament login authenticate end', [
                'panel' => $panel?->getId(),
                'path' => request()->path(),
                'method' => request()->method(),
                'host' => request()->getHost(),
                'authenticated' => Filament::auth()->check(),
            ]);

            return $response;
        } catch (Throwable $exception) {
            $channel->error('Filament login authenticate exception', [
                'panel' => $panel?->getId(),
                'path' => request()->path(),
                'method' => request()->method(),
                'host' => request()->getHost(),
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

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
