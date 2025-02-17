<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Proyecto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Detalles del Proyecto</h1>
                    <p class="text-gray-600">Cliente: {{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                </div>
                <button onclick="window.location.href='{{ route('clientes.proyectos.index', $cliente) }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="space-y-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">{{ $proyecto->nombre_proyecto }}</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $proyecto->estado === 'En progreso' ? 'bg-yellow-100 text-yellow-800' : 
                               ($proyecto->estado === 'Completado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                            {{ $proyecto->estado }}
                        </span>
                    </div>

                    <div class="border-t pt-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Descripción</h3>
                        <p class="text-gray-700 whitespace-pre-line">{{ $proyecto->descripcion ?: 'Sin descripción' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 border-t pt-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Fecha de Inicio</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $proyecto->fecha_inicio ? date('d/m/Y', strtotime($proyecto->fecha_inicio)) : 'No definida' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Fecha de Finalización</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $proyecto->fecha_finalizacion ? date('d/m/Y', strtotime($proyecto->fecha_finalizacion)) : 'No definida' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Presupuesto</h3>
                            <p class="mt-1 text-sm text-gray-900">
                                €{{ number_format($proyecto->presupuesto, 2, ',', '.') }}
                            </p>
                        </div>
                        @if($proyecto->link)
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Link del Proyecto</h3>
                            <a href="{{ $proyecto->link }}" target="_blank" 
                               class="mt-1 text-sm text-blue-600 hover:text-blue-800">
                                Visitar proyecto
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-4 border-t pt-4">
                        <button onclick="window.location.href='{{ route('clientes.proyectos.edit', [$cliente, $proyecto]) }}'"
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Editar Proyecto
                        </button>
                        <button onclick="eliminarProyecto()"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Eliminar Proyecto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function eliminarProyecto() {
            if (confirm('¿Estás seguro de que deseas eliminar este proyecto?')) {
                fetch('/api/clientes/{{ $cliente->id }}/proyectos/{{ $proyecto->id }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert('Proyecto eliminado correctamente');
                    window.location.href = '{{ route('clientes.proyectos.index', $cliente) }}';
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