<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalles del Cliente</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Detalles del Cliente</h1>
                <button onclick="window.location.href='{{ route('clientes.index') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">
                            {{ $cliente->nombre }} {{ $cliente->apellido }}
                        </h2>
                        @if($cliente->empresa)
                            <p class="text-gray-600 mt-1">{{ $cliente->empresa }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Información de Contacto</h3>
                            <div class="mt-2 space-y-2">
                                <p class="text-gray-800">
                                    <span class="font-medium">Email:</span> 
                                    <a href="mailto:{{ $cliente->email }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $cliente->email }}
                                    </a>
                                </p>
                                @if($cliente->telefono)
                                    <p class="text-gray-800">
                                        <span class="font-medium">Teléfono:</span> 
                                        <a href="tel:{{ $cliente->telefono }}" class="text-blue-600 hover:text-blue-800">
                                            {{ $cliente->telefono }}
                                        </a>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Ubicación</h3>
                            <div class="mt-2 space-y-2">
                                @if($cliente->ciudad)
                                    <p class="text-gray-800">
                                        <span class="font-medium">Ciudad:</span> {{ $cliente->ciudad }}
                                    </p>
                                @endif
                                @if($cliente->codigo_postal)
                                    <p class="text-gray-800">
                                        <span class="font-medium">Código Postal:</span> {{ $cliente->codigo_postal }}
                                    </p>
                                @endif
                                @if($cliente->pais)
                                    <p class="text-gray-800">
                                        <span class="font-medium">País:</span> {{ $cliente->pais }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <h3 class="text-sm font-medium text-gray-500 mb-4">Proyectos Asociados</h3>
                        @if($cliente->proyectos->count() > 0)
                            <div class="space-y-4">
                                @foreach($cliente->proyectos as $proyecto)
                                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                                        <div>
                                            <h4 class="font-medium text-gray-800">{{ $proyecto->nombre_proyecto }}</h4>
                                            <p class="text-sm text-gray-600">
                                                Estado: 
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $proyecto->estado === 'En progreso' ? 'bg-yellow-100 text-yellow-800' : 
                                                       ($proyecto->estado === 'Completado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ $proyecto->estado }}
                                                </span>
                                            </p>
                                        </div>
                                        <a href="{{ route('clientes.proyectos.show', [$cliente, $proyecto]) }}" 
                                           class="text-blue-600 hover:text-blue-800">
                                            Ver Detalles
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600">No hay proyectos asociados a este cliente.</p>
                        @endif
                    </div>

                    <div class="mt-6 pt-6 border-t flex justify-end space-x-4">
                        <button onclick="window.location.href='{{ route('clientes.proyectos.index', $cliente) }}'"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Ver Todos los Proyectos
                        </button>
                        <button onclick="window.location.href='{{ route('clientes.edit', $cliente) }}'"
                                class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                            Editar Cliente
                        </button>
                        <button onclick="eliminarCliente()"
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Eliminar Cliente
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function eliminarCliente() {
            if (confirm('¿Estás seguro de que deseas eliminar este cliente? Se eliminarán también todos sus proyectos.')) {
                fetch('{{ route("clientes.destroy", $cliente) }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => Promise.reject(err));
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message);
                    window.location.href = '{{ route('clientes.index') }}';
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error.message) {
                        alert(error.message);
                    } else {
                        alert('Error al eliminar el cliente');
                    }
                });
            }
        }
    </script>
</body>
</html> 