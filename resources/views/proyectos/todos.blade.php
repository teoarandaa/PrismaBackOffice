<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todos los Proyectos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Todos los Proyectos</h1>
                <div class="mt-4 space-y-4">
                    <input type="text" 
                           id="buscadorProyectos" 
                           placeholder="Buscar por nombre de proyecto o cliente..." 
                           class="w-full md:w-96 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="flex flex-wrap gap-4">
                        <select id="filtroEstado" 
                                class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="todos">Todos los estados</option>
                            <option value="En progreso">En progreso</option>
                            <option value="Completado">Completados</option>
                            <option value="Cancelado">Cancelados</option>
                        </select>
                        
                        <div class="flex items-center gap-2">
                            <input type="number" 
                                   id="presupuestoMin" 
                                   placeholder="Presupuesto mín" 
                                   class="w-36 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="text-gray-500">-</span>
                            <input type="number" 
                                   id="presupuestoMax" 
                                   placeholder="Presupuesto máx" 
                                   class="w-36 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="flex items-center gap-2">
                            <input type="date" 
                                   id="fechaInicio" 
                                   class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="text-gray-500">-</span>
                            <input type="date" 
                                   id="fechaFin" 
                                   class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <select id="filtroOrden" 
                                class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="recientes">Más recientes</option>
                            <option value="antiguos">Más antiguos</option>
                            <option value="presupuesto_alto">Mayor presupuesto</option>
                            <option value="presupuesto_bajo">Menor presupuesto</option>
                            <option value="nombre">Nombre del proyecto</option>
                            <option value="cliente">Nombre del cliente</option>
                        </select>
                    </div>
                </div>
            </div>
            <button onclick="window.location.href='{{ route('clientes.index') }}'" 
                    class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Volver
            </button>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proyecto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presupuesto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($proyectos as $proyecto)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $proyecto->cliente->empresa }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $proyecto->nombre_proyecto }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($proyecto->descripcion, 50) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $proyecto->estado === 'En progreso' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($proyecto->estado === 'Completado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ $proyecto->estado }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $proyecto->fecha_inicio ? date('d/m/Y', strtotime($proyecto->fecha_inicio)) : 'No definida' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $proyecto->fecha_finalizacion ? date('d/m/Y', strtotime($proyecto->fecha_finalizacion)) : 'No definida' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            €{{ number_format($proyecto->presupuesto, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                            <a href="{{ route('clientes.proyectos.show', [$proyecto->cliente, $proyecto]) }}"
                               class="text-blue-600 hover:text-blue-900">Detalles</a>
                            <a href="{{ route('clientes.proyectos.edit', [$proyecto->cliente, $proyecto]) }}"
                               class="text-yellow-600 hover:text-yellow-900">Editar</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function aplicarFiltros() {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('tbody tr');
            const filtroEstado = document.getElementById('filtroEstado').value;
            const presupuestoMin = parseFloat(document.getElementById('presupuestoMin').value) || 0;
            const presupuestoMax = parseFloat(document.getElementById('presupuestoMax').value);
            const fechaInicio = document.getElementById('fechaInicio').value;
            const fechaFin = document.getElementById('fechaFin').value;
            const filtroOrden = document.getElementById('filtroOrden').value;
            
            rows.forEach(row => {
                const clienteNombre = row.querySelector('td:first-child').textContent.toLowerCase();
                const proyectoNombre = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const estado = row.querySelector('td:nth-child(3) span').textContent.trim();
                const fechaInicioDate = new Date(row.querySelector('td:nth-child(4)').textContent.trim());
                const presupuesto = parseFloat(row.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.'));
                
                let mostrar = true;

                // Filtro de búsqueda
                if (searchTerm) {
                    mostrar = clienteNombre.includes(searchTerm) || 
                             proyectoNombre.includes(searchTerm);
                }

                // Filtro de estado
                if (mostrar && filtroEstado !== 'todos') {
                    mostrar = estado === filtroEstado;
                }

                // Filtro de presupuesto
                if (presupuestoMin > 0 || presupuestoMax) {
                    mostrar = presupuesto >= presupuestoMin && 
                             (!presupuestoMax || presupuesto <= presupuestoMax);
                }

                // Filtro de fecha
                const fechaInicioFiltro = document.getElementById('fechaInicio').value;
                const fechaFinFiltro = document.getElementById('fechaFin').value;
                if (mostrar && (fechaInicioFiltro || fechaFinFiltro)) {
                    const fechaMinima = new Date(fechaInicioFiltro);
                    mostrar = fechaInicioDate >= fechaMinima;
                    if (fechaFinFiltro && mostrar) {
                        const fechaMaxima = new Date(fechaFinFiltro);
                        mostrar = fechaInicioDate <= fechaMaxima;
                    }
                }

                row.style.display = mostrar ? '' : 'none';
            });
            
            // Ordenar las filas
            const tbody = document.querySelector('tbody');
            const rowsArray = Array.from(rows);
            
            rowsArray.sort((a, b) => {
                switch(filtroOrden) {
                    case 'recientes':
                        return new Date(b.querySelector('td:nth-child(4)').textContent) - 
                               new Date(a.querySelector('td:nth-child(4)').textContent);
                    case 'antiguos':
                        return new Date(a.querySelector('td:nth-child(4)').textContent) - 
                               new Date(b.querySelector('td:nth-child(4)').textContent);
                    case 'presupuesto_alto':
                        return parseFloat(b.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.')) - 
                               parseFloat(a.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.'));
                    case 'presupuesto_bajo':
                        return parseFloat(a.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.')) - 
                               parseFloat(b.querySelector('td:nth-child(6)').textContent.replace('€', '').replace('.', '').replace(',', '.'));
                    case 'nombre':
                        return a.querySelector('td:nth-child(2)').textContent.localeCompare(b.querySelector('td:nth-child(2)').textContent);
                    case 'cliente':
                        return a.querySelector('td:first-child').textContent.localeCompare(b.querySelector('td:first-child').textContent);
                    default:
                        return 0;
                }
            });
            
            rowsArray.forEach(row => tbody.appendChild(row));
        }

        // Eventos para los filtros
        document.getElementById('buscadorProyectos').addEventListener('input', aplicarFiltros);
        document.getElementById('filtroEstado').addEventListener('change', aplicarFiltros);
        document.getElementById('presupuestoMin').addEventListener('input', aplicarFiltros);
        document.getElementById('presupuestoMax').addEventListener('input', aplicarFiltros);
        document.getElementById('fechaInicio').addEventListener('change', aplicarFiltros);
        document.getElementById('fechaFin').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroOrden').addEventListener('change', aplicarFiltros);
    </script>
</body>
</html> 