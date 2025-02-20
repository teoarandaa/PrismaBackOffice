<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Proyectos de {{ $cliente->nombre }} {{ $cliente->apellido }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo de la empresa" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Proyectos del Cliente</h1>
                        <p class="text-gray-600 mt-1">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                        <p class="text-gray-500 text-sm">{{ $cliente->email }}</p>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <input type="text" 
                           id="buscadorProyectos" 
                           placeholder="Buscar por nombre de proyecto..." 
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
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Rango de fechas</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-xs text-gray-500">Fecha de inicio</label>
                                        <div class="flex gap-2 items-center mt-1">
                                            <input type="date" id="fechaInicioMin" class="w-full px-3 py-2 rounded-md border border-gray-300">
                                            <span class="text-gray-500">-</span>
                                            <input type="date" id="fechaInicioMax" class="w-full px-3 py-2 rounded-md border border-gray-300">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500">Finalización prevista</label>
                                        <div class="flex gap-2 items-center mt-1">
                                            <input type="date" id="fechaFinMin" class="w-full px-3 py-2 rounded-md border border-gray-300">
                                            <span class="text-gray-500">-</span>
                                            <input type="date" id="fechaFinMax" class="w-full px-3 py-2 rounded-md border border-gray-300">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-500">Fecha de completado</label>
                                        <div class="flex gap-2 items-center mt-1">
                                            <input type="date" id="fechaCompletadoMin" class="w-full px-3 py-2 rounded-md border border-gray-300">
                                            <span class="text-gray-500">-</span>
                                            <input type="date" id="fechaCompletadoMax" class="w-full px-3 py-2 rounded-md border border-gray-300">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Rango de presupuesto</h3>
                                <div class="flex gap-2 items-center">
                                    <input type="number" id="presupuestoMin" placeholder="Mínimo" 
                                           class="w-full px-3 py-2 rounded-md border border-gray-300">
                                    <span class="text-gray-500">-</span>
                                    <input type="number" id="presupuestoMax" placeholder="Máximo"
                                           class="w-full px-3 py-2 rounded-md border border-gray-300">
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Ordenar por</h3>
                                <select id="filtroOrden" 
                                        class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="nombre_asc">Nombre (A-Z)</option>
                                    <option value="nombre_desc">Nombre (Z-A)</option>
                                    <option value="fecha_inicio_asc">Fecha inicio (más antigua)</option>
                                    <option value="fecha_inicio_desc">Fecha inicio (más reciente)</option>
                                    <option value="fecha_fin_asc">Finalización prevista (más antigua)</option>
                                    <option value="fecha_fin_desc">Finalización prevista (más reciente)</option>
                                    <option value="fecha_completado_asc">Fecha completado (más antigua)</option>
                                    <option value="fecha_completado_desc">Fecha completado (más reciente)</option>
                                    <option value="presupuesto_asc">Presupuesto (menor a mayor)</option>
                                    <option value="presupuesto_desc">Presupuesto (mayor a menor)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if(auth()->user()->can_edit || auth()->user()->is_admin)
                        <a href="{{ route('clientes.proyectos.create', $cliente) }}" 
                           class="h-[42px] bg-blue-500 hover:bg-blue-700 text-white font-bold px-6 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Nuevo Proyecto
                        </a>
                    @endif
                    <button onclick="window.location.href='{{ route('clientes.index') }}'" 
                            class="h-[42px] bg-gray-500 hover:bg-gray-700 text-white font-bold px-6 rounded-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
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
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Nombre</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Estado</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Fecha Inicio</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Finalización prevista</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Fecha Completado</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Presupuesto</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($proyectos as $proyecto)
                    <tr data-proyecto-id="{{ $proyecto->id }}" class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                            <div class="text-sm font-medium text-gray-900">{{ $proyecto->nombre_proyecto }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($proyecto->descripcion, 50) }}</div>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $proyecto->estado === 'En progreso' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($proyecto->estado === 'Completado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ $proyecto->estado }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                            {{ $proyecto->fecha_inicio ? date('d/m/Y', strtotime($proyecto->fecha_inicio)) : 'No definida' }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                            {{ $proyecto->fecha_finalizacion ? date('d/m/Y', strtotime($proyecto->fecha_finalizacion)) : 'No definida' }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                            @if($proyecto->estado === 'Completado')
                                {{ date('d/m/Y', strtotime($proyecto->updated_at)) }}
                                <div class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($proyecto->updated_at)->diffForHumans() }}
                                </div>
                            @else
                                <span class="text-gray-400">No completado</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                            €{{ number_format($proyecto->presupuesto, 2, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('clientes.proyectos.show', [$cliente, $proyecto]) }}" 
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
                                    <a href="{{ route('clientes.proyectos.edit', [$cliente, $proyecto]) }}" 
                                       class="bg-yellow-600 hover:bg-yellow-700 p-2 rounded-lg transition-colors duration-200"
                                       title="Editar Proyecto">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <button onclick="eliminarProyecto({{ $proyecto->id }})"
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
            const searchTerm = document.getElementById('buscadorProyectos').value.toLowerCase();
            const filtroEstado = document.getElementById('filtroEstado').value;
            const fechaInicioMin = document.getElementById('fechaInicioMin').value;
            const fechaInicioMax = document.getElementById('fechaInicioMax').value;
            const fechaFinMin = document.getElementById('fechaFinMin').value;
            const fechaFinMax = document.getElementById('fechaFinMax').value;
            const fechaCompletadoMin = document.getElementById('fechaCompletadoMin').value;
            const fechaCompletadoMax = document.getElementById('fechaCompletadoMax').value;
            const presupuestoMin = parseFloat(document.getElementById('presupuestoMin').value) || 0;
            const presupuestoMax = parseFloat(document.getElementById('presupuestoMax').value) || Infinity;
            const filtroOrden = document.getElementById('filtroOrden').value;

            const rows = document.querySelectorAll('tbody tr');
            let resultadosEncontrados = false;

            const rowsArray = Array.from(rows);

            rowsArray.forEach(row => {
                const proyectoData = JSON.parse(row.dataset.proyecto);
                let mostrar = true;

                // Filtro de búsqueda por nombre
                if (searchTerm && !proyectoData.nombre.toLowerCase().includes(searchTerm)) {
                    mostrar = false;
                }

                // Filtro por estado
                if (filtroEstado !== 'todos' && proyectoData.estado !== filtroEstado) {
                    mostrar = false;
                }

                // Filtros de fechas
                if (fechaInicioMin && new Date(proyectoData.fecha_inicio) < new Date(fechaInicioMin)) mostrar = false;
                if (fechaInicioMax && new Date(proyectoData.fecha_inicio) > new Date(fechaInicioMax)) mostrar = false;
                if (fechaFinMin && new Date(proyectoData.fecha_finalizacion) < new Date(fechaFinMin)) mostrar = false;
                if (fechaFinMax && new Date(proyectoData.fecha_finalizacion) > new Date(fechaFinMax)) mostrar = false;
                if (fechaCompletadoMin && new Date(proyectoData.fecha_completado) < new Date(fechaCompletadoMin)) mostrar = false;
                if (fechaCompletadoMax && new Date(proyectoData.fecha_completado) > new Date(fechaCompletadoMax)) mostrar = false;

                // Filtro de presupuesto
                if (proyectoData.presupuesto < presupuestoMin || proyectoData.presupuesto > presupuestoMax) {
                    mostrar = false;
                }

                row.style.display = mostrar ? '' : 'none';
                if (mostrar) resultadosEncontrados = true;
            });

            // Ordenación
            rowsArray.sort((a, b) => {
                const dataA = JSON.parse(a.dataset.proyecto);
                const dataB = JSON.parse(b.dataset.proyecto);

                switch(filtroOrden) {
                    case 'nombre_asc':
                        return dataA.nombre.localeCompare(dataB.nombre);
                    case 'nombre_desc':
                        return dataB.nombre.localeCompare(dataA.nombre);
                    case 'fecha_inicio_asc':
                        return new Date(dataA.fecha_inicio) - new Date(dataB.fecha_inicio);
                    case 'fecha_inicio_desc':
                        return new Date(dataB.fecha_inicio) - new Date(dataA.fecha_inicio);
                    case 'fecha_fin_asc':
                        return new Date(dataA.fecha_finalizacion) - new Date(dataB.fecha_finalizacion);
                    case 'fecha_fin_desc':
                        return new Date(dataB.fecha_finalizacion) - new Date(dataA.fecha_finalizacion);
                    case 'fecha_completado_asc':
                        return new Date(dataA.fecha_completado) - new Date(dataB.fecha_completado);
                    case 'fecha_completado_desc':
                        return new Date(dataB.fecha_completado) - new Date(dataA.fecha_completado);
                    case 'presupuesto_asc':
                        return dataA.presupuesto - dataB.presupuesto;
                    case 'presupuesto_desc':
                        return dataB.presupuesto - dataA.presupuesto;
                    default:
                        return 0;
                }
            });

            // Actualizar DOM
            const tbody = document.querySelector('tbody');
            rowsArray.forEach(row => tbody.appendChild(row));

            // Mostrar/ocultar mensaje de no resultados
            const noResultados = document.getElementById('noResultados');
            const tablaProyectos = document.querySelector('.bg-white.shadow-md.rounded-lg.overflow-hidden');
            
            if (resultadosEncontrados) {
                noResultados.classList.add('hidden');
                tablaProyectos.classList.remove('hidden');
            } else {
                noResultados.classList.remove('hidden');
                tablaProyectos.classList.add('hidden');
            }
        }

        // Añadir event listeners para los nuevos filtros
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = [
                'buscadorProyectos', 'filtroEstado', 'fechaInicioMin', 'fechaInicioMax',
                'fechaFinMin', 'fechaFinMax', 'fechaCompletadoMin', 'fechaCompletadoMax',
                'presupuestoMin', 'presupuestoMax', 'filtroOrden'
            ];

            inputs.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.addEventListener('change', aplicarFiltros);
                    if (element.type === 'text' || element.type === 'number') {
                        element.addEventListener('input', aplicarFiltros);
                    }
                }
            });
        });

        function resetFiltros() {
            const inputs = [
                'buscadorProyectos', 'filtroEstado', 'fechaInicioMin', 'fechaInicioMax',
                'fechaFinMin', 'fechaFinMax', 'fechaCompletadoMin', 'fechaCompletadoMax',
                'presupuestoMin', 'presupuestoMax', 'filtroOrden'
            ];

            inputs.forEach(id => {
                const element = document.getElementById(id);
                if (element) {
                    element.value = element.type === 'select-one' ? 'todos' : '';
                }
            });

            aplicarFiltros();
        }

        function eliminarProyecto(proyectoId) {
            if (confirm('¿Estás seguro de que deseas eliminar este proyecto?')) {
                fetch(`/clientes/{{ $cliente->id }}/proyectos/${proyectoId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error al eliminar el proyecto');
                    }
                    return response.json();
                })
                .then(data => {
                    const fila = document.querySelector(`tr[data-proyecto-id="${proyectoId}"]`);
                    if (fila) {
                        fila.remove();
                    }
                    alert('Proyecto eliminado correctamente');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(error.message);
                });
            }
        }
    </script>
</body>
</html> 