<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Proyecto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Editar Proyecto</h1>
                    <p class="text-gray-600">Cliente: {{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                </div>
                <button onclick="window.location.href='{{ route('clientes.proyectos.index', $cliente) }}'" 
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Volver
                </button>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6">
                <form id="editForm" class="space-y-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre del Proyecto</label>
                        <input type="text" id="nombre" name="nombre" value="{{ $proyecto->nombre_proyecto }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $proyecto->descripcion }}</textarea>
                    </div>

                    <div>
                        <label for="fecha_inicio" class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                        <input type="date" id="fecha_inicio" name="fecha_inicio" value="{{ $proyecto->fecha_inicio }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="fecha_fin_estimada" class="block text-sm font-medium text-gray-700">Fecha de Finalización Estimada</label>
                        <input type="date" id="fecha_fin_estimada" name="fecha_fin_estimada" value="{{ $proyecto->fecha_finalizacion }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="presupuesto" class="block text-sm font-medium text-gray-700">Presupuesto</label>
                        <input type="number" id="presupuesto" name="presupuesto" step="0.01" value="{{ $proyecto->presupuesto }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select id="estado" name="estado"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="en_progreso" {{ $proyecto->estado === 'En progreso' ? 'selected' : '' }}>En Progreso</option>
                            <option value="completado" {{ $proyecto->estado === 'Completado' ? 'selected' : '' }}>Completado</option>
                            <option value="cancelado" {{ $proyecto->estado === 'Cancelado' ? 'selected' : '' }}>Cancelado</option>
                        </select>
                    </div>

                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de Proyecto</label>
                        <select id="tipo" name="tipo"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="web" {{ $proyecto->tipo === 'web' ? 'selected' : '' }}>Sitio Web</option>
                            <option value="app" {{ $proyecto->tipo === 'app' ? 'selected' : '' }}>Aplicación</option>
                        </select>
                    </div>

                    <div>
                        <label for="link" class="block text-sm font-medium text-gray-700">Link del Proyecto</label>
                        <input type="url" id="link" name="link" value="{{ $proyecto->link }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Actualizar Proyecto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                nombre: document.getElementById('nombre').value,
                descripcion: document.getElementById('descripcion').value,
                fecha_inicio: document.getElementById('fecha_inicio').value,
                fecha_fin_estimada: document.getElementById('fecha_fin_estimada').value,
                presupuesto: document.getElementById('presupuesto').value,
                estado: document.getElementById('estado').value,
                tipo: document.getElementById('tipo').value,
                link: document.getElementById('link').value
            };

            fetch('{{ route("clientes.proyectos.update", [$cliente, $proyecto]) }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);
                window.location.href = '{{ route("clientes.proyectos.index", $cliente) }}';
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Error al actualizar el proyecto');
            });
        });
    </script>
</body>
</html> 