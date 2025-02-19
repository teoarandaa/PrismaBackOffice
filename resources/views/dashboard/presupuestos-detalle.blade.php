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
                <div class="bg-blue-50 p-6 rounded-lg">
                    <h2 class="text-2xl font-bold text-blue-800 mb-4">Apps</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-lg">
                                <h3 class="text-sm text-blue-600">Presupuesto Promedio</h3>
                                <p class="text-2xl font-bold">{{ number_format($estadisticas['apps']['promedio'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <h3 class="text-sm text-blue-600">Presupuesto Máximo</h3>
                                <p class="text-2xl font-bold">{{ number_format($estadisticas['apps']['maximo'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <h3 class="text-sm text-blue-600">Presupuesto Mínimo</h3>
                                <p class="text-2xl font-bold">{{ number_format($estadisticas['apps']['minimo'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <h3 class="text-sm text-blue-600">Tasa de Éxito</h3>
                                <p class="text-2xl font-bold">{{ number_format(($estadisticas['apps']['completados'] / $estadisticas['apps']['total']) * 100, 1) }}%</p>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Últimos Proyectos</h3>
                            <div class="space-y-2">
                                @foreach($ultimosProyectos['apps'] as $proyecto)
                                <div class="flex justify-between items-center p-2 hover:bg-blue-50 rounded">
                                    <span>{{ $proyecto->nombre_proyecto }}</span>
                                    <span class="font-semibold">{{ number_format($proyecto->presupuesto, 2, ',', '.') }}€</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estadísticas Webs -->
                <div class="bg-green-50 p-6 rounded-lg">
                    <h2 class="text-2xl font-bold text-green-800 mb-4">Webs</h2>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white p-4 rounded-lg">
                                <h3 class="text-sm text-green-600">Presupuesto Promedio</h3>
                                <p class="text-2xl font-bold">{{ number_format($estadisticas['webs']['promedio'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <h3 class="text-sm text-green-600">Presupuesto Máximo</h3>
                                <p class="text-2xl font-bold">{{ number_format($estadisticas['webs']['maximo'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <h3 class="text-sm text-green-600">Presupuesto Mínimo</h3>
                                <p class="text-2xl font-bold">{{ number_format($estadisticas['webs']['minimo'], 2, ',', '.') }}€</p>
                            </div>
                            <div class="bg-white p-4 rounded-lg">
                                <h3 class="text-sm text-green-600">Tasa de Éxito</h3>
                                <p class="text-2xl font-bold">{{ number_format(($estadisticas['webs']['completados'] / $estadisticas['webs']['total']) * 100, 1) }}%</p>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-2">Últimos Proyectos</h3>
                            <div class="space-y-2">
                                @foreach($ultimosProyectos['webs'] as $proyecto)
                                <div class="flex justify-between items-center p-2 hover:bg-green-50 rounded">
                                    <span>{{ $proyecto->nombre_proyecto }}</span>
                                    <span class="font-semibold">{{ number_format($proyecto->presupuesto, 2, ',', '.') }}€</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 