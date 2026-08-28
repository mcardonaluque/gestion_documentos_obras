{{-- resources/views/custom-tabs-menu.blade.php --}}
<style>
    /* ===== ESTILOS PARA TABS CON SUBMENÚS ===== */

       /* ===== PERSONALIZACIÓN COMPLETA DE TEXTOS ===== */

    /* 1. TÍTULO PRINCIPAL */
    /* Reducir tamaño del select de paginación SOLO dentro de widgets */
    /* Centrar el botón del login en Filament */
.filament-login-page form {
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
}

.filament-login-page form .fi-btn,
.filament-login-page form button[type="submit"] {
    margin-left: auto !important;
    margin-right: auto !important;
    display: block !important;
}

/* Botones primarios en recursos con color corporativo */
.fi-main .fi-btn.fi-color-primary,
.fi-main .fi-ac-btn-action.fi-color-primary,
.fi-main .fi-icon-btn.fi-color-primary {
    background-color: rgb(28, 20, 99) !important;
    border-color: rgb(28, 20, 99) !important;
    color: #ffffff !important;
}

.fi-main .fi-btn.fi-color-primary:hover,
.fi-main .fi-ac-btn-action.fi-color-primary:hover,
.fi-main .fi-icon-btn.fi-color-primary:hover {
    background-color: rgb(36, 26, 120) !important;
    border-color: rgb(36, 26, 120) !important;
}

