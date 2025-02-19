<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Tiempos de Desarrollo</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <p class="text-4xl font-bold text-purple-600">{{ (int)$tiempos['promedio_general']->tiempo_medio }} días</p>
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
                            @foreach($tiempos['por_tipo'] as $tipo)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $tipo->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ (int)$tipo->tiempo_medio }} días</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $tipo->total_proyectos }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Lista de Proyectos -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-purple-600">Proyectos por Tiempo de Desarrollo</h2>
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
                </div>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-purple-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Inicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Fin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-purple-800 uppercase tracking-wider">Duración</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($tiempos['proyectos'] as $proyecto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_inicio }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->updated_at }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->dias_desarrollo }} días</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $tiempos['proyectos']->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html> 