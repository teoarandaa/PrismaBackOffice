<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Clientes</h1>
            <button onclick="window.location.href='{{ route('clientes.create') }}'" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Nuevo Cliente
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($clientes as $cliente)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-semibold">{{ $cliente->nombre }} {{ $cliente->apellido }}</h2>
                        <p class="text-gray-600">{{ $cliente->email }}</p>
                        <p class="text-gray-500">{{ $cliente->empresa ?: 'Sin empresa' }}</p>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('clientes.proyectos.index', $cliente) }}" 
                           class="bg-green-500 hover:bg-green-700 text-white text-center py-2 px-4 rounded">
                            Ver Proyectos
                        </a>
                        
                        <a href="{{ route('clientes.edit', $cliente) }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white text-center py-2 px-4 rounded">
                            Editar Cliente
                        </a>
                        
                        <button onclick="eliminarCliente({{ $cliente->id }})" 
                                class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">
                            Eliminar Cliente
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        function eliminarCliente(clienteId) {
            if (confirm('¿Estás seguro de que deseas eliminar este cliente? Se eliminarán también todos sus proyectos.')) {
                fetch(`/api/clientes/${clienteId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert('Cliente eliminado correctamente');
                    window.location.reload();
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