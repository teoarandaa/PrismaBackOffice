<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Ingresos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-green-600">Proyectos por Ingresos</h1>
                        <p class="text-gray-600">Detalle de ingresos por proyecto</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('dashboard.kpis') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <!-- Ingresos Totales General -->
            <div class="mb-8 bg-green-50 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-green-800 mb-2">Ingresos Totales</h2>
                <p class="text-4xl font-bold text-green-600">{{ number_format($proyectos['total_general'], 2, ',', '.') }}€</p>
                <p class="text-sm text-green-600">Total histórico de todos los proyectos</p>
            </div>

            <!-- Ingresos por Tipo -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-green-600 mb-4">Ingresos por Tipo de Proyecto</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Total Ingresos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['por_tipo'] as $tipo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $tipo->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($tipo->total, 2, ',', '.') }}€</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ingresos Mensuales -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-green-600 mb-4">Ingresos Mensuales</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Período</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['por_mes'] as $mes)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mes->mes }}/{{ $mes->año }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($mes->total, 2, ',', '.') }}€</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $proyectos['por_mes']->appends(request()->except('pagina_mes'))->links() }}
                </div>
            </div>

            <!-- Proyectos por Ingresos -->
            <div>
                <div class="mb-4">
                    <h2 class="text-2xl font-bold text-green-600">Proyectos por Ingresos</h2>
                </div>

                <div class="flex justify-end items-center gap-4 mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-600">Ordenar por:</span>
                        <a href="{{ request()->fullUrlWithQuery(['orden' => 'desc']) }}" 
                           class="px-3 py-1 rounded {{ $ordenIngresos === 'desc' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }} text-sm">
                            Mayor a menor
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['orden' => 'asc']) }}" 
                           class="px-3 py-1 rounded {{ $ordenIngresos === 'asc' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700' }} text-sm">
                            Menor a mayor
                        </a>
                    </div>
                    <button onclick="toggleFiltros('ingresos')" 
                            class="bg-green-100 hover:bg-green-200 text-green-800 font-bold py-2 px-4 rounded inline-flex items-center">
                        <span>Filtros</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                </div>

                <!-- Filtros -->
                <form id="filtros-ingresos" class="filtros mb-4 bg-white p-4 rounded-lg shadow">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proyecto</label>
                            <input type="text" 
                                   name="proyecto" 
                                   value="{{ request('proyecto') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Buscar por nombre de proyecto">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <input type="text" 
                                   name="cliente" 
                                   value="{{ request('cliente') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Buscar por nombre de cliente">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipo</label>
                            <select name="tipo" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="web" {{ request('tipo') == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="app" {{ request('tipo') == 'app' ? 'selected' : '' }}>App</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado</label>
                            <select name="estado" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="En progreso" {{ request('estado') == 'En progreso' ? 'selected' : '' }}>En Desarrollo</option>
                                <option value="Completado" {{ request('estado') == 'Completado' ? 'selected' : '' }}>Completado</option>
                                <option value="Cancelado" {{ request('estado') == 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ingresos Mínimos</label>
                            <input type="number" 
                                   name="ingresos_min" 
                                   value="{{ request('ingresos_min') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Mínimo">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ingresos Máximos</label>
                            <input type="number" 
                                   name="ingresos_max" 
                                   value="{{ request('ingresos_max') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Máximo">
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('dashboard.ingresos') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Limpiar
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Filtrar
                        </button>
                    </div>
                </form>

                <!-- Tabla -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Ingresos</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['proyectos'] as $proyecto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->estado }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($proyecto->presupuesto, 2, ',', '.') }}€</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $proyectos['proyectos']->appends(request()->except('pagina_proyectos'))->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        .filtros {
            display: none;
            transition: all 0.3s ease-in-out;
        }
        .filtros.active {
            display: block;
        }
    </style>

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
            const filtrosParams = ['proyecto', 'cliente', 'tipo', 'estado', 'ingresos_min', 'ingresos_max'];
            
            // Verificar si hay algún filtro activo
            const tieneFiltroPara = filtrosParams.some(param => urlParams.has(param));
            
            if (tieneFiltroPara) {
                document.getElementById('filtros-ingresos').classList.add('active');
                const button = document.querySelector('button[onclick="toggleFiltros(\'ingresos\')"]');
                const svg = button.querySelector('svg');
                svg.style.transform = 'rotate(180deg)';
            }
        }
    </script>
</body>
</html> 