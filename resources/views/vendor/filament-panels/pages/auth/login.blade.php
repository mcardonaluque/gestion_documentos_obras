<style>
    /* BEGIN: login-centered-button (reversible) */
    .fi-simple-page #form .fi-form-actions {
        display: flex !important;
        justify-content: center !important;
    }

    .fi-simple-page #form .fi-form-actions .fi-ac {
        display: flex !important;
        justify-content: center !important;
        grid-template-columns: none !important;
        width: auto !important;
        margin-inline: auto !important;
    }

    .fi-simple-page #form .fi-form-actions .fi-btn,
    .fi-simple-page #form .fi-form-actions button[type="submit"] {
        width: auto !important;
        margin-inline: auto !important;
        min-width: 10rem;
    }

    .fi-simple-page #form .fi-form-actions .fi-ac-btn-action,
    .fi-simple-page #form .fi-form-actions button[type="submit"].fi-btn {
        background-color: rgb(28, 20, 99) !important;
        border-color: rgb(28, 20, 99) !important;
        color: #fff !important;
        margin-inline: auto !important;
    }

    .fi-simple-page #form .fi-form-actions .fi-ac-btn-action:hover,
    .fi-simple-page #form .fi-form-actions button[type="submit"].fi-btn:hover {
        background-color: rgb(28, 20, 99) !important;
        border-color: rgb(28, 20, 99) !important;
        filter: brightness(1.08);
    }
    /* END: login-centered-button (reversible) */
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

<script>
    // BEGIN: login-centered-button-script (reversible)
    (function () {
        const centerLoginButton = () => {
            const actionsContainer = document.querySelector('.fi-simple-page #form .fi-form-actions');
            const actionsRow = document.querySelector('.fi-simple-page #form .fi-form-actions .fi-ac');
            const submitButton = document.querySelector('.fi-simple-page #form button[type="submit"].fi-btn, .fi-simple-page #form .fi-form-actions .fi-btn');

            if (actionsContainer) {
                actionsContainer.style.display = 'flex';
                actionsContainer.style.justifyContent = 'center';
            }

            if (actionsRow) {
                actionsRow.style.display = 'flex';
                actionsRow.style.justifyContent = 'center';
                actionsRow.style.width = 'auto';
                actionsRow.style.marginInline = 'auto';
            }

            if (submitButton) {
                submitButton.style.marginInline = 'auto';
                submitButton.style.width = 'auto';
            }
        };

        document.addEventListener('DOMContentLoaded', centerLoginButton);
        document.addEventListener('livewire:navigated', centerLoginButton);
        centerLoginButton();
    })();
    // END: login-centered-button-script (reversible)
</script>
