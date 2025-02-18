<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Proyectos</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Rango de fechas</h3>
                                <div class="flex gap-2 items-center">
                                    <input type="date" id="fechaInicio"
                                           class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <span class="text-gray-500">-</span>
                                    <input type="date" id="fechaFin"
                                           class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Ordenar por</h3>
                                <select id="filtroOrden" 
                                        class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="recientes">Más recientes</option>
                                    <option value="antiguos">Más antiguos</option>
                                    <option value="presupuesto_alto">Mayor presupuesto</option>
                                    <option value="presupuesto_bajo">Menor presupuesto</option>
                                    <option value="nombre">Nombre del proyecto</option>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyecto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presupuesto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($proyectos as $proyecto)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $proyecto->cliente->empresa }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $proyecto->nombre_proyecto }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($proyecto->descripcion, 50) }}</div>
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
                            €{{ number_format($proyecto->presupuesto, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('clientes.proyectos.show', [$proyecto->cliente, $proyecto]) }}"
                               class="text-blue-600 hover:text-blue-900">Detalles</a>
                            <a href="{{ route('clientes.proyectos.edit', [$proyecto->cliente, $proyecto]) }}"
                               class="text-yellow-600 hover:text-yellow-900">Editar</a>
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
                const clienteNombre = row.querySelector('td:first-child').textContent.toLowerCase();
                const proyectoNombre = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
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

                // Filtro de fecha
                const fechaInicioFiltro = document.getElementById('fechaInicio').value;
                const fechaFinFiltro = document.getElementById('fechaFin').value;
                if (mostrar && fechaInicioDate && (fechaInicioFiltro || fechaFinFiltro)) {
                    const fechaMinima = new Date(fechaInicioFiltro);
                    mostrar = fechaInicioDate >= fechaMinima;
                    if (fechaFinFiltro && mostrar) {
                        const fechaMaxima = new Date(fechaFinFiltro);
                        mostrar = fechaInicioDate <= fechaMaxima;
                    }
                }

                row.style.display = mostrar ? '' : 'none';
                if (mostrar) resultadosEncontrados = true;
            });
            
            // Mostrar/ocultar mensaje de no resultados
            const noResultados = document.getElementById('noResultados');
            const tabla = document.querySelector('table');
            noResultados.style.display = resultadosEncontrados ? 'none' : 'block';
            tabla.style.display = resultadosEncontrados ? '' : 'none';
            
            // Ordenar las filas
            const tbody = document.querySelector('tbody');
            const rowsArray = Array.from(rows);
            
            rowsArray.sort((a, b) => {
                switch(document.getElementById('filtroOrden').value) {
                    case 'recientes':
                        const fechaB = b.querySelector('td:nth-child(4)').textContent.trim();
                        const fechaA = a.querySelector('td:nth-child(4)').textContent.trim();
                        if (fechaB === 'No definida') return 1;
                        if (fechaA === 'No definida') return -1;
                        return new Date(fechaB.split('/').reverse().join('-')) - 
                               new Date(fechaA.split('/').reverse().join('-'));
                    case 'antiguos':
                        const fechaB2 = b.querySelector('td:nth-child(4)').textContent.trim();
                        const fechaA2 = a.querySelector('td:nth-child(4)').textContent.trim();
                        if (fechaB2 === 'No definida') return -1;
                        if (fechaA2 === 'No definida') return 1;
                        return new Date(fechaA2.split('/').reverse().join('-')) - 
                               new Date(fechaB2.split('/').reverse().join('-'));
                    case 'presupuesto_alto':
                        return parseFloat(b.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.')) - 
                               parseFloat(a.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.'));
                    case 'presupuesto_bajo':
                        return parseFloat(a.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.')) - 
                               parseFloat(b.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.'));
                    case 'nombre':
                        return a.querySelector('td:nth-child(2)').textContent.localeCompare(b.querySelector('td:nth-child(2)').textContent);
                    case 'cliente':
                        return a.querySelector('td:first-child').textContent.localeCompare(b.querySelector('td:first-child').textContent);
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
        document.getElementById('fechaInicio').addEventListener('change', aplicarFiltros);
        document.getElementById('fechaFin').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroOrden').addEventListener('change', aplicarFiltros);

        // Aplicar filtros iniciales
        aplicarFiltros();

        function resetFiltros() {
            document.getElementById('buscadorProyectos').value = '';
            document.getElementById('filtroEstado').value = 'todos';
            document.getElementById('presupuestoMin').value = '';
            document.getElementById('presupuestoMax').value = '';
            document.getElementById('fechaInicio').value = '';
            document.getElementById('fechaFin').value = '';
            document.getElementById('filtroOrden').value = 'recientes';
            aplicarFiltros();
        }
    </script>
</body>
</html> 