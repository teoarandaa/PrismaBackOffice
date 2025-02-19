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

            <!-- Proyectos Activos -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-blue-600 mb-4">En Desarrollo ({{ $proyectos['activos']->total() }})</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Inicio Previsto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-blue-800 uppercase tracking-wider">Fin Estimado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['activos'] as $proyecto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_inicio }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_finalizacion }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $proyectos['activos']->links() }}
                </div>
            </div>

            <!-- Últimos Proyectos Completados -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-green-600 mb-4">Completados ({{ $proyectos['completados']->total() }})</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-green-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Inicio Previsto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Fin Estimado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Completado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['completados'] as $proyecto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_inicio }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_finalizacion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->updated_at }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $proyectos['completados']->links() }}
                </div>
            </div>

            <!-- Proyectos Cancelados -->
            <div>
                <h2 class="text-2xl font-bold text-red-600 mb-4">Cancelados ({{ $proyectos['cancelados']->total() }})</h2>
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-red-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Proyecto</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Inicio</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Fin Estimado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-red-800 uppercase tracking-wider">Cancelado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($proyectos['cancelados'] as $proyecto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                                <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_inicio }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->fecha_finalizacion }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->updated_at }}</td>
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
</body>
</html> 