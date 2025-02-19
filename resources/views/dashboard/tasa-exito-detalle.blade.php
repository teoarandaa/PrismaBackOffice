<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Tasa de Éxito</title>
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
            background-color: #EAB308 !important;
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
                        <h1 class="text-3xl font-bold text-gray-800">Tasa de Éxito</h1>
                        <p class="text-gray-600">Análisis de proyectos completados</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('dashboard.kpis') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <!-- Tasa de Éxito General -->
            <div class="mb-8 bg-yellow-50 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-yellow-800 mb-2">Tasa de Éxito General</h2>
                <p class="text-4xl font-bold text-yellow-600">{{ $estadisticas['general']['tasa_exito'] }}%</p>
                <p class="text-sm text-yellow-600">
                    {{ $estadisticas['general']['completados'] }} completados de {{ $estadisticas['general']['total'] }} totales
                </p>
            </div>

            <!-- Tasas por Tipo -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-yellow-600 mb-4">Tasa de Éxito por Tipo</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-yellow-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Completados</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Tasa de Éxito</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap capitalize">Apps</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $estadisticas['por_tipo']['app']['completados'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $estadisticas['por_tipo']['app']['total'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $estadisticas['por_tipo']['app']['tasa_exito'] }}%</td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap capitalize">Webs</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $estadisticas['por_tipo']['web']['completados'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $estadisticas['por_tipo']['web']['total'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $estadisticas['por_tipo']['web']['tasa_exito'] }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lista de Proyectos -->
            <div>
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-yellow-600">Historial de Proyectos</h2>
                </div>

                <div class="flex justify-end items-center gap-4 mb-4">
                    <button onclick="toggleFiltros('exito')" 
                            class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 font-bold py-2 px-4 rounded inline-flex items-center">
                        <span>Filtros</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>

                <!-- Filtros -->
                <form id="filtros-exito" class="filtros mb-4 bg-white p-4 rounded-lg shadow">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proyecto</label>
                            <input type="text" 
                                   name="proyecto" 
                                   value="{{ request('proyecto') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Buscar por nombre de proyecto">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" 
                                   name="cliente" 
                                   value="{{ request('cliente') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500"
                                   placeholder="Buscar por nombre de cliente">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="tipo" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="">Todos</option>
                                <option value="web" {{ request('tipo') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="app" {{ request('tipo') == 'app' ? 'selected' : '' }}>App</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select name="estado" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                                <option value="">Todos</option>
                                <option value="Completado" {{ request('estado') == 'Completado' ? 'selected' : '' }}>Completado</option>
                                <option value="En Progreso" {{ request('estado') == 'En Progreso' ? 'selected' : '' }}>En Progreso</option>
                                <option value="Cancelado" {{ request('estado') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha Finalización</label>
                            <input type="date" 
                                   name="fecha_fin" 
                                   value="{{ request('fecha_fin') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-yellow-500 focus:ring-yellow-500">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('dashboard.tasa-exito') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Limpiar
                        </a>
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar
                        </button>
                    </div>
                </form>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-yellow-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Fecha Finalización</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($estadisticas['proyectos'] as $proyecto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $proyecto->estado === 'Completado' ? 'bg-green-100 text-green-800' : 
                                           ($proyecto->estado === 'Cancelado' ? 'bg-red-100 text-red-800' : 
                                           'bg-yellow-100 text-yellow-800') }}">
                                        {{ $proyecto->estado }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->updated_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $estadisticas['proyectos']->links() }}
                </div>
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
            const filtrosParams = ['proyecto', 'cliente', 'tipo', 'estado', 'fecha_fin'];
            
            // Verificar si hay algún filtro activo
            const tieneFiltroPara = filtrosParams.some(param => urlParams.has(param));
            
            if (tieneFiltroPara) {
                document.getElementById('filtros-exito').classList.add('active');
                const button = document.querySelector('button[onclick="toggleFiltros(\'exito\')"]');
                const svg = button.querySelector('svg');
                svg.style.transform = 'rotate(180deg)';
            }
        }
    </script>
</body>
</html> 