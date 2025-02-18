<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Usuario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo de la empresa" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Detalles del Usuario</h1>
                        <p class="text-gray-600 mt-1">Información completa del usuario</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('users.index') }}'" 
                        class="h-[42px] bg-gray-500 hover:bg-gray-700 text-white font-bold px-6 rounded-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </button>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h2 class="text-xl font-semibold mb-4">Información Básica</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Nombre</p>
                            <p class="text-lg">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="text-lg">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Fecha de Registro</p>
                            <p class="text-lg">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h2 class="text-xl font-semibold mb-4">Información Sensible</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Contraseña</p>
                            <div class="relative">
                                <div id="passwordContainer" class="blur-sm select-none">
                                    <p class="text-lg font-mono bg-gray-100 p-2 rounded">
                                        {{ $user->plain_password ?? 'No disponible' }}
                                    </p>
                                </div>
                                <button onclick="showPasswordPrompt()" 
                                        class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Mostrar/Ocultar Contraseña
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para verificar contraseña -->
    <div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Verificar Credenciales de Admin</h3>
            <div class="space-y-4">
                <div>
                    <label for="adminPassword" class="block text-sm font-medium text-gray-700">
                        Contraseña de Administrador
                    </label>
                    <input type="password" 
                           id="adminPassword" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="flex justify-end gap-3">
                    <button onclick="closePasswordModal()" 
                            class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300">
                        Cancelar
                    </button>
                    <button onclick="verifyPassword()" 
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Verificar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showPasswordPrompt() {
            document.getElementById('passwordModal').classList.remove('hidden');
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').classList.add('hidden');
            document.getElementById('adminPassword').value = '';
        }

        function verifyPassword() {
            const password = document.getElementById('adminPassword').value;
            
            fetch('{{ route('verify.admin.password') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ password: password })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const passwordContainer = document.getElementById('passwordContainer');
                    passwordContainer.classList.toggle('blur-sm');
                    passwordContainer.classList.toggle('select-none');
                    closePasswordModal();
                } else {
                    alert('Contraseña incorrecta');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al verificar la contraseña');
            });
        }
    </script>
</body>
</html> 