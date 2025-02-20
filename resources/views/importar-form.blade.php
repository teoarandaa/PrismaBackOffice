<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar Datos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Importar Datos</h1>
                <button onclick="window.location.href='{{ route('clientes.index') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">Error:</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('importar') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div>
                        <label for="archivo" class="block text-sm font-medium text-gray-700 mb-2">
                            Seleccionar archivo CSV
                        </label>
                        <input type="file" 
                               id="archivo" 
                               name="archivo" 
                               accept=".csv,.txt"
                               required
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100">
                        @error('archivo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Formato esperado del archivo:</h3>
                        <p class="text-xs text-gray-600">
                            El archivo debe ser un CSV con las siguientes secciones:<br>
                            <strong>CLIENTES</strong>: ID, Nombre, Apellido, Email, Teléfono, Empresa, Ciudad, etc.<br>
                            <strong>PROYECTOS</strong>: ID, ID Cliente, Nombre, Descripción, Tipo, etc.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Importar Datos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 