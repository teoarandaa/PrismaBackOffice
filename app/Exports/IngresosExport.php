<?php

namespace App\Exports;

use App\Models\Proyecto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IngresosExport implements FromCollection, WithHeadings, WithMapping
{
    protected $type;
    protected $year;

    public function __construct($type, $year = null)
    {
        $this->type = $type;
        $this->year = $year;
    }

    public function collection()
    {
        $query = Proyecto::with('cliente')
            ->where('estado', 'Completado');

        if ($this->type === 'year' && $this->year) {
            $query->whereYear('updated_at', $this->year);
        }

        return $query->orderBy('updated_at')->get();
    }

    public function headings(): array
    {
        return [
            'Proyecto',
            'Cliente',
            'Tipo',
            'Fecha Completado',
            'Ingresos (€)'
        ];
    }

    public function map($proyecto): array
    {
        return [
            $proyecto->nombre_proyecto,
            $proyecto->cliente->nombre . ' ' . $proyecto->cliente->apellido,
            ucfirst($proyecto->tipo),
            $proyecto->updated_at->format('d/m/Y'),
            $proyecto->presupuesto
        ];
    }
} 