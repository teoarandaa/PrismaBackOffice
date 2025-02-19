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
                    <p class="text-3xl font-bold text-blue-600">{{ $proyectosActivos }}</p>
                    <p class="text-sm text-blue-600">En desarrollo</p>
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
                    <h3 class="text-lg font-semibold mb-4">Distribución de Proyectos</h3>
                    <div style="height: 200px;">
                        <canvas id="tipoProyectos"></canvas>
                    </div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4">Estado de Proyectos</h3>
                    <div style="height: 200px;">
                        <canvas id="estadoProyectos"></canvas>
                    </div>
                </div>
            </div>

            <!-- Gráficos de Tendencias -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-green-600">Tendencia de Ingresos</h3>
                    <div style="height: 200px;">
                        <canvas id="ingresosTendencia"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-purple-600">Tendencia Tiempo de Desarrollo</h3>
                    <div style="height: 200px;">
                        <canvas id="tiempoTendencia"></canvas>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4 text-yellow-600">Tendencia Tasa de Éxito</h3>
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
                            <span class="font-semibold">{{ number_format($presupuestoPromedioApps, 2, ',', '.') }}€</span>
                        </div>
                        <div class="flex justify-between hover:bg-gray-50 p-2 rounded">
                            <span>Webs</span>
                            <span class="font-semibold">{{ number_format($presupuestoPromedioWebs, 2, ',', '.') }}€</span>
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
        // Función para clonar un gráfico en el modal
        function showChartInModal(chartId, title) {
            const modal = document.getElementById('chartModal');
            const modalTitle = document.getElementById('modalTitle');
            const originalChart = Chart.getChart(chartId);
            
            // Configurar título
            modalTitle.textContent = title;
            
            // Destruir gráfico modal anterior si existe
            const existingModalChart = Chart.getChart('modalChart');
            if (existingModalChart) {
                existingModalChart.destroy();
            }
            
            // Clonar configuración del gráfico original
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

        // Cerrar modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('chartModal').style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('chartModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Hacer los gráficos clickeables
        document.querySelectorAll('.bg-white.p-6.rounded-lg.shadow').forEach(container => {
            container.style.cursor = 'pointer';
            container.onclick = function() {
                const title = this.querySelector('h3').textContent;
                const canvas = this.querySelector('canvas');
                showChartInModal(canvas.id, title);
            }
        });

        // Gráficos existentes
        new Chart(document.getElementById('tipoProyectos'), {
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

        new Chart(document.getElementById('estadoProyectos'), {
            type: 'doughnut',
            data: {
                labels: ['En Progreso', 'Completados', 'Cancelados'],
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

        // Nuevos gráficos de tendencias
        new Chart(document.getElementById('ingresosTendencia'), {
            type: 'line',
            data: {
                labels: {!! json_encode($tendencias['meses']) !!},
                datasets: [{
                    label: 'Ingresos Mensuales',
                    data: {!! json_encode($tendencias['ingresos']) !!},
                    borderColor: '#10B981',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + '€';
                            }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('tiempoTendencia'), {
            type: 'line',
            data: {
                labels: {!! json_encode($tendencias['meses']) !!},
                datasets: [{
                    label: 'Tiempo Medio (días)',
                    data: {!! json_encode($tendencias['tiempos']) !!},
                    borderColor: '#8B5CF6',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value + ' días';
                            }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('exitoTendencia'), {
            type: 'line',
            data: {
                labels: {!! json_encode($tendencias['meses']) !!},
                datasets: [{
                    label: 'Tasa de Éxito (%)',
                    data: {!! json_encode($tendencias['tasas_exito']) !!},
                    borderColor: '#F59E0B',
                    tension: 0.1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>