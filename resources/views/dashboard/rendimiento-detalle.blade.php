<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Rendimiento</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Modal para editar el porcentaje mínimo -->
    <div id="porcentajeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Configurar Porcentaje Mínimo</h3>
                <p id="periodoLabel" class="text-sm text-gray-600 mb-4"></p>
                <div class="mt-2 px-7 py-3">
                    <input type="number" 
                           id="porcentajeMinimo" 
                           class="w-full px-3 py-2 border rounded-md" 
                           min="0" 
                           max="100" 
                           value="50">
                </div>
                <div class="flex justify-end gap-2 px-4 py-3">
                    <button id="cancelarPorcentaje" 
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Cancelar
                    </button>
                    <button id="guardarPorcentaje" 
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                            <div class="transition-colors duration-300 p-4 rounded-lg cursor-pointer" 
                                 onclick="mostrarModalPorcentaje('{{ $periodo }}', '{{ $stats['label'] }}')"
                                 id="tasaExitoContainer-{{ $periodo }}">
                                <h3 class="text-lg font-semibold mb-3 transition-colors duration-300" 
                                    id="tasaExitoTitulo-{{ $periodo }}">
                                    Tasa de Éxito 
                                    <span class="text-sm font-normal">(click para configurar)</span>
                                </h3>
                                <div class="flex items-center justify-center h-24">
                                    <span class="text-4xl font-bold transition-colors duration-300" 
                                          id="tasaExito-{{ $periodo }}" 
                                          data-valor="{{ $stats['tasa_exito'] }}">
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

    <script>
        let porcentajesMinimos = JSON.parse(localStorage.getItem('porcentajesMinimos')) || {
            'ultimo_mes': 50,
            'ultimo_trimestre': 50,
            'ultimo_anio': 50
        };
        
        let periodoActual = '';
        
        function aplicarColores() {
            Object.keys(porcentajesMinimos).forEach(periodo => {
                const elemento = document.getElementById(`tasaExito-${periodo}`);
                if (!elemento) return;
                
                const valor = elemento.dataset.valor;
                const container = document.getElementById(`tasaExitoContainer-${periodo}`);
                const titulo = document.getElementById(`tasaExitoTitulo-${periodo}`);
                const porcentajeMinimo = porcentajesMinimos[periodo];
                
                // Si el valor es null o undefined, usar gris
                if (valor === null || valor === undefined || valor === '') {
                    elemento.className = 'text-4xl font-bold text-gray-600';
                    container.className = 'bg-gray-50 p-4 rounded-lg cursor-pointer transition-colors duration-300';
                    titulo.className = 'text-lg font-semibold mb-3 text-gray-800 transition-colors duration-300';
                } else {
                    // Si hay un valor (incluso 0), aplicar rojo o verde
                    const valorNumerico = parseFloat(valor);
                    if (valorNumerico < porcentajeMinimo) {
                        elemento.className = 'text-4xl font-bold text-red-600';
                        container.className = 'bg-red-50 p-4 rounded-lg cursor-pointer transition-colors duration-300';
                        titulo.className = 'text-lg font-semibold mb-3 text-red-800 transition-colors duration-300';
                    } else {
                        elemento.className = 'text-4xl font-bold text-green-600';
                        container.className = 'bg-green-50 p-4 rounded-lg cursor-pointer transition-colors duration-300';
                        titulo.className = 'text-lg font-semibold mb-3 text-green-800 transition-colors duration-300';
                    }
                }
            });
        }

        // Mostrar modal
        function mostrarModalPorcentaje(periodo, label) {
            periodoActual = periodo;
            const modal = document.getElementById('porcentajeModal');
            const input = document.getElementById('porcentajeMinimo');
            const periodoLabel = document.getElementById('periodoLabel');
            
            input.value = porcentajesMinimos[periodo];
            periodoLabel.textContent = `Configurando porcentaje mínimo para: ${label}`;
            modal.classList.remove('hidden');
        }

        // Configurar eventos
        document.getElementById('cancelarPorcentaje').onclick = function() {
            document.getElementById('porcentajeModal').classList.add('hidden');
        }

        document.getElementById('guardarPorcentaje').onclick = function() {
            const nuevoValor = document.getElementById('porcentajeMinimo').value;
            porcentajesMinimos[periodoActual] = nuevoValor;
            localStorage.setItem('porcentajesMinimos', JSON.stringify(porcentajesMinimos));
            aplicarColores();
            document.getElementById('porcentajeModal').classList.add('hidden');
        }

        // Aplicar colores al cargar la página
        aplicarColores();
    </script>
</body>
</html> 