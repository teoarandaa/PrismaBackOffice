<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestión de Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Estilo para el select cuando está desplegado -->
    <style>
        select#perPage {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            font-size: 0.875rem;
        }

        select#perPage option {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            padding: 12px 16px;
            background-color: white;
            color: #4B5563;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        select#perPage option:hover,
        select#perPage option:focus {
            background-color: #F3F4F6;
        }

        select#perPage option:checked {
            background-color: #E5E7EB;
            color: #1F2937;
        }
    </style>
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
                    <a href="{{ route('calendario.proyectos') }}" 
                       class="h-[42px] bg-teal-500 hover:bg-teal-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                       title="Calendario de Proyectos">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </a>
                    @if(auth()->user()->can_edit || auth()->user()->is_admin)
                    <a href="{{ route('dashboard.kpis') }}" 
                       class="h-[42px] bg-blue-500 hover:bg-blue-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                       title="Dashboard KPIs">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5M8 8v8m-4-5v5m16-5v5M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        </svg>
                    </a>
                    @endif
                    @if(auth()->user()->is_admin)
                    <a href="{{ route('exportar') }}" 
                       class="h-[42px] bg-emerald-500 hover:bg-emerald-700 text-white font-bold px-4 rounded-lg flex items-center justify-center"
                       title="Exportar CSV">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </a>
                    @endif
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

        <!-- Mensaje de no resultados -->
        <div id="noResultados" class="{{ $clientes->count() === 0 ? '' : 'hidden' }}">
            <div class="flex flex-col items-center justify-center py-12">
                <div class="bg-gray-100 rounded-full p-4 mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No se encontraron resultados</h3>
                <p class="text-gray-500">No hay clientes registrados en el sistema</p>
            </div>
        </div>

        <!-- Tabla de clientes -->
        <div id="tablaClientes" class="{{ $clientes->count() === 0 ? 'hidden' : '' }} bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-semibold text-gray-700">Lista de Clientes</h2>
                <div class="relative">
                    <select id="perPage" 
                            class="appearance-none bg-white border border-gray-300 rounded-lg pl-4 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer text-gray-700 hover:border-gray-400 transition-colors duration-200"
                            onchange="cambiarPaginacion(this.value)">
                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20 registros por página</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 registros por página</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 registros por página</option>
                        <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200 registros por página</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Cliente</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Email</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Empresa</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/10">Nº Proyectos</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/5">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($clientes as $cliente)
                        <tr class="hover:bg-gray-50"
                            data-cliente-id="{{ $cliente->id }}"
                            data-num-proyectos="{{ $cliente->proyectos->count() }}"
                            data-estados="{{ json_encode($cliente->proyectos->pluck('estado')->unique()->values()) }}"
                            data-tipos="{{ json_encode($cliente->proyectos->pluck('tipo')->unique()->values()) }}"
                            data-fecha="{{ $cliente->created_at }}">
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 text-center">{{ $cliente->nombre }} {{ $cliente->apellido }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 text-center">{{ $cliente->email }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 text-center">{{ $cliente->empresa ?: 'Sin empresa' }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 text-center">{{ $cliente->proyectos->count() }}</div>
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('clientes.show', $cliente) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 p-2 rounded-lg transition-colors duration-200"
                                       title="Ver Detalles">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('clientes.proyectos.index', $cliente) }}" 
                                       class="bg-green-600 hover:bg-green-700 p-2 rounded-lg transition-colors duration-200"
                                       title="Ver Proyectos">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                    </a>
                                    @if(auth()->user()->can_edit || auth()->user()->is_admin)
                                        <a href="{{ route('clientes.edit', $cliente) }}" 
                                           class="bg-yellow-600 hover:bg-yellow-700 p-2 rounded-lg transition-colors duration-200"
                                           title="Editar Cliente">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <button onclick="eliminarCliente({{ $cliente->id }})" 
                                                class="bg-red-600 hover:bg-red-700 p-2 rounded-lg transition-colors duration-200"
                                                title="Eliminar Cliente">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 border-t">
                {{ $clientes->appends(['per_page' => request('per_page', 20)])->links() }}
            </div>
        </div>
    </div>

    <script>
        function toggleFiltros() {
            const menu = document.getElementById('menuFiltros');
            menu.classList.toggle('hidden');
        }

        // Cerrar el menú de filtros cuando se hace clic fuera
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('menuFiltros');
            const button = event.target.closest('[onclick="toggleFiltros()"]');
            
            if (!menu.contains(event.target) && !button && !menu.classList.contains('hidden')) {
                menu.classList.add('hidden');
            }
        });

        function aplicarFiltros() {
            const searchTerm = document.getElementById('buscadorClientes').value.toLowerCase();
            const filtroProyectos = document.getElementById('filtroProyectos').value;
            const filtroEstado = document.getElementById('filtroEstado').value;
            const filtroOrden = document.getElementById('filtroOrden').value;
            
            const rows = document.querySelectorAll('tbody tr');
            let resultadosEncontrados = false;

            rows.forEach(row => {
                const nombre = row.querySelector('td:first-child').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const empresa = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const numProyectos = parseInt(row.getAttribute('data-num-proyectos')) || 0;
                const estados = JSON.parse(row.getAttribute('data-estados') || '[]');
                
                let mostrar = true;

                // Filtro de búsqueda
                if (searchTerm) {
                    mostrar = nombre.includes(searchTerm) || 
                             email.includes(searchTerm) || 
                             empresa.includes(searchTerm);
                }

                // Filtro de número de proyectos
                if (mostrar && filtroProyectos !== 'todos') {
                    if (filtroProyectos === 'mas3') {
                        mostrar = numProyectos > 3;
                    } else if (filtroProyectos === 'menos3') {
                        mostrar = numProyectos < 3;
                    }
                }

                // Filtro de estado de proyectos
                if (mostrar && filtroEstado !== 'todos') {
                    mostrar = estados.includes(filtroEstado);
                }

                row.style.display = mostrar ? '' : 'none';
                if (mostrar) resultadosEncontrados = true;
            });

            // Actualizar la visibilidad de la tabla y el mensaje de no resultados
            const noResultados = document.getElementById('noResultados');
            const tablaClientes = document.getElementById('tablaClientes');
            
            if (resultadosEncontrados) {
                noResultados.classList.add('hidden');
                tablaClientes.classList.remove('hidden');
            } else {
                noResultados.classList.remove('hidden');
                tablaClientes.classList.add('hidden');
            }

            // Ordenar las filas
            const tbody = document.querySelector('tbody');
            const rowsArray = Array.from(rows);

            rowsArray.sort((a, b) => {
                if (filtroOrden === 'nombre') {
                    const nombreA = a.querySelector('td:first-child').textContent;
                    const nombreB = b.querySelector('td:first-child').textContent;
                    return nombreA.localeCompare(nombreB);
                } else if (filtroOrden === 'proyectos') {
                    const proyectosA = parseInt(a.getAttribute('data-num-proyectos')) || 0;
                    const proyectosB = parseInt(b.getAttribute('data-num-proyectos')) || 0;
                    return proyectosB - proyectosA;
                } else if (filtroOrden === 'recientes') {
                    const fechaA = new Date(a.getAttribute('data-fecha'));
                    const fechaB = new Date(b.getAttribute('data-fecha'));
                    return fechaB - fechaA;
                }
                return 0;
            });

            // Reordenar las filas en la tabla
            rowsArray.forEach(row => tbody.appendChild(row));
        }

        function resetFiltros() {
            document.getElementById('buscadorClientes').value = '';
            document.getElementById('filtroProyectos').value = 'todos';
            document.getElementById('filtroEstado').value = 'todos';
            document.getElementById('filtroOrden').value = 'nombre';
            aplicarFiltros();
        }

        // Eventos para los filtros
        document.addEventListener('DOMContentLoaded', function() {
            const buscador = document.getElementById('buscadorClientes');
            const filtroProyectos = document.getElementById('filtroProyectos');
            const filtroEstado = document.getElementById('filtroEstado');
            const filtroOrden = document.getElementById('filtroOrden');

            if (buscador) buscador.addEventListener('input', aplicarFiltros);
            if (filtroProyectos) filtroProyectos.addEventListener('change', aplicarFiltros);
            if (filtroEstado) filtroEstado.addEventListener('change', aplicarFiltros);
            if (filtroOrden) filtroOrden.addEventListener('change', aplicarFiltros);
        });

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

        function cambiarPaginacion(valor) {
            // Obtener todos los parámetros actuales de la URL
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);
            
            // Actualizar o añadir el parámetro per_page
            params.set('per_page', valor);
            
            // Mantener la página actual en 1 al cambiar el número de elementos por página
            params.set('page', '1');
            
            // Actualizar la URL con los nuevos parámetros
            url.search = params.toString();
            window.location.href = url.toString();
        }

        // Al cargar la página, establecer el valor correcto en el selector
        document.addEventListener('DOMContentLoaded', function() {
            const perPage = new URLSearchParams(window.location.search).get('per_page') || '20';
            const select = document.getElementById('perPage');
            if (select) {
                select.value = perPage;
            }
        });
    </script>
</body>
</html> 