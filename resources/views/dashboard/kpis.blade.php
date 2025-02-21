<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard KPIs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            width: 80%;
            max-width: 800px;
            border-radius: 8px;
        }

        .close {
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 28px;
            cursor: pointer;
        }

        /* Estilos personalizados para los selectores */
        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1em;
            padding-right: 2.5rem;
        }

        select::-ms-expand {
            display: none;
        }

        /* Estilo para el hover del select */
        select:hover {
            background-color: #f8fafc;
        }

        /* Estilo para cuando el select está activo */
        select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Estilo para las opciones del select */
        select option {
            padding: 0.5rem;
            background-color: white;
            color: #374151;
        }

        select option:hover {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Dashboard KPIs</h1>
                        <p class="text-gray-600">Métricas y análisis de rendimiento</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('clientes.index') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <!-- KPIs Principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-blue-50 p-6 rounded-lg cursor-pointer hover:bg-blue-100 transition-colors"
                     onclick="window.location.href='{{ route('dashboard.proyectos-activos') }}'">
                    <h3 class="text-blue-800 text-lg font-semibold mb-2">
                        <span>Proyectos Activos</span>
                    </h3>
                    <p class="text-3xl font-bold text-blue-600">{{ $estadisticas['en_progreso'] }}</p>
                    <p class="text-sm text-blue-600">En progreso</p>
                </div>
                
                <div class="bg-green-50 p-6 rounded-lg cursor-pointer hover:bg-green-100 transition-colors"
                     onclick="window.location.href='{{ route('dashboard.ingresos') }}'">
                    <h3 class="text-green-800 text-lg font-semibold mb-2">Ingresos Totales</h3>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($ingresosTotales, 2, ',', '.') }}€</p>
                    <p class="text-sm text-green-600">Total histórico</p>
                </div>
                
                <div class="bg-purple-50 p-6 rounded-lg cursor-pointer hover:bg-purple-100 transition-colors"
                     onclick="window.location.href='{{ route('dashboard.tiempo-desarrollo') }}'">
                    <h3 class="text-purple-800 text-lg font-semibold mb-2">Tiempo Medio Desarrollo</h3>
                    <p class="text-3xl font-bold text-purple-600">{{ (int)$tiempoMedioDesarrollo }} días</p>
                    <p class="text-sm text-purple-600">Promedio general</p>
                </div>
                
                <div class="bg-yellow-50 p-6 rounded-lg cursor-pointer hover:bg-yellow-100 transition-colors"
                     onclick="window.location.href='{{ route('dashboard.tasa-exito') }}'">
                    <h3 class="text-yellow-800 text-lg font-semibold mb-2">Tasa de Éxito</h3>
                    <p class="text-3xl font-bold text-yellow-600">{{ $tasaExito }}%</p>
                    <p class="text-sm text-yellow-600">Proyectos completados</p>
                </div>
            </div>

            <!-- Gráficos Principales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Distribución de Proyectos</h3>
                        <select class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-pointer" 
                                onchange="actualizarRangoTiempo('tipoProyectos', this.value)">
                            <option value="mes">Último Mes</option>
                            <option value="trimestre">Último Trimestre</option>
                            <option value="anio">Último Año</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="tipoProyectos"></canvas>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Estado de Proyectos</h3>
                        <select class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-pointer" 
                                onchange="actualizarRangoTiempo('estadoProyectos', this.value)">
                            <option value="mes">Último Mes</option>
                            <option value="trimestre">Último Trimestre</option>
                            <option value="anio">Último Año</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="estadoProyectos"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráficos de Tendencias -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-green-600">Ingresos</h3>
                        <select class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-pointer" 
                                onchange="actualizarRangoTiempo('ingresosTendencia', this.value)">
                            <option value="mes">Último Mes</option>
                            <option value="trimestre">Último Trimestre</option>
                            <option value="anio">Último Año</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="ingresosTendencia"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-purple-600 whitespace-nowrap">Tiempo Desarrollo</h3>
                        <select class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-pointer" 
                                onchange="actualizarRangoTiempo('tiempoTendencia', this.value)">
                            <option value="mes">Último Mes</option>
                            <option value="trimestre">Último Trimestre</option>
                            <option value="anio">Último Año</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="tiempoTendencia"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-yellow-600">Tasa de Éxito</h3>
                        <select class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg shadow-sm hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-pointer" 
                                onchange="actualizarRangoTiempo('exitoTendencia', this.value)">
                            <option value="mes">Último Mes</option>
                            <option value="trimestre">Último Trimestre</option>
                            <option value="anio">Último Año</option>
                            <option value="general">General</option>
                        </select>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="exitoTendencia"></canvas>
                    </div>
                </div>
            </div>

            <!-- Métricas Adicionales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 flex justify-between items-center">
                        <span>Top Clientes</span>
                        <a href="{{ route('dashboard.top-clientes') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Ver detalles →
                        </a>
                    </h3>
                    <div class="space-y-3">
                        @foreach($topClientes as $cliente)
                        <div class="flex justify-between items-center hover:bg-gray-50 p-2 rounded cursor-pointer"
                             onclick="window.location.href='{{ route('dashboard.top-clientes') }}'">
                            <span class="text-gray-600">{{ $cliente->nombre }} {{ $cliente->apellido }}</span>
                            <span class="font-semibold">{{ $cliente->total_proyectos }} proyectos</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 flex justify-between items-center">
                        <span>Presupuesto Promedio</span>
                        <a href="{{ route('dashboard.presupuestos') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Ver detalles →
                        </a>
                    </h3>
                    <div class="space-y-2 cursor-pointer" onclick="window.location.href='{{ route('dashboard.presupuestos') }}'">
                        <div class="flex justify-between hover:bg-gray-50 p-2 rounded">
                            <span>Apps</span>
                            <span class="font-semibold">
                                @if($totalApps > 0)
                                    {{ number_format($presupuestoPromedioApps, 2, ',', '.') }}€
                                @else
                                    0,00€
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between hover:bg-gray-50 p-2 rounded">
                            <span>Webs</span>
                            <span class="font-semibold">
                                @if($totalWebs > 0)
                                    {{ number_format($presupuestoPromedioWebs, 2, ',', '.') }}€
                                @else
                                    0,00€
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 flex justify-between items-center">
                        <span>Rendimiento General</span>
                        <a href="{{ route('dashboard.rendimiento') }}" class="text-sm text-blue-600 hover:text-blue-800">
                            Ver detalles →
                        </a>
                    </h3>
                    <div class="space-y-2 cursor-pointer" onclick="window.location.href='{{ route('dashboard.rendimiento') }}'">
                        <div class="flex justify-between hover:bg-gray-50 p-2 rounded">
                            <span>Proyectos Iniciados</span>
                            <span class="font-semibold">{{ $proyectosIniciados }}</span>
                        </div>
                        <div class="flex justify-between hover:bg-gray-50 p-2 rounded">
                            <span>Proyectos Completados</span>
                            <span class="font-semibold">{{ $proyectosCompletados }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para gráficos -->
    <div id="chartModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle" class="text-2xl font-bold mb-4"></h2>
            <div style="height: 500px;">
                <canvas id="modalChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        let graficos = {};

        function inicializarGraficos() {
            const opcionesComunes = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            };

            // Gráfico de Ingresos
            graficos.ingresos = new Chart(document.getElementById('ingresosTendencia'), {
                type: 'line',
                data: {
                    labels: @json($tendencias['meses']),
                    datasets: [{
                        label: 'Ingresos',
                        data: @json($tendencias['ingresos']),
                        borderColor: '#059669',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    ...opcionesComunes,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '€' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });

            // Gráfico de Tiempo de Desarrollo
            graficos.tiempo = new Chart(document.getElementById('tiempoTendencia'), {
                type: 'line',
                data: {
                    labels: @json($tendencias['meses']),
                    datasets: [{
                        label: 'Días promedio',
                        data: @json($tendencias['tiempos']),
                        borderColor: '#7C3AED',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    ...opcionesComunes,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' días';
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });

            // Gráfico de Tasa de Éxito
            graficos.exito = new Chart(document.getElementById('exitoTendencia'), {
                type: 'line',
                data: {
                    labels: @json($tendencias['meses']),
                    datasets: [{
                        label: 'Tasa de éxito',
                        data: @json($tendencias['tasas_exito']),
                        borderColor: '#D97706',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    ...opcionesComunes,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    }
                }
            });

            // Gráfico de tipo de proyectos
            graficos.tipos = new Chart(document.getElementById('tipoProyectos'), {
                type: 'pie',
                data: {
                    labels: ['Apps', 'Webs'],
                    datasets: [{
                        data: [{{ $totalApps }}, {{ $totalWebs }}],
                        backgroundColor: ['#818CF8', '#34D399']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Gráfico de estado de proyectos
            graficos.estados = new Chart(document.getElementById('estadoProyectos'), {
                type: 'doughnut',
                data: {
                    labels: ['En progreso', 'Completados', 'Cancelados'],
                    datasets: [{
                        data: [
                            {{ $estadisticas['en_progreso'] }}, 
                            {{ $estadisticas['completados'] }}, 
                            {{ $estadisticas['cancelados'] }}
                        ],
                        backgroundColor: ['#FCD34D', '#34D399', '#EF4444']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        async function actualizarRangoTiempo(graficoId, rango) {
            try {
                console.log('Actualizando gráfico:', graficoId, 'con rango:', rango);
                
                const response = await fetch(`{{ route('dashboard.estadisticas') }}?grafico=${graficoId}&rango=${rango}`);
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                
                const datos = await response.json();
                console.log('Datos recibidos:', datos);
                
                let grafico;
                switch(graficoId) {
                    case 'tipoProyectos':
                        grafico = graficos.tipos;
                        grafico.data.datasets[0].data = [datos.apps, datos.webs];
                        break;
                        
                    case 'estadoProyectos':
                        grafico = graficos.estados;
                        grafico.data.datasets[0].data = [
                            datos.en_progreso,
                            datos.completados,
                            datos.cancelados
                        ];
                        break;
                        
                    case 'ingresosTendencia':
                        grafico = graficos.ingresos;
                        grafico.data.labels = datos.meses;
                        grafico.data.datasets[0].data = datos.ingresos;
                        break;
                        
                    case 'tiempoTendencia':
                        grafico = graficos.tiempo;
                        grafico.data.labels = datos.meses;
                        grafico.data.datasets[0].data = datos.tiempos;
                        break;
                        
                    case 'exitoTendencia':
                        grafico = graficos.exito;
                        grafico.data.labels = datos.meses;
                        grafico.data.datasets[0].data = datos.tasas_exito;
                        break;
                }
                
                if (grafico) {
                    grafico.update();
                    console.log('Gráfico actualizado:', graficoId);
                }
            } catch (error) {
                console.error('Error al actualizar el gráfico:', error);
            }
        }

        document.addEventListener('DOMContentLoaded', inicializarGraficos);

        function showChartInModal(chartId, title) {
            const modal = document.getElementById('chartModal');
            const modalTitle = document.getElementById('modalTitle');
            const originalChart = Chart.getChart(chartId);
            
            modalTitle.textContent = title;
            
            const existingModalChart = Chart.getChart('modalChart');
            if (existingModalChart) {
                existingModalChart.destroy();
            }
            
            const modalCanvas = document.getElementById('modalChart');
            new Chart(modalCanvas, {
                type: originalChart.config.type,
                data: JSON.parse(JSON.stringify(originalChart.data)),
                options: {
                    ...originalChart.config.options,
                    maintainAspectRatio: false,
                    responsive: true
                }
            });
            
            modal.style.display = 'block';
        }

        document.querySelector('.close').onclick = function() {
            document.getElementById('chartModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('chartModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        document.querySelectorAll('.bg-white.p-6.rounded-lg.shadow').forEach(container => {
            container.style.cursor = 'pointer';
            container.onclick = function() {
                const title = this.querySelector('h3').textContent;
                const canvas = this.querySelector('canvas');
                showChartInModal(canvas.id, title);
            }
        });
    </script>
</body>
</html>