<style>
    .fi-simple-main #form .fi-sc-actions {
        justify-content: center !important;
    }

    .fi-simple-main #form .fi-sc-actions .fi-ac {
        display: flex !important;
        justify-content: center !important;
        grid-template-columns: none !important;
        width: auto !important;
        margin-inline: auto !important;
    }

    .fi-simple-main #form .fi-sc-actions .fi-btn,
    .fi-simple-main #form .fi-sc-actions button[type="submit"] {
        min-width: 10rem;
        margin-inline: auto !important;
    }

    .fi-simple-main #form .fi-sc-actions .fi-ac-btn-action,
    .fi-simple-main #form .fi-sc-actions .fi-btn,
    .fi-simple-main #form .fi-sc-actions button[type="submit"].fi-btn {
        background-color: rgb(28, 20, 99) !important;
        border-color: rgb(28, 20, 99) !important;
        color: #fff !important;
    }

    .fi-simple-main #form .fi-sc-actions .fi-ac-btn-action:hover,
    .fi-simple-main #form .fi-sc-actions .fi-btn:hover,
    .fi-simple-main #form .fi-sc-actions button[type="submit"].fi-btn:hover {
        background-color: rgb(28, 20, 99) !important;
        border-color: rgb(28, 20, 99) !important;
        filter: brightness(1.08);
    }
</style>

<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form id="form" wire:submit="authenticate">
        {{ $this->form }}

        <div class="flex justify-center w-full">
            <x-filament-panels::form.actions
                :actions="$this->getCachedFormActions()"
                :alignment="\Filament\Support\Enums\Alignment::Center"
                :full-width="false"
            />
        </div>
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
</x-filament-panels::page.simple>
