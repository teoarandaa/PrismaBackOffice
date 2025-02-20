<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Proyectos Activos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        nav[role="navigation"] {
            color: white !important;
        }
        [aria-current="page"] span {
            background-color: #3B82F6 !important;
            color: white !important;
        }
        .filtros {
            display: none;
            transition: all 0.3s ease-in-out;
        }
        .filtros.active {
            display: block;
        }
        .col-proyecto { width: 25%; }
        .col-cliente { width: 20%; }
        .col-tipo { width: 10%; }
        .col-inicio { width: 15%; }
        .col-fin { width: 15%; }
        .col-fecha { width: 15%; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Proyectos Activos</h1>
                        <p class="text-gray-600">Estado actual de los proyectos</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('dashboard.kpis') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <!-- Proyectos Completados -->
            <div class="mb-16">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-green-600">Completados ({{ $proyectos['completados']->total() }})</h2>
                    <button onclick="toggleFiltros('completados')" 
                            class="bg-green-100 hover:bg-green-200 text-green-800 font-bold py-2 px-4 rounded inline-flex items-center">
                        <span>Filtros</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Filtros Completados -->
                <form id="filtros-completados" class="filtros mb-4 bg-white p-4 rounded-lg shadow">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proyecto</label>
                            <input type="text" name="completados_nombre" value="{{ request('completados_nombre') }}" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" name="completados_cliente" value="{{ request('completados_cliente') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="completados_tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                                <option value="">Todos</option>
                                <option value="web" {{ request('completados_tipo') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="app" {{ request('completados_tipo') == 'app' ? 'selected' : '' }}>App</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Inicio Previsto</label>
                            <input type="date" name="completados_inicio" value="{{ request('completados_inicio') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fin Estimado</label>
                            <input type="date" name="completados_fin" value="{{ request('completados_fin') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha Completado</label>
                            <input type="date" name="completados_completado" value="{{ request('completados_completado') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('dashboard.proyectos-activos') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Limpiar
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabla Completados -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="col-proyecto px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Proyecto</th>
                                <th class="col-cliente px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Cliente</th>
                                <th class="col-tipo px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Tipo</th>
                                <th class="col-inicio px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Inicio Previsto</th>
                                <th class="col-fin px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Fin Estimado</th>
                                <th class="col-fecha px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Completado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['completados'] as $proyecto)
                            <tr class="hover:bg-gray-50">
                                <td class="col-proyecto px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                                <td class="col-cliente px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                                <td class="col-tipo px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                                <td class="col-inicio px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_inicio }}</td>
                                <td class="col-fin px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_finalizacion }}</td>
                                <td class="col-fecha px-6 py-4 whitespace-nowrap">{{ $proyecto->updated_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $proyectos['completados']->links() }}
                </div>
            </div>

            <!-- Proyectos En Desarrollo -->
            <div class="mb-16">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-blue-600">En Desarrollo ({{ $proyectos['en_progreso']->total() }})</h2>
                    <button onclick="toggleFiltros('en_progreso')" 
                            class="bg-blue-100 hover:bg-blue-200 text-blue-800 font-bold py-2 px-4 rounded inline-flex items-center">
                        <span>Filtros</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Filtros En Desarrollo -->
                <form id="filtros-en_progreso" class="filtros mb-4 bg-white p-4 rounded-lg shadow">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proyecto</label>
                            <input type="text" name="en_progreso_nombre" value="{{ request('en_progreso_nombre') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" name="en_progreso_cliente" value="{{ request('en_progreso_cliente') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="en_progreso_tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="web" {{ request('en_progreso_tipo') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="app" {{ request('en_progreso_tipo') == 'app' ? 'selected' : '' }}>App</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Inicio Previsto</label>
                            <input type="date" name="en_progreso_inicio" value="{{ request('en_progreso_inicio') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fin Estimado</label>
                            <input type="date" name="en_progreso_fin" value="{{ request('en_progreso_fin') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('dashboard.proyectos-activos') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Limpiar
                        </a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabla En Desarrollo -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="col-proyecto px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Proyecto</th>
                                <th class="col-cliente px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Cliente</th>
                                <th class="col-tipo px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Tipo</th>
                                <th class="col-inicio px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Inicio Previsto</th>
                                <th class="col-fin px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Fin Estimado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['en_progreso'] as $proyecto)
                            <tr class="hover:bg-gray-50">
                                <td class="col-proyecto px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                                <td class="col-cliente px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                                <td class="col-tipo px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                                <td class="col-inicio px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_inicio }}</td>
                                <td class="col-fin px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_finalizacion }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $proyectos['en_progreso']->links() }}
                </div>
            </div>

            <!-- Proyectos Cancelados -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-red-600">Cancelados ({{ $proyectos['cancelados']->total() }})</h2>
                    <button onclick="toggleFiltros('cancelados')" 
                            class="bg-red-100 hover:bg-red-200 text-red-800 font-bold py-2 px-4 rounded inline-flex items-center">
                        <span>Filtros</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Filtros Cancelados -->
                <form id="filtros-cancelados" class="filtros mb-4 bg-white p-4 rounded-lg shadow">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proyecto</label>
                            <input type="text" name="cancelados_nombre" value="{{ request('cancelados_nombre') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" name="cancelados_cliente" value="{{ request('cancelados_cliente') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="cancelados_tipo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                <option value="">Todos</option>
                                <option value="web" {{ request('cancelados_tipo') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="app" {{ request('cancelados_tipo') == 'app' ? 'selected' : '' }}>App</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Inicio Previsto</label>
                            <input type="date" name="cancelados_inicio" value="{{ request('cancelados_inicio') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fin Estimado</label>
                            <input type="date" name="cancelados_fin" value="{{ request('cancelados_fin') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha Cancelación</label>
                            <input type="date" name="cancelados_cancelado" value="{{ request('cancelados_cancelado') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('dashboard.proyectos-activos') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Limpiar
                        </a>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabla Cancelados -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-red-50">
                            <tr>
                                <th class="col-proyecto px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Proyecto</th>
                                <th class="col-cliente px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Cliente</th>
                                <th class="col-tipo px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Tipo</th>
                                <th class="col-inicio px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Inicio Previsto</th>
                                <th class="col-fin px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Fin Estimado</th>
                                <th class="col-fecha px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Cancelado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['cancelados'] as $proyecto)
                            <tr class="hover:bg-gray-50">
                                <td class="col-proyecto px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                                <td class="col-cliente px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                                <td class="col-tipo px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                                <td class="col-inicio px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_inicio }}</td>
                                <td class="col-fin px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_finalizacion }}</td>
                                <td class="col-fecha px-6 py-4 whitespace-nowrap">{{ $proyecto->updated_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $proyectos['cancelados']->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleFiltros(seccion) {
            // Cerrar otros filtros abiertos
            document.querySelectorAll('.filtros').forEach(filtro => {
                if (filtro.id !== `filtros-${seccion}`) {
                    filtro.classList.remove('active');
                }
            });

            // Toggle del filtro seleccionado
            const filtro = document.getElementById(`filtros-${seccion}`);
            filtro.classList.toggle('active');

            // Rotar flecha
            const button = event.currentTarget;
            const svg = button.querySelector('svg');
            svg.style.transform = filtro.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0)';
        }

        // Si hay filtros activos, mostrar la sección correspondiente
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            ['completados', 'en_progreso', 'cancelados'].forEach(seccion => {
                const tieneFiltroPara = Array.from(urlParams.keys()).some(key => key.startsWith(`${seccion}_`));
                if (tieneFiltroPara) {
                    document.getElementById(`filtros-${seccion}`).classList.add('active');
                    const button = document.querySelector(`button[onclick="toggleFiltros('${seccion}')"]`);
                    const svg = button.querySelector('svg');
                    svg.style.transform = 'rotate(180deg)';
                }
            });
        }
    </script>
</body>
</html> 