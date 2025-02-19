<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Tasa de Éxito</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <h2 class="text-2xl font-bold text-yellow-600 mb-4">Historial de Proyectos</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-yellow-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-yellow-800 uppercase tracking-wider">Fecha</th>
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
                                        {{ $proyecto->exitoso ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
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
</body>
</html> 