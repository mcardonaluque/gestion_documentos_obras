@php
    $selectedUserName = $selectedUserName ?? null;
    $tableId = 'assignments-table-' . uniqid();
    $searchId = $tableId . '-search';
    $pageSizeId = $tableId . '-page-size';
    $paginationId = $tableId . '-pagination';
@endphp

@if(filled($selectedUser))
    <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-950/30 dark:text-emerald-200">
        Se resaltan en verde las asignaciones ya realizadas para {{ $selectedUserName ?? 'usuario seleccionado' }}.
    </div>
@endif

<div class="mb-3 flex flex-wrap items-end justify-between gap-3">
    <div class="min-w-[18rem] flex-1">
        <label for="{{ $searchId }}" class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">Buscar rápido</label>
        <input
            id="{{ $searchId }}"
            type="search"
            placeholder="Buscar por expediente, obra o usuario"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm outline-none transition focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:focus:border-emerald-700 dark:focus:ring-emerald-900/40"
        >
    </div>

    <div class="w-full sm:w-auto">
        <label for="{{ $pageSizeId }}" class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-300">Tamaño de página</label>
        <select
            id="{{ $pageSizeId }}"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm outline-none transition focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:focus:border-emerald-700 dark:focus:ring-emerald-900/40"
        >
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
        </select>
    </div>
</div>