.fi-widget .fi-ta-pagination select,
.fi-widget .fi-ta-pagination .fi-select-input,
.fi-widget .fi-ta-pagination .fi-input {
    font-size: 0.75rem !important;
    padding: 0.25rem 0.5rem !important;
    height: 1.75rem !important;
    min-height: 1.75rem !important;
    line-height: 1.75rem !important;
    border-radius: 6px !important;
}

    .custom-tabs-container,
    .custom-tabs-container * {
        box-sizing: border-box;
    }
    .custom-tabs-container {
        background: linear-gradient(135deg, rgb(28, 20, 99), #3730a3);
        padding: 0.5rem 1rem;
        border-bottom: 2px solid #3730a3;
    }
     .panel-background {
    position: fixed;
    inset: 0;
    z-index: -1;
    background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
    }
.fi-topbar nav {
    display: flex;
    align-items: center;
    width: 100% !important;
}

.fi-topbar [x-persist="topbar.end"] {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

    .fi-modal,
    .fi-modal-window,
    .fi-modal-overlay,
    dialog,
    [role="dialog"] {
        z-index: 10000 !important;
    }

    .fi-modal > .fi-modal-close-overlay,
    .fi-modal-overlay,
    .fi-modal-close-overlay,
    dialog::backdrop {
        background-color: rgba(15, 23, 42, 0.88) !important;
        backdrop-filter: blur(6px);
    }

    .fi-modal > .fi-modal-close-overlay {
        opacity: 1 !important;
        z-index: 9998 !important;
    }

    .fi-modal > .fi-modal-window-ctn {
        z-index: 9999 !important;
    }

    body:has(.fi-modal.fi-modal-open) .fi-topbar,
    body:has(.fi-modal.fi-modal-open) .custom-tabs-container {
        opacity: 0 !important;
        pointer-events: none !important;
    }



    .fi-header-heading {
        font-size: 1.5rem !important;
        font-weight: 900 !important;
        color: rgb(28, 20, 99) !important;
        text-transform: uppercase;
        letter-spacing: 0.1em;
       /* background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;*/
        margin-bottom: 0.5rem;
        padding: 0.5rem 0;
    }

    .dark .fi-header-heading {
        color: #f9fafb !important;
    }

    /* 2. SUBTÍTULO */
    .fi-header-subheading {
        font-size: 1.35rem !important;
        font-weight: 600 !important;
        color: rgb(28, 20, 99) !important;
        font-style: italic;
        margin-bottom: 2rem;
        border-left: 4px solid rgb(28, 20, 99);
        padding-left: 1rem;
    }

    .dark .fi-header-subheading {
        color: #e5e7eb !important;
        border-left-color: #818cf8;
    }

    /* 3. TÍTULOS DE WIDGETS */
    .fi-widget-header-heading {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: rgb(28, 20, 99) !important;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgb(28, 20, 99);
    }

    .dark .fi-widget-header-heading {
        color: #f9fafb !important;
        border-bottom-color: #374151;
    }

    /* 4. TÍTULOS DE CARDS */
    .fi-card-header-heading {
        font-size: 1.3rem !important;
        font-weight: 600 !important;
        color: rgb(28, 20, 99) !important;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .dark .fi-card-header-heading {
        color: #e5e7eb !important;
    }

    /* 5. TEXTOS EN STATS */
    .fi-stats-overview-stat-label {
        font-size: 1.1rem !important;
        font-weight: 600 !important;
        color: rgb(28, 20, 99) !important;
    }

    .dark .fi-stats-overview-stat-label {
        color: #d1d5db !important;
    }

    .fi-stats-overview-stat-value {
        font-size: 2rem !important;
        font-weight: 800 !important;
        color: rgb(28, 20, 99) !important;
    }

    .dark .fi-stats-overview-stat-value {
        color: #818cf8 !important;
    }

    /* 6. RESPONSIVE */
    @media (max-width: 768px) {
        .fi-header-heading {
            font-size: 2rem !important;
        }

        .fi-header-subheading {
            font-size: 1.1rem !important;
        }

        .fi-widget-header-heading {
            font-size: 1.3rem !important;
        }
    }

    .dark .custom-tabs-container {
        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
        border-bottom-color: #4338ca;
    }

    .custom-tabs-nav {
        display: flex;
        gap: 0.25rem;
        align-items: center;
        position: relative;
        z-index: 100;
    }

    .custom-tab {
        position: relative;
        padding: 0.75rem 1.25rem;
        border: 2px solid transparent;
        border-radius: 8px;
        color: #ffffff;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;

        cursor: pointer;
        transition: all 0.3s ease;
    }

    .custom-tab::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        height: 10px;
    }

    .custom-tab:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
        color: #ffffff;
    }

    .custom-tab-active {
        background: rgba(255, 255, 255, 0.2) !important;
        border-color: #818cf8 !important;
        color: #ffffff !important;
        font-weight: 600;
    }

    /* Submenús */
    .custom-submenu {
        position: absolute;
        top: calc(100% - 2px);
        left: 0;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem;
        min-width: 200px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 1000;
        margin-top: 0;
    }

    .dark .custom-submenu {
        background: #1f2937;
        border-color: #374151;
    }

    .custom-submenu-item {
        display: block;
        padding: 0.75rem 1rem;
        border-radius: 6px;
        color: #374151;
        text-decoration: none;
        transition: all 0.2s ease;
        margin: 0.25rem 0;
    }

    .dark .custom-submenu-item {
        color: #e2e8f0;
    }

    .custom-submenu-item:hover {
        background: #f1f5f9;
        color: #1e40af;
    }

    .dark .custom-submenu-item:hover {
        background: #374151;
        color: #ffffff;
    }

    .custom-submenu-active {
        background: #dbeafe;
        color: #1e40af;
        font-weight: 500;
    }

    .dark .custom-submenu-active {
        background: #1e40af;
        color: #ffffff;
    }

    /* Mostrar submenú al hover */
    .custom-tab:hover .custom-submenu {
        display: block;
    }

    .custom-tab:focus-within .custom-submenu {
        display: block;
    }

    /* Mantener submenú visible cuando está activo */
    .custom-tab-active .custom-submenu {
        display: block !important;
    }

</style>
@auth
    @php

        $user = auth()->user();

        // Usar el team ACTUAL del usuario, no el primero

       //$team = \Filament\Facades\Filament::getTenant();
       // Si no hay team, usar un slug por defecto
       //$municipioSlug = $team?->name ?? 'default';//$team ? \Illuminate\Support\Str::slug($team->name) : 'default';
    @endphp
@if($user)
<div class="custom-tabs-container">
    <nav class="custom-tabs-nav">
        <div class="custom-tab" data-tab="Home">


                <a href="/obras/" class="custom-menu-item">
                     🏠 Escritorio
                </a>
        </div>
           <!-- Tab Inicio -->
           <div class="custom-tab" data-tab="inicio">
            🚀 Aprobación de Obras
            <div class="custom-submenu">
                <a href="/obras/datos-de-inicio-de-obras" class="custom-submenu-item">
                    🏗️ Inicio de Obras
                </a>

                    <a href="/obras/prorrogas-ejecucion" class="custom-submenu-item">
                        📅 Prórrogas y plazos
                    </a>
            </div>
        </div>
        <!-- Tab Proyectos -->
        <div class="custom-tab" data-tab="proyectos">
             Proyectos
            <div class="custom-submenu">
                <a href="/obras/proyectos" class="custom-submenu-item">
                    📋 Proyectos
                </a>
                <a href="/obras/obras-pendientes-proyecto" class="custom-submenu-item">
                    📊 Obras Pendientes de Proyectos
                </a>
            </div>
        </div>
           <!-- Tab Inicio -->
           <div class="custom-tab" data-tab="Cesión">
            💸 Cesión/Contratación
            <div class="custom-submenu">
                <a href="/obras/obra-cedidas" class="custom-submenu-item">
                    📦 Cesión de Obras
                </a>
                 <a href="/obras/pendiente-contratacion-obras" class="custom-submenu-item">
                    📄 Contratación de Obras
                </a>
            </div>
        </div>


        <!-- Tab Ejecución -->
        <div class="custom-tab" data-tab="ejecucion">
            🏗️ Ejecución
            <div class="custom-submenu">
           {{-- -  <a href="/obras/{{ $municipioSlug }}/datos-ejecucion-obras" class="custom-submenu-item"> --}}
                <a href="/obras/datos-ejecucion-obras" class="custom-submenu-item">
                    📈 Datos de Ejecución
                </a>
                <a href="/obras/certificaciones" class="custom-submenu-item">
                    📝 Certificaciones
                </a>
                <a href="/obras/actadereplanteos" class="custom-submenu-item">
                    👷‍♀️ Acta de replanteo
                </a>
                <a href="/obras/plan-sses" class="custom-submenu-item">
                    ⛑️ Planes de Seguridad y Salud
                </a>
                <a href="/obras/actaderecepcions" class="custom-submenu-item">
                    🖹 Acta de recepcion
                </a>
                      <a href="/obras/prorrogas-ejecucion" class="custom-submenu-item">
                          📅 Prórrogas
                </a>
            </div>
        </div>
        <div class="custom-tab" data-tab="Expedientes">
            🗄 Expedientes
            <div class="custom-submenu">
                <a href="/obras/expedientes" class="custom-submenu-item">
                    📋 Expedientes
                </a>
                <a href="/obras/documentoexpedientes" class="custom-submenu-item">
                    🗒️ Documentos de Expedientes
                </a>
            </div>
        </div>
        <div class="custom-tab" data-tab="Documentos">
            📁Documentación
            <div class="custom-submenu">
                <a href="/obras/expedientes" class="custom-submenu-item">
                    📋 Documentos de Incio
                </a>
                <a href="/obras/documentoexpedientes" class="custom-submenu-item">
                    🗒️ Documentos de Proyectos
                </a>
                <a href="/obras/documentoexpedientes" class="custom-submenu-item">
                    🗒️ Documentos de Cesión
                </a>
                <a href="/obras/documentoexpedientes" class="custom-submenu-item">
                    🗒️ Documentos de Ejecución
                </a>
                <a href="/obras/documentoexpedientes" class="custom-submenu-item">
                    🗒️ Documentos de Justificación
                </a>
            </div>
        </div>
        <div class="custom-tab" data-tab="Cesión">
            📢 Tareas
            <div class="custom-submenu">
                <a href="/obras/notifications" class="custom-submenu-item">
                    📢 Notificaciones
                </a>
                @if(auth()->user()->hasRole('super_admin') || auth()->user()->can('page_InformeExpedientesAgrupado'))
                <a href="/obras/informe-expedientes-agrupado" class="custom-submenu-item">
                    📊 Informes
                </a>
                @endif
                @if(auth()->user()->hasRole('super_admin'))
                <a href="/obras/hitos-fechas-materializados" class="custom-submenu-item">
                    🗓️ Hitos materializados
                </a>
                @endif
                <a href="/obras/contratistas" class="custom-submenu-item">
                    👷 Contratistas
                </a>
                 <a href="/obras/normativa-ppacs" class="custom-submenu-item">
                        📜 Normativa PPAC
                    </a>
                @if(auth()->user()->hasRole('super_admin') || auth()->user()->hasAnyRole(['Abogado', 'abogado']))
                <a href="/obras/expediente-user-assignments" class="custom-submenu-item">
                    👤 Asignación de Expedientes
                </a>
                @endif

            </div>

        </div>
    </nav>
</div>
@endauth
@endif
