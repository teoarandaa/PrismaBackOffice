<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyectos de {{ $cliente->nombre }} {{ $cliente->apellido }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Proyectos de {{ $cliente->nombre }} {{ $cliente->apellido }}</h1>
                <p class="text-gray-600">{{ $cliente->empresa ?: 'Sin empresa' }}</p>
                <div class="mt-4">
                    <input type="text" 
                           id="buscadorProyectos" 
                           placeholder="Buscar proyectos..." 
                           class="w-full md:w-96 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="space-x-4">
                <button onclick="window.location.href='{{ route('clientes.index') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
                <button onclick="window.location.href='{{ route('clientes.proyectos.create', $cliente) }}'" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Nuevo Proyecto
                </button>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Inicio</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Presupuesto</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($proyectos as $proyecto)
                    <tr data-proyecto-id="{{ $proyecto->id }}">
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
                            <button onclick="window.location.href='{{ route('clientes.proyectos.show', [$cliente, $proyecto]) }}'"
                                    class="text-blue-600 hover:text-blue-900">
                                Detalles
                            </button>
                            <button onclick="window.location.href='{{ route('clientes.proyectos.edit', [$cliente, $proyecto]) }}'"
                                    class="text-yellow-600 hover:text-yellow-900">
                                Editar
                            </button>
                            <button onclick="eliminarProyecto({{ $proyecto->id }})"
                                    class="text-red-600 hover:text-red-900">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Función para filtrar proyectos
        function filtrarProyectos(searchTerm) {
            const rows = document.querySelectorAll('tbody tr');
            searchTerm = searchTerm.toLowerCase();
            
            rows.forEach(row => {
                const nombre = row.querySelector('td:first-child').textContent.toLowerCase();
                const estado = row.querySelector('.rounded-full').textContent.toLowerCase();
                const descripcion = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (nombre.includes(searchTerm) || estado.includes(searchTerm) || descripcion.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Buscador de texto
        document.getElementById('buscadorProyectos').addEventListener('input', (e) => {
            filtrarProyectos(e.target.value);
        });

        function eliminarProyecto(proyectoId) {
            if (confirm('¿Estás seguro de que deseas eliminar este proyecto?')) {
                fetch(`/api/clientes/{{ $cliente->id }}/proyectos/${proyectoId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert('Proyecto eliminado correctamente');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el proyecto');
                });
            }
        }
    </script>
</body>
</html> 