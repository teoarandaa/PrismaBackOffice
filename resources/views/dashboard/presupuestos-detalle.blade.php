<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Presupuestos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Análisis de Presupuestos</h1>
                        <p class="text-gray-600">Detalle por tipo de proyecto</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('dashboard.kpis') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Estadísticas Apps -->
                <div class="bg-white p-6 rounded-lg">
                    <h2 class="text-2xl font-bold text-blue-800 mb-4">Apps</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-sm text-blue-600">Presupuesto Promedio</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($estadisticas['apps']['promedio'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-sm text-blue-600">Presupuesto Máximo</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($estadisticas['apps']['maximo'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-sm text-blue-600">Presupuesto Mínimo</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($estadisticas['apps']['minimo'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-sm text-blue-600">Tasa de Éxito</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format(($estadisticas['apps']['completados'] / $estadisticas['apps']['total']) * 100, 1) }}%</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-4 text-blue-800">Últimos Proyectos</h3>
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <table class="min-w-full">
                                    <thead class="bg-blue-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Proyecto</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-blue-800 uppercase tracking-wider">
                                                <div class="flex items-center justify-end gap-2">
                                                    Presupuesto
                                                    <div class="flex flex-col">
                                                        <a href="{{ request()->fullUrlWithQuery(['orden_apps' => 'desc']) }}" 
                                                           class="{{ $ordenApps === 'desc' ? 'text-blue-600' : 'text-gray-400' }}">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/>
                                                            </svg>
                                                        </a>
                                                        <a href="{{ request()->fullUrlWithQuery(['orden_apps' => 'asc']) }}"
                                                           class="{{ $ordenApps === 'asc' ? 'text-blue-600' : 'text-gray-400' }}">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($ultimosProyectos['apps'] as $proyecto)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $proyecto->nombre_proyecto }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-right">{{ number_format($proyecto->presupuesto, 2, ',', '.') }}€</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 apps-pagination">
                                {{ $ultimosProyectos['apps']->appends(request()->except('pagina_apps'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Webs -->
                <div class="bg-white p-6 rounded-lg">
                    <h2 class="text-2xl font-bold text-green-800 mb-4">Webs</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="text-sm text-green-600">Presupuesto Promedio</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($estadisticas['webs']['promedio'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="text-sm text-green-600">Presupuesto Máximo</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($estadisticas['webs']['maximo'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="text-sm text-green-600">Presupuesto Mínimo</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format($estadisticas['webs']['minimo'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="text-sm text-green-600">Tasa de Éxito</h3>
                                <p class="text-2xl font-bold text-gray-800">{{ number_format(($estadisticas['webs']['completados'] / $estadisticas['webs']['total']) * 100, 1) }}%</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-4 text-green-800">Últimos Proyectos</h3>
                            <div class="bg-white rounded-lg shadow overflow-hidden">
                                <table class="min-w-full">
                                    <thead class="bg-green-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Proyecto</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-green-800 uppercase tracking-wider">
                                                <div class="flex items-center justify-end gap-2">
                                                    Presupuesto
                                                    <div class="flex flex-col">
                                                        <a href="{{ request()->fullUrlWithQuery(['orden_webs' => 'desc']) }}" 
                                                           class="{{ $ordenWebs === 'desc' ? 'text-green-600' : 'text-gray-400' }}">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 8l-6 6 1.41 1.41L12 10.83l4.59 4.58L18 14z"/>
                                                            </svg>
                                                        </a>
                                                        <a href="{{ request()->fullUrlWithQuery(['orden_webs' => 'asc']) }}"
                                                           class="{{ $ordenWebs === 'asc' ? 'text-green-600' : 'text-gray-400' }}">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M16.59 8.59L12 13.17 7.41 8.59 6 10l6 6 6-6z"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($ultimosProyectos['webs'] as $proyecto)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $proyecto->nombre_proyecto }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap font-semibold text-right">{{ number_format($proyecto->presupuesto, 2, ',', '.') }}€</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 webs-pagination">
                                {{ $ultimosProyectos['webs']->appends(request()->except('pagina_webs'))->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estilos para la paginación -->
    <style>
        nav[role="navigation"] {
            color: white !important;
        }
        .apps-pagination [aria-current="page"] span {
            background-color: #3B82F6 !important; /* blue-500 para Apps */
        }
        .webs-pagination [aria-current="page"] span {
            background-color: #22C55E !important; /* green-500 para Webs */
        }
    </style>
</body>
</html> 