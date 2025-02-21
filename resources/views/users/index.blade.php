<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo de la empresa" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Gestión de Usuarios</h1>
                        <p class="text-gray-600 mt-1">Panel de administración de usuarios del sistema</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-4 border-l pl-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="h-[42px] bg-gray-500 hover:bg-gray-700 text-white font-bold px-6 rounded-lg flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <input type="text" 
                           id="buscadorUsuarios" 
                           placeholder="Buscar por nombre o email..." 
                           class="w-96 h-[42px] px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('register') }}" 
                       class="h-[42px] bg-blue-500 hover:bg-blue-700 text-white font-bold px-6 rounded-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Nuevo Usuario
                    </a>
                    <button onclick="window.location.href='{{ route('clientes.index') }}'" 
                            class="h-[42px] bg-gray-500 hover:bg-gray-700 text-white font-bold px-6 rounded-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver
                    </button>
                </div>
            </div>
        </div>

        <!-- Mensaje de no resultados -->
        <div id="noResultados" class="{{ $users->where('id', '!=', 1)->count() === 0 ? '' : 'hidden' }}">
            <div class="flex flex-col items-center justify-center py-12">
                <div class="bg-gray-100 rounded-full p-4 mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No se encontraron resultados</h3>
                <p class="text-gray-500">No hay usuarios registrados en el sistema</p>
            </div>
        </div>

        <!-- Grid de usuarios -->
        <div id="gridUsuarios" class="{{ $users->where('id', '!=', 1)->count() === 0 ? 'hidden' : '' }} grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($users as $user)
                @if($user->id !== 1)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
                                <p class="text-gray-600">{{ $user->email }}</p>
                                <p class="text-sm text-gray-500">Registrado: {{ $user->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('users.show', $user) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white text-center py-2 px-4 rounded">
                                    Ver Detalles
                                </a>
                                <a href="{{ route('users.edit', $user) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-700 text-white text-center py-2 px-4 rounded">
                                    Editar Usuario
                                </a>
                                <button onclick="eliminarUsuario({{ $user->id }})" 
                                        class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">
                                    Eliminar Usuario
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <script>
        function eliminarUsuario(userId) {
            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                fetch(`/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Usuario eliminado correctamente') {
                        alert('Usuario eliminado correctamente');
                        window.location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al eliminar el usuario');
                });
            }
        }

        // Buscador de usuarios
        document.getElementById('buscadorUsuarios').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.grid > div');
            const noResultados = document.getElementById('noResultados');
            const gridUsuarios = document.getElementById('gridUsuarios');
            let resultadosEncontrados = false;
            
            // Si no hay usuarios (excepto el admin), mostrar el mensaje
            if (cards.length === 0) {
                noResultados.classList.remove('hidden');
                gridUsuarios.classList.add('hidden');
                return;
            }
            
            cards.forEach(card => {
                const name = card.querySelector('h2').textContent.toLowerCase();
                const email = card.querySelector('.text-gray-600').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || email.includes(searchTerm)) {
                    card.style.display = '';
                    resultadosEncontrados = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // Mostrar/ocultar mensaje de no resultados
            if (resultadosEncontrados) {
                noResultados.classList.add('hidden');
                gridUsuarios.classList.remove('hidden');
            } else {
                noResultados.classList.remove('hidden');
                gridUsuarios.classList.add('hidden');
            }
        });
    </script>
</body>
</html> 