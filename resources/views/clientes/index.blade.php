<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo de la empresa" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Gestión de Clientes</h1>
                        <p class="text-gray-600 mt-1">Panel de administración de clientes</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-4">
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
                           id="buscadorClientes" 
                           placeholder="Buscar por nombre, email o empresa..." 
                           class="w-96 h-[42px] px-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="relative inline-block text-left">
                        <button type="button" 
                                onclick="toggleFiltros()"
                                class="h-[42px] inline-flex items-center px-4 rounded-lg bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 justify-center">
                            <svg class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filtros
                        </button>

                        <div id="menuFiltros" 
                             class="hidden origin-top-right absolute right-0 mt-2 w-80 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none z-50">
                            <div class="p-4 flex justify-between items-center">
                                <h3 class="text-sm font-medium text-gray-900">Filtros</h3>
                                <button onclick="resetFiltros()" 
                                        class="text-sm text-red-600 hover:text-red-800 font-medium flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Resetear filtros
                                </button>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Número de proyectos</h3>
                                <select id="filtroProyectos" 
                                        class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="todos">Todos los clientes</option>
                                    <option value="mas3">> 3 proyectos</option>
                                    <option value="menos3">< 3 proyectos</option>
                                </select>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Estado de proyectos</h3>
                                <select id="filtroEstado" 
                                        class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="todos">Cualquier estado</option>
                                    <option value="Completado">Con proyectos completados</option>
                                    <option value="En progreso">Con proyectos en progreso</option>
                                    <option value="Cancelado">Con proyectos cancelados</option>
                                </select>
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-medium text-gray-900 mb-3">Ordenar por</h3>
                                <select id="filtroOrden" 
                                        class="w-full px-3 py-2 rounded-md border border-gray-300 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="nombre">Ordenar por nombre</option>
                                    <option value="proyectos">Ordenar por nº proyectos</option>
                                    <option value="recientes">Más recientes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('proyectos.todos') }}" 
                       class="h-[42px] bg-indigo-500 hover:bg-indigo-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                       title="Ver Todos los Proyectos">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </a>
                    <a href="{{ route('dashboard.kpis') }}" 
                       class="h-[42px] bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                       title="Dashboard KPIs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5M8 8v8m-4-5v5m16-5v5M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        </svg>
                    </a>
                    <a href="{{ route('exportar') }}" 
                       class="h-[42px] bg-emerald-500 hover:bg-emerald-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                       title="Exportar CSV">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </a>
                    @if(auth()->user()->can_edit || auth()->user()->is_admin)
                        <button onclick="document.getElementById('importForm').classList.toggle('hidden')"
                                class="h-[42px] bg-purple-500 hover:bg-purple-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                                title="Importar CSV">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </button>
                        <a href="{{ route('clientes.create') }}" 
                           class="h-[42px] bg-cyan-500 hover:bg-cyan-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                           title="Nuevo Cliente">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </a>
                    @endif
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('users.index') }}" 
                           class="h-[42px] bg-amber-500 hover:bg-amber-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                           title="Gestionar Usuarios">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </a>
                        <a href="{{ route('register') }}" 
                           class="h-[42px] bg-rose-500 hover:bg-rose-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                           title="Nuevo Usuario">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if(auth()->user()->can_edit || auth()->user()->is_admin)
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
        @endif

        @if($clientes->count() > 0)
            <div id="noResultados" class="hidden col-span-full">
                <div class="flex flex-col items-center justify-center py-12">
                    <div class="bg-gray-100 rounded-full p-4 mb-4">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No se encontraron resultados</h3>
                    <p class="text-gray-500">Prueba con otros términos de búsqueda o filtros</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($clientes as $cliente)
                <div class="bg-white rounded-lg shadow-md p-6" 
                     data-cliente-id="{{ $cliente->id }}"
                     data-num-proyectos="{{ $cliente->proyectos->count() }}"
                     data-estados="{{ json_encode($cliente->proyectos->pluck('estado')->unique()->values()) }}"
                     data-tipos="{{ json_encode($cliente->proyectos->pluck('tipo')->unique()->values()) }}"
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
                            
                            @if(auth()->user()->can_edit || auth()->user()->is_admin)
                                <a href="{{ route('clientes.edit', $cliente) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-700 text-white text-center py-2 px-4 rounded">
                                    Editar Cliente
                                </a>
                                
                                <button onclick="eliminarCliente({{ $cliente->id }})" 
                                        class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded">
                                    Eliminar Cliente
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="flex flex-col items-center justify-center space-y-4">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900">No hay clientes registrados</h3>
                    <p class="text-gray-500">Comienza agregando tu primer cliente haciendo clic en el botón "Nuevo Cliente".</p>
                    <a href="{{ route('clientes.create') }}" 
                       class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Agregar Cliente
                    </a>
                </div>
            </div>
        @endif
    </div>

    <script>
        function toggleFiltros() {
            const menu = document.getElementById('menuFiltros');
            menu.classList.toggle('hidden');
            
            // Cerrar el menú al hacer clic fuera
            document.addEventListener('click', function(event) {
                const isClickInside = menu.contains(event.target) || 
                                    event.target.closest('button')?.contains(event.target);
                if (!isClickInside && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            });
        }

        // Función para aplicar todos los filtros
        function aplicarFiltros() {
            const cards = document.querySelectorAll('.grid > div:not(#noResultados)');
            const searchTerm = document.getElementById('buscadorClientes').value.toLowerCase();
            const filtroProyectos = document.getElementById('filtroProyectos').value;
            const filtroEstado = document.getElementById('filtroEstado').value;
            const filtroTipo = document.getElementById('filtroTipo').value;
            const filtroOrden = document.getElementById('filtroOrden').value;
            let resultadosEncontrados = false;
            
            cards.forEach(card => {
                const nombre = card.querySelector('h2').textContent.toLowerCase();
                const email = card.querySelector('.text-gray-600').textContent.toLowerCase();
                const empresa = card.querySelector('.text-gray-500').textContent.toLowerCase();
                const numProyectos = parseInt(card.getAttribute('data-num-proyectos')) || 0;
                const estados = JSON.parse(card.getAttribute('data-estados') || '[]');
                const tipos = JSON.parse(card.getAttribute('data-tipos') || '[]');
                
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
                
                // Filtro de tipo de proyecto
                if (mostrar && filtroTipo !== 'todos') {
                    mostrar = tipos.includes(filtroTipo);
                }
                
                card.style.display = mostrar ? '' : 'none';
                if (mostrar) resultadosEncontrados = true;
            });
            
            // Mostrar/ocultar mensaje de no resultados
            const noResultados = document.getElementById('noResultados');
            noResultados.style.display = resultadosEncontrados ? 'none' : 'block';
            
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
        document.getElementById('filtroTipo').addEventListener('change', aplicarFiltros);
        document.getElementById('filtroOrden').addEventListener('change', aplicarFiltros);

        function eliminarCliente(clienteId) {
            if (confirm('¿Está seguro de que desea eliminar este cliente?')) {
                fetch(`/clientes/${clienteId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
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
                    window.location.reload();
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

        function resetFiltros() {
            document.getElementById('buscadorClientes').value = '';
            document.getElementById('filtroProyectos').value = 'todos';
            document.getElementById('filtroEstado').value = 'todos';
            document.getElementById('filtroTipo').value = 'todos';
            document.getElementById('filtroOrden').value = 'nombre';
            aplicarFiltros();
        }
    </script>
</body>
</html> 