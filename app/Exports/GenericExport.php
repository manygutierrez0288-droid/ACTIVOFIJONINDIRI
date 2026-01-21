<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GenericExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $headers;

    public function __construct($data)
    {
        // Convertir colección o datos a un array simple de arrays
        $this->data = $data instanceof \Illuminate\Support\Collection ? $data->toArray() : (array) $data;

        if (count($this->data) > 0) {
            $firstItem = $this->data[0];
            $this->headers = array_keys((array) $firstItem);
        } else {
            $this->headers = [];
        }
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
