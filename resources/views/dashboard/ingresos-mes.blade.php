<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresos {{ $mes }}/{{ $año }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-green-600">Ingresos {{ $mes }}/{{ $año }}</h1>
                        <p class="text-gray-600">Detalle de ingresos por proyecto</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('dashboard.ingresos') }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <div class="mb-8 bg-green-50 p-6 rounded-lg">
                <h2 class="text-2xl font-bold text-green-800 mb-2">Total del Mes</h2>
                <p class="text-4xl font-bold text-green-600">{{ number_format($total, 2, ',', '.') }}€</p>
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-green-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Proyecto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Fecha Completado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-green-800 uppercase tracking-wider">Ingresos</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($proyectos as $proyecto)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->nombre_proyecto }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->cliente->nombre }} {{ $proyecto->cliente->apellido }}</td>
                            <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $proyecto->tipo }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $proyecto->updated_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ number_format($proyecto->presupuesto, 2, ',', '.') }}€</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function toggleExportMenu() {
            const menu = document.getElementById('exportMenu');
            menu.classList.toggle('hidden');
        }

        // Cerrar el menú cuando se hace clic fuera
        window.onclick = function(event) {
            if (!event.target.matches('.export-btn')) {
                const menu = document.getElementById('exportMenu');
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            }
        }
    </script>
</body>
</html> 