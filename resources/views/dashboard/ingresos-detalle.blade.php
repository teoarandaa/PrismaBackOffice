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
                        <h1 class="text-3xl font-bold text-gray-800">Detalle de Ingresos</h1>
                        <p class="text-gray-600">Análisis financiero de proyectos</p>
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
                <p class="text-4xl font-bold text-green-600">{{ number_format($ingresos['total_general'], 2, ',', '.') }}€</p>
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
                            @foreach($ingresos['por_tipo'] as $tipo)
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
                            @foreach($ingresos['por_mes'] as $mes)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $mes->mes }}/{{ $mes->año }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($mes->total, 2, ',', '.') }}€</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $ingresos['por_mes']->links() }}
                </div>
            </div>

            <!-- Proyectos por Ingresos -->
            <div>
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-green-600">Proyectos por Ingresos</h2>
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
                </div>
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
                            @foreach($ingresos['proyectos'] as $proyecto)
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
                    {{ $ingresos['proyectos']->links() }}
                </div>
            </div>
        </div>
    </div>
</body>
</html> 