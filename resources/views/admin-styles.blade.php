{{-- resources/views/custom-tabs-menu.blade.php --}}
<style>
    /* ===== ESTILOS PARA TABS CON SUBMENÚS ===== */
    .fi-topbar nav {
        border: 1px solid transparent;
        border-radius: 6px;
        padding: 6px 12px;
        margin: 0 4px;
        transition: all 0.2s;
    }

    /* Hover */
    .fi-topbar nav:hover {
        border-color: #3b82f6; /* azul */
        background-color: #eff6ff;
        color:rgb(28, 20, 99) /* #1d4ed8;*/
    }

    /* Activo */
    .fi-topbar nav[aria-current="page"] {
        border-color: #1d4ed8;
        background-color: #dbeafe;
        font-weight: bold;
        color: #1e3a8a;
    }
    /*#1e40af 0%*/
    .custom-tabs-container {
        position: relative;
        background: linear-gradient(135deg,rgb(28, 20, 99) , #3730a3 100%);
        padding: 0.5rem 1rem;
        border-bottom: 2px solid #3730a3;
        order: 2; /* ← Esto lo coloca después de la topbar */
        /*margin-top: 0; /* ← Elimina cualquier margen superior */
    }
    .fi-main {
        /*display: flex;
        flex-direction: column;*/
    }

    /* Asegurar que la topbar de Filament permanezca arriba */
    .fi-topbar {
       /* order: 1; /* ← Primera posición */
       /* position: relative;
        top: 0;
        margin-top: 0; /* ← Elimina cualquier margen superior */
        z-index: 40; /* ← Un z-index mayor */
        
    }
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* El contenido principal debe crecer */
    .fi-main > div:first-child {
        flex-grow: 1;
    }

    /* Footer de Filament - posición correcta */
    .fi-footer {
        margin-top: auto !important;
        position: relative !important;
        bottom: auto !important;
    }
       /* ===== PERSONALIZACIÓN COMPLETA DE TEXTOS ===== */
    
    /* 1. TÍTULO PRINCIPAL */
    .fi-header-heading {
        font-size: 1.5rem !important;
        font-weight: 900 !important;
        color: #1e40af !important;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        background: linear-gradient(135deg, #1e40af 0%, #3730a3 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
        padding: 0.5rem 0;
    }
    
    .dark .fi-header-heading {
        background: linear-gradient(135deg, #818cf8 0%, #a5b4fc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* 2. SUBTÍTULO */
    .fi-header-subheading {
        font-size: 1.35rem !important;
        font-weight: 400 !important;
        color: #6b7280 !important;
        font-style: italic;
        margin-bottom: 2rem;
        border-left: 4px solid #3b82f6;
        padding-left: 1rem;
    }
    
    .dark .fi-header-subheading {
        color: #9ca3af !important;
        border-left-color: #818cf8;
    }
    
    /* 3. TÍTULOS DE WIDGETS */
    .fi-widget-header-heading {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .dark .fi-widget-header-heading {
        color: #f9fafb !important;
        border-bottom-color: #374151;
    }
    
    /* 4. TÍTULOS DE CARDS */
    .fi-card-header-heading {
        font-size: 1.3rem !important;
        font-weight: 600 !important;
        color: #374151 !important;
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
        color: #4b5563 !important;
    }
    
    .dark .fi-stats-overview-stat-label {
        color: #d1d5db !important;
    }
    
    .fi-stats-overview-stat-value {
        font-size: 2rem !important;
        font-weight: 800 !important;
        color: #1e40af !important;
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
        color: #e0e7ff;
        font-weight: 500;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
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
        top: 100%;
        left: 0;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem;
        min-width: 200px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 1000;
        margin-top: 0.25rem;
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

    /* Mantener submenú visible cuando está activo */
    .custom-tab-active .custom-submenu {
        display: block !important;
    }
</style>

<div class="custom-tabs-container">
    <nav class="custom-tabs-nav">
        <!-- Tab Proyectos -->
        <div class="custom-tab" data-tab="proyectos">
            📁 Proyectos
            <div class="custom-submenu">
                <a href="/obras/proyectos" class="custom-submenu-item">
                    📋 Proyectos
                </a>
                <a href="/obras/fase-de-proyectos.index" class="custom-submenu-item">
                    📊 Fases de Proyectos
                </a>
            </div>
        </div>

        <!-- Tab Inicio -->
        <div class="custom-tab" data-tab="inicio">
            🚀 Inicio
            <div class="custom-submenu">
                <a href="/obras/proyectos" class="custom-submenu-item">
                    🏗️ Inicio de Obras
                </a>
            </div>
        </div>

        <!-- Tab Ejecución -->
        <div class="custom-tab" data-tab="ejecucion">
            ⚡ Ejecución
            <div class="custom-submenu">
                <a href="{route{'datos-ejecucion-obras.index'}}" class="custom-submenu-item">
                    📈 Datos de Ejecución
                </a>
                <a href="/obras/certificaciones.index" class="custom-submenu-item">
                    📝 Certificaciones
                </a>
                <a href="/obras/planss.index" class="custom-submenu-item">
                    🛡️ Planes de Seguridad y Salud
                </a>
               
            </div>
        </div>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.custom-tab');
    const submenus = document.querySelectorAll('.custom-submenu');
    
    // Ocultar todos los submenús inicialmente
    submenus.forEach(menu => menu.style.display = 'none');
    
    // Función para cerrar todos los submenús
    function closeAllSubmenus() {
        tabs.forEach(tab => {
            tab.classList.remove('custom-tab-active');
            const menu = tab.querySelector('.custom-submenu');
            if (menu) menu.style.display = 'none';
        });
    }
    
    // Eventos para los tabs
    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.stopPropagation();
            
            const isActive = this.classList.contains('custom-tab-active');
            closeAllSubmenus();
            
            if (!isActive) {
                this.classList.add('custom-tab-active');
                const menu = this.querySelector('.custom-submenu');
                if (menu) menu.style.display = 'block';
            }
        });
        
        // Mantener submenú visible al hover
        tab.addEventListener('mouseenter', function() {
            closeAllSubmenus();
            this.classList.add('custom-tab-active');
            const menu = this.querySelector('.custom-submenu');
            if (menu) menu.style.display = 'block';
        });
    });
    
    // Cerrar menús al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-tabs-container')) {
            closeAllSubmenus();
        }
    });
    
    // Marcar el tab activo basado en la URL actual
    function setActiveTab() {
        const currentPath = window.location.pathname;
        
        tabs.forEach(tab => {
            const menuItems = tab.querySelectorAll('.custom-submenu-item');
            let isActive = false;
            
            menuItems.forEach(item => {
                if (item.href.includes(currentPath)) {
                    isActive = true;
                    item.classList.add('custom-submenu-active');
                } else {
                    item.classList.remove('custom-submenu-active');
                }
            });
            
            if (isActive) {
                tab.classList.add('custom-tab-active');
                const menu = tab.querySelector('.custom-submenu');
                if (menu) menu.style.display = 'block';
            }
        });
    }
    
    setActiveTab();
});
</script>