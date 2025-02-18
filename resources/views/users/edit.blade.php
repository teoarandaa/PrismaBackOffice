<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo" class="mx-auto h-20 w-auto">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Editar Usuario
                </h2>
            </div>
            <form class="mt-8 space-y-6" action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="name" class="sr-only">Nombre</label>
                        <input id="name" name="name" type="text" required value="{{ $user->name }}"
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Nombre">
                    </div>
                    <div>
                        <label for="email" class="sr-only">Email</label>
                        <input id="email" name="email" type="email" required value="{{ $user->email }}"
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Email">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Nueva Contraseña</label>
                        <input id="password" name="password" type="password"
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Nueva contraseña (dejar en blanco para mantener la actual)">
                    </div>
                    <div>
                        <label for="password_confirmation" class="sr-only">Confirmar Nueva Contraseña</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                               class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm" 
                               placeholder="Confirmar nueva contraseña">
                    </div>
                </div>

                <div class="space-y-4 border-t pt-4">
                    <h3 class="text-lg font-medium text-gray-900">Permisos del Usuario</h3>
                    
                    <div class="space-y-2">
                        <select id="role" name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="read" {{ $user->can_read && !$user->can_edit && !$user->is_admin ? 'selected' : '' }}>
                                Solo Lectura (Ver clientes y proyectos)
                            </option>
                            <option value="edit" {{ $user->can_edit && !$user->is_admin ? 'selected' : '' }}>
                                Editor (Crear, editar y eliminar clientes y proyectos)
                            </option>
                            <option value="admin" {{ $user->is_admin ? 'selected' : '' }}>
                                Administrador (Gestión completa del sistema)
                            </option>
                        </select>
                        <p class="mt-1 text-sm text-gray-500">
                            Seleccione el nivel de acceso para este usuario
                        </p>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="text-red-500 text-sm">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-between">
                    <button type="button" 
                            onclick="window.location.href='{{ route('users.index') }}'"
                            class="group relative flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Volver
                    </button>
                    <button type="submit" 
                            class="group relative flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 