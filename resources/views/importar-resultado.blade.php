<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado de Importación</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Resultado de la Importación</h1>
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

                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <strong class="font-bold">¡Éxito!</strong>
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                @if(isset($resumen))
                    <div class="space-y-6">
                        <div class="border-b pb-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Resumen de la Importación</h2>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">Clientes Procesados</p>
                                    <p class="text-2xl font-bold text-blue-600">{{ $resumen['clientes']['total'] }}</p>
                                    <div class="mt-2 text-sm">
                                        <p class="text-green-600">Nuevos: {{ $resumen['clientes']['nuevos'] }}</p>
                                        <p class="text-yellow-600">Actualizados: {{ $resumen['clientes']['actualizados'] }}</p>
                                    </div>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-600">Proyectos Procesados</p>
                                    <p class="text-2xl font-bold text-purple-600">{{ $resumen['proyectos']['total'] }}</p>
                                    <div class="mt-2 text-sm">
                                        <p class="text-green-600">Nuevos: {{ $resumen['proyectos']['nuevos'] }}</p>
                                        <p class="text-yellow-600">Actualizados: {{ $resumen['proyectos']['actualizados'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!empty($resumen['errores']))
                            <div class="border-b pb-4">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Errores Encontrados</h3>
                                <div class="bg-red-50 p-4 rounded-lg">
                                    <ul class="list-disc list-inside space-y-2">
                                        @foreach($resumen['errores'] as $error)
                                            <li class="text-red-600">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <div class="flex justify-end space-x-4">
                            <button onclick="window.location.href='{{ route('clientes.index') }}'"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Ir al Listado de Clientes
                            </button>
                            <button onclick="window.location.reload()"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Importar Más Datos
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html> 