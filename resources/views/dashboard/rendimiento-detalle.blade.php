<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Rendimiento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Análisis de Rendimiento</h1>
                        <p class="text-gray-600">Métricas por período</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('dashboard.kpis') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <div class="space-y-6">
                @foreach($estadisticas as $periodo => $stats)
                <div class="bg-white border rounded-lg overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b">
                        <h2 class="text-xl font-semibold text-gray-800">{{ $stats['label'] }}</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Métricas de Proyectos -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-blue-800 mb-3">Estado de Proyectos</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Iniciados</span>
                                        <span class="font-semibold">{{ $stats['iniciados'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">En Progreso</span>
                                        <span class="font-semibold">{{ $stats['en_progreso'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Completados</span>
                                        <span class="font-semibold">{{ $stats['completados'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-blue-600">Cancelados</span>
                                        <span class="font-semibold">{{ $stats['cancelados'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tasa de Éxito -->
                            <div class="bg-green-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-green-800 mb-3">Tasa de Éxito</h3>
                                <div class="flex items-center justify-center h-24">
                                    <span class="text-4xl font-bold text-green-600">
                                        {{ number_format($stats['tasa_exito'], 1) }}%
                                    </span>
                                </div>
                            </div>

                            <!-- Tiempo Promedio -->
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <h3 class="text-lg font-semibold text-purple-800 mb-3">Tiempo Promedio</h3>
                                <div class="flex items-center justify-center h-24">
                                    <span class="text-4xl font-bold text-purple-600">
                                        {{ $stats['tiempo_promedio'] ? number_format($stats['tiempo_promedio'], 2, ',', '.') : 'N/A' }}
                                        <span class="text-lg">días</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html> 