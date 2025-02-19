<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle Top Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Top Clientes</h1>
                        <p class="text-gray-600">Análisis detallado de clientes</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('dashboard.kpis') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <div class="space-y-6">
                @foreach($clientes as $cliente)
                <div class="border rounded-lg p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h3 class="text-blue-800 font-semibold">Cliente</h3>
                            <p class="text-xl font-bold">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h3 class="text-green-800 font-semibold">Total Proyectos</h3>
                            <p class="text-xl font-bold">{{ $cliente->total_proyectos }}</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h3 class="text-purple-800 font-semibold">Presupuesto Total</h3>
                            <p class="text-xl font-bold">{{ number_format($cliente->proyectos_sum_presupuesto, 2, ',', '.') }}€</p>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg">
                            <h3 class="text-yellow-800 font-semibold">Presupuesto Promedio</h3>
                            <p class="text-xl font-bold">{{ number_format($cliente->proyectos_avg_presupuesto, 2, ',', '.') }}€</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4 class="font-semibold mb-2">Últimos Proyectos</h4>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Presupuesto</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($cliente->proyectos as $proyecto)
                                    <tr>
                                        <td class="px-6 py-4">{{ $proyecto->nombre_proyecto }}</td>
                                        <td class="px-6 py-4 capitalize">{{ $proyecto->tipo }}</td>
                                        <td class="px-6 py-4">{{ $proyecto->estado }}</td>
                                        <td class="px-6 py-4">{{ number_format($proyecto->presupuesto, 2, ',', '.') }}€</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html> 