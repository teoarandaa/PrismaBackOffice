<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Proyectos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js'></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-6">
                    <img src="{{ asset('images/prisma_logo.png') }}" alt="Logo de la empresa" class="h-20 w-auto">
                    <div class="border-l-2 border-gray-200 pl-6">
                        <h1 class="text-3xl font-bold text-gray-800">Calendario de Proyectos</h1>
                        <p class="text-gray-600 mt-1">Vista general de todos los proyectos</p>
                    </div>
                </div>
                <a href="{{ route('clientes.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver
                </a>
            </div>
            
            <div class="bg-white rounded-lg p-6">
                <div id="calendario"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendario');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                events: @json($eventos),
                eventDidMount: function(info) {
                    tippy(info.el, {
                        content: info.event.extendedProps.descripcion.replace(/\n/g, '<br>'),
                        allowHTML: true,
                        theme: 'light-border',
                        placement: 'top',
                        interactive: true,
                        maxWidth: 300
                    });
                },
                eventContent: function(arg) {
                    return {
                        html: `<div class="fc-event-title">${arg.event.title}</div>
                               <div class="fc-event-subtitle text-xs">${arg.event.extendedProps.cliente_nombre}</div>`
                    };
                },
                displayEventTime: false,
                dayMaxEvents: 3,
            });
            calendar.render();
        });
    </script>

    <!-- Añadir Tippy.js para tooltips mejorados -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light-border.css"/>

    <style>
        .fc-event {
            cursor: pointer;
            padding: 4px;
        }
        
        .fc-event-title {
            font-weight: 600;
            font-size: 0.9em;
            white-space: normal;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
        }

        .fc-event-subtitle {
            font-size: 0.8em;
            opacity: 0.9;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tippy-content {
            font-size: 14px;
            line-height: 1.5;
            padding: 12px;
        }

        /* Estilo para el botón "+más" */
        .fc-daygrid-more-link {
            color: #6B7280;
            font-weight: 500;
            background: #F3F4F6;
            padding: 2px 4px;
            border-radius: 4px;
            margin-top: 2px;
        }

        .fc-daygrid-more-link:hover {
            background: #E5E7EB;
            color: #374151;
        }
    </style>
</body>
</html> 