<div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700" style="width: 100%; max-width: none;">
    <table id="{{ $tableId }}" class="w-full text-sm" style="min-width: 1200px; table-layout: fixed;">
        <thead class="bg-gray-50 dark:bg-gray-900/40">
            <tr>
                <th class="w-56 px-4 py-3 text-left align-top whitespace-nowrap">
                    <button type="button" class="font-semibold hover:underline" data-sort="expediente">Expediente</button>
                </th>
                <th class="px-4 py-3 text-left align-top">
                    <button type="button" class="font-semibold hover:underline" data-sort="obra">Obra</button>
                </th>
                <th class="w-56 px-4 py-3 text-left align-top whitespace-nowrap">
                    <button type="button" class="font-semibold hover:underline" data-sort="usuario">Usuario</button>
                </th>
                <th class="w-40 px-4 py-3 text-left align-top whitespace-nowrap">
                    <button type="button" class="font-semibold hover:underline" data-sort="fecha">Fecha</button>
                </th>
                <th class="w-40 px-4 py-3 text-left align-top whitespace-nowrap">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assignments as $assignment)
                @php
                    $isSelectedUserAssignment = filled($selectedUser) && (int) $assignment->user_id === (int) $selectedUser;
                    $obraText = (string) ($assignment->expediente?->nombre_obra ?? 'Sin obra');
                    $userText = (string) ($assignment->user?->name ?? 'Sin usuario');
                    $timestamp = (int) ($assignment->created_at?->timestamp ?? 0);
                @endphp
                <tr
                    class="{{ $isSelectedUserAssignment ? 'bg-emerald-50 dark:bg-emerald-950/20 border-b border-emerald-100 dark:border-emerald-900' : 'border-b border-gray-100 dark:border-gray-800' }}"
                    data-expediente="{{ strtolower((string) $assignment->expediente_id) }}"
                    data-obra="{{ strtolower($obraText) }}"
                    data-usuario="{{ strtolower($userText) }}"
                    data-fecha="{{ $timestamp }}"
                    data-search="{{ strtolower((string) $assignment->expediente_id . ' ' . $obraText . ' ' . $userText) }}"
                >
                    <td class="w-56 px-4 py-3 align-top font-medium whitespace-nowrap">{{ $assignment->expediente_id }}</td>
                    <td class="w-[45%] px-4 py-3 align-top">{{ $obraText }}</td>
                    <td class="w-56 px-4 py-3 align-top">{{ $userText }}</td>
                    <td class="w-40 px-4 py-3 align-top whitespace-nowrap">{{ $assignment->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                    <td class="w-40 px-4 py-3 align-top whitespace-nowrap">
                        @if($isSelectedUserAssignment)
                            <span class="inline-flex rounded-full bg-emerald-100 px-2 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-200">Ya asignado</span>
                        @else
                            <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-200">Otra asignación</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div id="{{ $paginationId }}" class="mt-3 flex items-center justify-between gap-3 text-sm text-gray-700 dark:text-gray-300">
    <div data-role="summary"></div>
    <div class="flex items-center gap-2" data-role="controls"></div>
</div>

<script>
    (() => {
        const table = document.getElementById(@json($tableId));
        const searchInput = document.getElementById(@json($searchId));
        const pageSizeSelect = document.getElementById(@json($pageSizeId));
        const pagination = document.getElementById(@json($paginationId));

        if (!table || !searchInput || !pageSizeSelect || !pagination) {
            return;
        }

        const tbody = table.querySelector('tbody');
        const sortButtons = table.querySelectorAll('[data-sort]');
        const allRows = Array.from(tbody.querySelectorAll('tr'));

        let currentSort = 'fecha';
        let currentDir = 'desc';
        let currentPage = 1;
        let currentPageSize = Number(pageSizeSelect.value || 10);
        let filteredRows = [...allRows];

        const compare = (a, b) => {
            const av = a.dataset[currentSort] || '';
            const bv = b.dataset[currentSort] || '';

            if (currentSort === 'fecha') {
                const ai = Number(av);
                const bi = Number(bv);
                return currentDir === 'asc' ? ai - bi : bi - ai;
            }

            if (av < bv) {
                return currentDir === 'asc' ? -1 : 1;
            }

            if (av > bv) {
                return currentDir === 'asc' ? 1 : -1;
            }

            return 0;
        };

        const renderPagination = (totalPages, totalRows) => {
            const summary = pagination.querySelector('[data-role="summary"]');
            const controls = pagination.querySelector('[data-role="controls"]');

            const start = totalRows === 0 ? 0 : ((currentPage - 1) * currentPageSize) + 1;
            const end = Math.min(currentPage * currentPageSize, totalRows);

            summary.textContent = `Mostrando ${start}-${end} de ${totalRows} asignaciones`;
            controls.innerHTML = '';

            const makeButton = (label, page, disabled = false, active = false) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = label;
                button.disabled = disabled;
                button.className = [
                    'rounded-md',
                    'border',
                    'px-2.5',
                    'py-1',
                    'text-xs',
                    active
                        ? 'border-emerald-300 bg-emerald-50 text-emerald-700 dark:border-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200'
                        : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800'
                ].join(' ');

                button.addEventListener('click', () => {
                    currentPage = page;
                    render();
                });

                controls.appendChild(button);
            };

            makeButton('Anterior', Math.max(1, currentPage - 1), currentPage === 1);

            for (let page = 1; page <= totalPages; page++) {
                makeButton(String(page), page, false, page === currentPage);
            }

            makeButton('Siguiente', Math.min(totalPages, currentPage + 1), currentPage === totalPages);
        };

        const render = () => {
            filteredRows.sort(compare);

            const totalRows = filteredRows.length;
            const totalPages = Math.max(1, Math.ceil(totalRows / currentPageSize));

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            const startIndex = (currentPage - 1) * currentPageSize;
            const endIndex = startIndex + currentPageSize;

            allRows.forEach((row) => {
                row.style.display = 'none';
            });

            filteredRows.forEach((row, index) => {
                row.style.display = index >= startIndex && index < endIndex ? '' : 'none';
            });

            renderPagination(totalPages, totalRows);
        };

        const applyFilters = () => {
            const term = (searchInput.value || '').trim().toLowerCase();

            if (!term) {
                filteredRows = [...allRows];
            } else {
                filteredRows = allRows.filter((row) => (row.dataset.search || '').includes(term));
            }

            currentPage = 1;
            render();
        };

        sortButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const nextSort = button.dataset.sort;

                if (!nextSort) {
                    return;
                }

                if (nextSort === currentSort) {
                    currentDir = currentDir === 'asc' ? 'desc' : 'asc';
                } else {
                    currentSort = nextSort;
                    currentDir = nextSort === 'fecha' ? 'desc' : 'asc';
                }

                currentPage = 1;
                render();
            });
        });

        pageSizeSelect.addEventListener('change', () => {
            const nextSize = Number(pageSizeSelect.value || 10);
            currentPageSize = [10, 25, 50].includes(nextSize) ? nextSize : 10;
            currentPage = 1;
            render();
        });

        searchInput.addEventListener('input', () => {
            applyFilters();
        });

        applyFilters();
    })();
</script>
