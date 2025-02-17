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
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Clientes</h1>
                <div class="mt-4 space-y-4">
                    <input type="text" 
                           id="buscadorClientes" 
                           placeholder="Buscar por nombre, email o empresa..." 
                           class="w-full md:w-96 px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="flex flex-wrap gap-4">
                        <select id="filtroProyectos" 
                                class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="todos">Todos los clientes</option>
                            <option value="mas3">> 3 proyectos</option>
                            <option value="menos3">< 3 proyectos</option>
                        </select>
                        
                        <select id="filtroEstado" 
                                class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="todos">Cualquier estado</option>
                            <option value="completado">Con proyectos completados</option>
                            <option value="en_progreso">Con proyectos en progreso</option>
                            <option value="cancelado">Con proyectos cancelados</option>
                        </select>
                        
                        <select id="filtroOrden" 
                                class="px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="nombre">Ordenar por nombre</option>
                            <option value="proyectos">Ordenar por nº proyectos</option>
                            <option value="recientes">Más recientes</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('exportar') }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Exportar CSV
                </a>
                <button onclick="document.getElementById('importForm').classList.toggle('hidden')"
                        class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                    Importar CSV
                </button>
                <button onclick="window.location.href='{{ route('clientes.create') }}'" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Nuevo Cliente
                </button>
            </div>
        </div>

        <div id="importForm" class="hidden mb-6 p-4 bg-white rounded-lg shadow">
            <form action="{{ route('importar') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                @csrf
                <input type="file" name="archivo" accept=".csv,.txt" required
                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0 file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Subir Archivo
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($clientes as $cliente)
            <div class="bg-white rounded-lg shadow-md p-6" 
                 data-cliente-id="{{ $cliente->id }}"
                 data-num-proyectos="{{ $cliente->proyectos->count() }}"
                 data-estados="{{ json_encode($cliente->proyectos->pluck('estado')->unique()->values()) }}"
                 data-fecha="{{ $cliente->created_at }}">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h2 class="text-xl font-semibold">{{ $cliente->nombre }} {{ $cliente->apellido }}</h2>
                        <p class="text-gray-600">{{ $cliente->email }}</p>
                        <p class="text-gray-500">{{ $cliente->empresa ?: 'Sin empresa' }}</p>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <div class="flex flex-col space-y-2">
                        <a href="{{ route('clientes.show', $cliente) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-4 rounded">
                            Ver Detalles
                        </a>
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
        // Función para aplicar todos los filtros
        function aplicarFiltros() {
            const cards = document.querySelectorAll('.grid > div');
            const searchTerm = document.getElementById('buscadorClientes').value.toLowerCase();
            const filtroProyectos = document.getElementById('filtroProyectos').value;
            const filtroEstado = document.getElementById('filtroEstado').value;
            const filtroOrden = document.getElementById('filtroOrden').value;
            
            cards.forEach(card => {
                const nombre = card.querySelector('h2').textContent.toLowerCase();
                const email = card.querySelector('.text-gray-600').textContent.toLowerCase();
                const empresa = card.querySelector('.text-gray-500').textContent.toLowerCase();
                const numProyectos = parseInt(card.getAttribute('data-num-proyectos')) || 0;
                const estados = JSON.parse(card.getAttribute('data-estados') || '[]');
                
                let mostrar = true;
                
                // Filtro de búsqueda
                if (searchTerm) {
                    // Buscar en nombre del cliente, email y empresa
                    const nombreCompleto = nombre.toLowerCase();
                    const emailCliente = email.toLowerCase();
                    const nombreEmpresa = empresa.toLowerCase();
                    
                    mostrar = nombreCompleto.includes(searchTerm) || 
                             emailCliente.includes(searchTerm) || 
                             nombreEmpresa.includes(searchTerm);
                }
                
                // Filtro de número de proyectos
                if (mostrar && filtroProyectos !== 'todos') {
                    switch(filtroProyectos) {
                        case 'mas3':
                            mostrar = numProyectos > 3;
                            break;
                        case 'menos3':
                            mostrar = numProyectos < 3;
                            break;
                    }
                }
                
                // Filtro de estado de proyectos
                if (mostrar && filtroEstado !== 'todos') {
                    mostrar = estados.includes(filtroEstado);
                }
                
                card.style.display = mostrar ? '' : 'none';
            });
            
            // Ordenar las tarjetas
            const grid = document.querySelector('.grid');
            const cardsArray = Array.from(cards);
            
            cardsArray.sort((a, b) => {
                switch(filtroOrden) {
                    case 'nombre':
                        return a.querySelector('h2').textContent.localeCompare(b.querySelector('h2').textContent);
                    case 'proyectos':
                        return (parseInt(b.getAttribute('data-num-proyectos')) || 0) - 
                               (parseInt(a.getAttribute('data-num-proyectos')) || 0);
                    case 'recientes':
                        return new Date(b.getAttribute('data-fecha')) - 
                               new Date(a.getAttribute('data-fecha'));
                    default:
                        return 0;
                }
            });
            
            cardsArray.forEach(card => grid.appendChild(card));
        }

        // Eventos para los filtros
        document.getElementById('buscadorClientes').addEventListener('input', aplicarFiltros);
        document.getElementById('filtroProyectos').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroEstado').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroOrden').addEventListener('change', aplicarFiltros);

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