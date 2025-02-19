<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Tiempos de Desarrollo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .filtros {
            display: none;
            transition: all 0.3s ease-in-out;
        }
        .filtros.active {
            display: block;
        }
        nav[role="navigation"] {
            color: white !important;
        }
        [aria-current="page"] span {
            background-color: #9333EA !important;
            color: white !important;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Tiempos de Desarrollo</h1>
                        <p class="text-gray-600">Análisis de duración de proyectos</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('dashboard.kpis') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <!-- Tiempo Medio General -->
            <div class="mb-8 bg-purple-50 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-purple-800 mb-2">Tiempo Medio General</h2>
                <p class="text-4xl font-bold text-purple-600">{{ (int)$proyectos['promedio_general'] }} días</p>
                <p class="text-sm text-purple-600">Promedio de todos los proyectos completados</p>
            </div>

            <!-- Tiempo por Tipo de Proyecto -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-purple-600 mb-4">Tiempo Medio por Tipo</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-purple-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Tiempo Medio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Total Proyectos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['por_tipo'] as $tipo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $tipo->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ (int)$tipo->promedio_dias }} días</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tipo->total_proyectos }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Proyectos por Tiempo de Desarrollo -->
            <div>
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-purple-600">Proyectos por Tiempo de Desarrollo</h2>
                </div>

                <div class="flex justify-end items-center gap-4 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Ordenar por:</span>
                        <a href="{{ request()->fullUrlWithQuery(['orden' => 'desc']) }}" 
                           class="px-3 py-1 rounded {{ $ordenTiempo === 'desc' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700' }} text-sm">
                            Mayor a menor
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['orden' => 'asc']) }}" 
                           class="px-3 py-1 rounded {{ $ordenTiempo === 'asc' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700' }} text-sm">
                            Menor a mayor
                        </a>
                    </div>
                    <button onclick="toggleFiltros('tiempo')" 
                            class="bg-purple-100 hover:bg-purple-200 text-purple-800 font-bold py-2 px-4 rounded inline-flex items-center">
                        <span>Filtros</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>

                <!-- Filtros -->
                <form id="filtros-tiempo" class="filtros mb-4 bg-white p-4 rounded-lg shadow">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proyecto</label>
                            <input type="text" 
                                   name="proyecto" 
                                   value="{{ request('proyecto') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                   placeholder="Buscar por nombre de proyecto">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" 
                                   name="cliente" 
                                   value="{{ request('cliente') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                   placeholder="Buscar por nombre de cliente">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="tipo" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                <option value="">Todos</option>
                                <option value="web" {{ request('tipo') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="app" {{ request('tipo') == 'app' ? 'selected' : '' }}>App</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Inicio Previsto</label>
                            <input type="date" 
                                   name="fecha_inicio" 
                                   value="{{ request('fecha_inicio') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fin Estimado</label>
                            <input type="date" 
                                   name="fecha_fin" 
                                   value="{{ request('fecha_fin') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duración (días)</label>
                            <div class="flex gap-2">
                                <input type="number" 
                                       name="duracion_min" 
                                       value="{{ request('duracion_min') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                       placeholder="Mínimo">
                                <input type="number" 
                                       name="duracion_max" 
                                       value="{{ request('duracion_max') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                       placeholder="Máximo">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('dashboard.tiempo-desarrollo') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Limpiar
                        </a>
                        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Proyecto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Inicio Previsto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Fin Estimado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Fecha Completado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Duración</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($proyectos['proyectos'] as $proyecto)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                            <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_inicio }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_finalizacion }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($proyecto->estado === 'Completado')
                                    {{ $proyecto->updated_at->format('Y-m-d H:i:s') }}
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->dias_desarrollo }} días</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $proyectos['proyectos']->links() }}
            </div>
        </div>
    </div>

    <script>
        function toggleFiltros(seccion) {
            const filtro = document.getElementById(`filtros-${seccion}`);
            filtro.classList.toggle('active');

            // Rotar flecha
            const button = event.currentTarget;
            const svg = button.querySelector('svg');
            svg.style.transform = filtro.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0)';
        }

        // Si hay filtros activos, mostrar la sección
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const filtrosParams = ['proyecto', 'cliente', 'tipo', 'fecha_inicio', 'fecha_fin', 'duracion_min', 'duracion_max'];
            
            // Verificar si hay algún filtro activo
            const tieneFiltroPara = filtrosParams.some(param => urlParams.has(param));
            
            if (tieneFiltroPara) {
                document.getElementById('filtros-tiempo').classList.add('active');
                const button = document.querySelector('button[onclick="toggleFiltros(\'tiempo\')"]');
                const svg = button.querySelector('svg');
                svg.style.transform = 'rotate(180deg)';
            }
        }
    </script>
</body>
</html> 