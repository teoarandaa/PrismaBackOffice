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
                    <!-- Encabezado del Cliente -->
                    <div class="mb-8">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="bg-blue-100 rounded-full p-3">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">
                                    {{ $cliente->nombre }} {{ $cliente->apellido }}
                                </h2>
                                @if($cliente->empresa)
                                    <p class="text-gray-600 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $cliente->empresa }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto y Ubicación -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Información de Contacto
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-600">Email:</span>
                                    <a href="mailto:{{ $cliente->email }}" 
                                       class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $cliente->email }}
                                    </a>
                                </div>
                                @if($cliente->telefono)
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-600">Teléfono:</span>
                                        <a href="tel:{{ $cliente->telefono }}" 
                                           class="text-blue-600 hover:text-blue-800 hover:underline">
                                            {{ $cliente->telefono }}
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                Ubicación
                            </h3>
                            <div class="space-y-3">
                                @if($cliente->ciudad)
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-600">Ciudad:</span>
                                        <span class="text-gray-800">{{ $cliente->ciudad }}</span>
                                    </div>
                                @endif
                                @if($cliente->codigo_postal)
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-600">Código Postal:</span>
                                        <span class="text-gray-800">{{ $cliente->codigo_postal }}</span>
                                    </div>
                                @endif
                                @if($cliente->pais)
                                    <div class="flex items-center gap-3">
                                        <span class="text-gray-600">País:</span>
                                        <span class="text-gray-800">{{ $cliente->pais }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Proyectos Asociados -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Proyectos Asociados
                        </h3>
                        @if($cliente->proyectos->count() > 0)
                            <div class="space-y-3">
                                @foreach($cliente->proyectos->take(5) as $proyecto)
                                    <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200">
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
                                            Ver Detalles →
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            
                            @if($cliente->proyectos->count() > 5)
                                <div class="mt-4 text-center">
                                    <a href="{{ route('clientes.proyectos.index', $cliente) }}" 
                                       class="inline-flex items-center text-gray-600 hover:text-gray-900 text-sm font-medium">
                                        Ver todos los proyectos ({{ $cliente->proyectos->count() }})
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        @else
                            <p class="text-gray-600">No hay proyectos asociados a este cliente.</p>
                        @endif
                    </div>

                    <!-- Botones de Acción -->
                    <div class="mt-8 pt-6 border-t flex justify-center gap-4">
                        <button onclick="window.location.href='{{ route('clientes.proyectos.index', $cliente) }}'"
                                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                            Ver Todos los Proyectos
                        </button>
                        @if(auth()->user()->can_edit || auth()->user()->is_admin)
                            <button onclick="window.location.href='{{ route('clientes.edit', $cliente) }}'"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                                Editar Cliente
                            </button>
                            <button onclick="eliminarCliente()"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                                Eliminar Cliente
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function eliminarCliente() {
            if (confirm('¿Está seguro de que desea eliminar este cliente y todos sus proyectos asociados?')) {
                fetch(`/clientes/{{ $cliente->id }}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.message === 'Cliente y sus proyectos eliminados correctamente') {
                        window.location.href = '{{ route('clientes.index') }}';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el cliente');
                });
            }
        }
    </script>
</body>
</html> 