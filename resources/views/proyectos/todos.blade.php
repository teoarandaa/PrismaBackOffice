<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Proyectos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo de la empresa" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Todos los Proyectos</h1>
                        <p class="text-gray-600 mt-1">Panel de administración de proyectos</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <input type="text" 
                           id="buscadorProyectos" 
                           placeholder="Buscar por nombre de proyecto o cliente..." 
                           class="w-96 h-[42px] px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="relative inline-block text-left">
                        <button type="button" 
                                onclick="toggleFiltros()"
                                class="h-[42px] inline-flex items-center px-4 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 justify-center">
                            <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtros
                        </button>

                        <div id="menuFiltros" 
                             class="hidden origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50">
                            <div class="p-4 flex justify-between items-center">
                                <h3 class="text-sm font-medium text-gray-900">Filtros</h3>
                                <button onclick="resetFiltros()" 
                                        class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Resetear filtros
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Estado del proyecto</h3>
                                <select id="filtroEstado" 
                                        class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="todos">Todos los estados</option>
                                    <option value="En progreso">En progreso</option>
                                    <option value="Completado">Completados</option>
                                    <option value="Cancelado">Cancelados</option>
                                </select>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Rango fecha de inicio</h3>
                                <div class="flex gap-2 items-center">
                                    <input type="date" id="fechaInicioMin" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <span class="text-gray-500">-</span>
                                    <input type="date" id="fechaInicioMax" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Rango fecha de finalización</h3>
                                <div class="flex gap-2 items-center">
                                    <input type="date" id="fechaFinMin" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <span class="text-gray-500">-</span>
                                    <input type="date" id="fechaFinMax" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Rango fecha de completado</h3>
                                <div class="flex gap-2 items-center">
                                    <input type="date" id="fechaCompletadoMin" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <span class="text-gray-500">-</span>
                                    <input type="date" id="fechaCompletadoMax" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Rango de presupuesto</h3>
                                <div class="flex gap-2 items-center">
                                    <input type="number" id="presupuestoMin" placeholder="Mínimo" 
                                           class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <span class="text-gray-500">-</span>
                                    <input type="number" id="presupuestoMax" placeholder="Máximo"
                                           class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Ordenar por</h3>
                                <select id="filtroOrden" class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="nombre" selected>Nombre del proyecto</option>
                                    <option value="fecha_completado_desc">Fecha completado (más reciente)</option>
                                    <option value="fecha_completado_asc">Fecha completado (más antiguo)</option>
                                    <option value="fecha_fin_desc">Fecha fin (más reciente)</option>
                                    <option value="fecha_fin_asc">Fecha fin (más antiguo)</option>
                                    <option value="fecha_inicio_desc">Fecha inicio (más reciente)</option>
                                    <option value="fecha_inicio_asc">Fecha inicio (más antiguo)</option>
                                    <option value="presupuesto_alto">Mayor presupuesto</option>
                                    <option value="presupuesto_bajo">Menor presupuesto</option>
                                    <option value="cliente">Nombre del cliente</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('clientes.index') }}'" 
                        class="h-[42px] bg-gray-500 hover:bg-gray-700 text-white font-bold px-6 rounded-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </button>
            </div>
        </div>

        <!-- Mensaje de no proyectos -->
        <div id="noProyectos" class="{{ count($proyectos) === 0 ? '' : 'hidden' }}">
            <div class="flex flex-col items-center justify-center py-12">
                <div class="bg-gray-100 rounded-full p-4 mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No hay proyectos</h3>
                <p class="text-gray-500">No hay proyectos registrados en el sistema</p>
            </div>
        </div>

        <!-- Mensaje de no resultados de búsqueda -->
        <div id="noResultados" class="hidden">
            <div class="flex flex-col items-center justify-center py-12">
                <div class="bg-gray-100 rounded-full p-4 mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No se encontraron resultados</h3>
                <p class="text-gray-500">Prueba con otros términos de búsqueda o filtros</p>
            </div>
        </div>

        <!-- Tabla de proyectos -->
        <div id="tablaProyectos" class="{{ count($proyectos) === 0 ? 'hidden' : '' }} bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyecto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presupuesto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Completado</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($proyectos as $proyecto)
                    <tr data-proyecto-id="{{ $proyecto->id }}">
                        <td class="px-6 py-4 whitespace-nowrap" data-tipo="{{ $proyecto->tipo }}">
                            <div class="text-sm font-medium text-gray-900">{{ $proyecto->nombre_proyecto }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($proyecto->descripcion, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</div>
                            <div class="text-sm text-gray-500">{{ $proyecto->cliente->empresa }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $proyecto->estado === 'En progreso' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($proyecto->estado === 'Completado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ $proyecto->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $proyecto->fecha_inicio ? date('d/m/Y', strtotime($proyecto->fecha_inicio)) : 'No definida' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $proyecto->fecha_finalizacion ? date('d/m/Y', strtotime($proyecto->fecha_finalizacion)) : 'No definida' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($proyecto->presupuesto, 2, ',', '.') }} €
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($proyecto->estado === 'Completado')
                                {{ $proyecto->fecha_completado ? date('d/m/Y', strtotime($proyecto->fecha_completado)) : '-' }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('clientes.proyectos.show', [$proyecto->cliente, $proyecto]) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 p-2 rounded-lg transition-colors duration-200"
                                   title="Ver Detalles">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if(auth()->user()->can_edit || auth()->user()->is_admin)
                                    <a href="{{ route('clientes.proyectos.edit', [$proyecto->cliente, $proyecto]) }}" 
                                       class="bg-yellow-600 hover:bg-yellow-700 p-2 rounded-lg transition-colors duration-200"
                                       title="Editar Proyecto">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button onclick="eliminarProyecto({{ $proyecto->id }}, {{ $proyecto->cliente->id }})"
                                            class="bg-red-600 hover:bg-red-700 p-2 rounded-lg transition-colors duration-200"
                                            title="Eliminar Proyecto">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function toggleFiltros() {
            const menu = document.getElementById('menuFiltros');
            menu.classList.toggle('hidden');
            
            // Cerrar el menú al hacer clic fuera
            document.addEventListener('click', function(event) {
                const isClickInside = menu.contains(event.target) || 
                                    event.target.closest('button')?.contains(event.target);
                if (!isClickInside && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            });
        }

        function aplicarFiltros() {
            const rows = document.querySelectorAll('tbody tr');
            const searchTerm = document.getElementById('buscadorProyectos').value.toLowerCase();
            let resultadosEncontrados = false;
            
            rows.forEach(row => {
                const clienteNombre = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const proyectoNombre = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const estado = row.querySelector('td:nth-child(3) span').textContent.trim();
                const fechaTexto = row.querySelector('td:nth-child(4)').textContent.trim();
                const fechaInicioDate = fechaTexto !== 'No definida' ? 
                    new Date(fechaTexto.split('/').reverse().join('-')) : null;
                const presupuesto = parseFloat(row.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.'));
                
                let mostrar = true;

                // Filtro de búsqueda
                if (searchTerm) {
                    mostrar = clienteNombre.includes(searchTerm) || 
                             proyectoNombre.includes(searchTerm);
                }

                // Filtro de estado
                const filtroEstado = document.getElementById('filtroEstado').value;
                if (mostrar && filtroEstado !== 'todos') {
                    mostrar = estado === filtroEstado;
                }

                // Filtro de presupuesto
                const presupuestoMin = parseFloat(document.getElementById('presupuestoMin').value) || 0;
                const presupuestoMax = parseFloat(document.getElementById('presupuestoMax').value);
                if (presupuestoMin > 0 || presupuestoMax) {
                    mostrar = presupuesto >= presupuestoMin && 
                             (!presupuestoMax || presupuesto <= presupuestoMax);
                }

                // Filtros de fecha de inicio
                const fechaInicioMin = document.getElementById('fechaInicioMin').value;
                const fechaInicioMax = document.getElementById('fechaInicioMax').value;
                if (mostrar && fechaInicioDate && (fechaInicioMin || fechaInicioMax)) {
                    if (fechaInicioMin) mostrar = fechaInicioDate >= new Date(fechaInicioMin);
                    if (fechaInicioMax && mostrar) mostrar = fechaInicioDate <= new Date(fechaInicioMax);
                }

                // Filtros de fecha de fin
                const fechaFinTexto = row.querySelector('td:nth-child(5)').textContent.trim();
                const fechaFinDate = fechaFinTexto !== 'No definida' ? new Date(fechaFinTexto.split('/').reverse().join('-')) : null;
                const fechaFinMin = document.getElementById('fechaFinMin').value;
                const fechaFinMax = document.getElementById('fechaFinMax').value;
                if (mostrar && fechaFinDate && (fechaFinMin || fechaFinMax)) {
                    if (fechaFinMin) mostrar = fechaFinDate >= new Date(fechaFinMin);
                    if (fechaFinMax && mostrar) mostrar = fechaFinDate <= new Date(fechaFinMax);
                }

                // Filtros de fecha de completado
                const fechaCompletadoTexto = row.querySelector('td:nth-child(7)').textContent.trim();
                const fechaCompletadoDate = fechaCompletadoTexto !== '-' ? new Date(fechaCompletadoTexto.split('/').reverse().join('-')) : null;
                const fechaCompletadoMin = document.getElementById('fechaCompletadoMin').value;
                const fechaCompletadoMax = document.getElementById('fechaCompletadoMax').value;
                if (mostrar && fechaCompletadoDate && (fechaCompletadoMin || fechaCompletadoMax)) {
                    if (fechaCompletadoMin) mostrar = fechaCompletadoDate >= new Date(fechaCompletadoMin);
                    if (fechaCompletadoMax && mostrar) mostrar = fechaCompletadoDate <= new Date(fechaCompletadoMax);
                }

                row.style.display = mostrar ? '' : 'none';
                if (mostrar) resultadosEncontrados = true;
            });
            
            // Actualizar la visibilidad de la tabla y los mensajes
            const noResultados = document.getElementById('noResultados');
            const noProyectos = document.getElementById('noProyectos');
            const tablaProyectos = document.getElementById('tablaProyectos');
            const hayProyectos = document.querySelectorAll('tbody tr').length > 0;
            
            if (!hayProyectos) {
                // Si no hay proyectos en absoluto
                noProyectos.classList.remove('hidden');
                noResultados.classList.add('hidden');
                tablaProyectos.classList.add('hidden');
            } else if (resultadosEncontrados) {
                // Si hay proyectos y se encontraron resultados
                noProyectos.classList.add('hidden');
                noResultados.classList.add('hidden');
                tablaProyectos.classList.remove('hidden');
            } else {
                // Si hay proyectos pero no se encontraron resultados con los filtros
                noProyectos.classList.add('hidden');
                noResultados.classList.remove('hidden');
                tablaProyectos.classList.add('hidden');
            }
            
            // Ordenar las filas
            const tbody = document.querySelector('tbody');
            const rowsArray = Array.from(rows);
            
            rowsArray.sort((a, b) => {
                const orden = document.getElementById('filtroOrden').value;
                
                switch(orden) {
                    case 'fecha_completado_desc':
                    case 'fecha_completado_asc': {
                        const fechaA = a.querySelector('td:nth-child(7)').textContent.trim();
                        const fechaB = b.querySelector('td:nth-child(7)').textContent.trim();
                        if (fechaA === '-' && fechaB === '-') return 0;
                        if (fechaA === '-') return orden.includes('desc') ? 1 : -1;
                        if (fechaB === '-') return orden.includes('desc') ? -1 : 1;
                        const comparison = new Date(fechaB.split('/').reverse().join('-')) - new Date(fechaA.split('/').reverse().join('-'));
                        return orden.includes('desc') ? comparison : -comparison;
                    }
                    case 'fecha_fin_desc':
                    case 'fecha_fin_asc': {
                        const fechaA = a.querySelector('td:nth-child(5)').textContent.trim();
                        const fechaB = b.querySelector('td:nth-child(5)').textContent.trim();
                        if (fechaA === 'No definida' && fechaB === 'No definida') return 0;
                        if (fechaA === 'No definida') return orden.includes('desc') ? 1 : -1;
                        if (fechaB === 'No definida') return orden.includes('desc') ? -1 : 1;
                        const comparison = new Date(fechaB.split('/').reverse().join('-')) - new Date(fechaA.split('/').reverse().join('-'));
                        return orden.includes('desc') ? comparison : -comparison;
                    }
                    case 'fecha_inicio_desc':
                    case 'fecha_inicio_asc': {
                        const fechaA = a.querySelector('td:nth-child(4)').textContent.trim();
                        const fechaB = b.querySelector('td:nth-child(4)').textContent.trim();
                        if (fechaA === 'No definida' && fechaB === 'No definida') return 0;
                        if (fechaA === 'No definida') return orden.includes('desc') ? 1 : -1;
                        if (fechaB === 'No definida') return orden.includes('desc') ? -1 : 1;
                        const comparison = new Date(fechaB.split('/').reverse().join('-')) - new Date(fechaA.split('/').reverse().join('-'));
                        return orden.includes('desc') ? comparison : -comparison;
                    }
                    case 'presupuesto_alto':
                        return parseFloat(b.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.')) - 
                               parseFloat(a.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.'));
                    case 'presupuesto_bajo':
                        return parseFloat(a.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.')) - 
                               parseFloat(b.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.'));
                    case 'nombre':
                        return a.querySelector('td:nth-child(1)').textContent.localeCompare(b.querySelector('td:nth-child(1)').textContent);
                    case 'cliente':
                        return a.querySelector('td:nth-child(2)').textContent.localeCompare(b.querySelector('td:nth-child(2)').textContent);
                    default:
                        return 0;
                }
            });
            
            rowsArray.forEach(row => tbody.appendChild(row));
        }

        // Eventos para los filtros
        document.getElementById('buscadorProyectos').addEventListener('input', aplicarFiltros);
        document.getElementById('filtroEstado').addEventListener('change', aplicarFiltros);
        document.getElementById('presupuestoMin').addEventListener('input', aplicarFiltros);
        document.getElementById('presupuestoMax').addEventListener('input', aplicarFiltros);
        document.getElementById('fechaInicioMin').addEventListener('change', aplicarFiltros);
        document.getElementById('fechaInicioMax').addEventListener('change', aplicarFiltros);
        document.getElementById('fechaFinMin').addEventListener('change', aplicarFiltros);
        document.getElementById('fechaFinMax').addEventListener('change', aplicarFiltros);
        document.getElementById('fechaCompletadoMin').addEventListener('change', aplicarFiltros);
        document.getElementById('fechaCompletadoMax').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroOrden').addEventListener('change', aplicarFiltros);

        // Aplicar filtros iniciales
        aplicarFiltros();

        function resetFiltros() {
            document.getElementById('buscadorProyectos').value = '';
            document.getElementById('filtroEstado').value = 'todos';
            document.getElementById('presupuestoMin').value = '';
            document.getElementById('presupuestoMax').value = '';
            document.getElementById('fechaInicioMin').value = '';
            document.getElementById('fechaInicioMax').value = '';
            document.getElementById('fechaFinMin').value = '';
            document.getElementById('fechaFinMax').value = '';
            document.getElementById('fechaCompletadoMin').value = '';
            document.getElementById('fechaCompletadoMax').value = '';
            document.getElementById('filtroOrden').value = 'nombre';
            aplicarFiltros();
        }

        function eliminarProyecto(proyectoId, clienteId) {
            if (confirm('¿Estás seguro de que deseas eliminar este proyecto?')) {
                const token = document.querySelector('meta[name="csrf-token"]').content;

                fetch(`/clientes/${clienteId}/proyectos/${proyectoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            console.error('Error response:', text);
                            throw new Error('Error al eliminar el proyecto');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Éxito:', data);
                    alert('Proyecto eliminado correctamente');
                    const fila = document.querySelector(`tr[data-proyecto-id="${proyectoId}"]`);
                    if (fila) {
                        fila.remove();
                    }
                })
                .catch(error => {
                    console.error('Error detallado:', error);
                    alert('Error al eliminar el proyecto: ' + error.message);
                });
            }
        }
    </script>
</body>
</html> 