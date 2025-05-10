<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DataExport implements FromView, WithStyles, WithColumnFormatting
{
    public $dataInputs;
    public $charges, $refund , $pending_total;

    public function __construct($dataInputs, $charges, $refund , $pending_total)
    {
        $this->dataInputs = $dataInputs;
        $this->charges = $charges;
        $this->refund = $refund;
        $this->pending_total = $pending_total;
    }

    public function styles(Worksheet $sheet)
    {
        // Get the last row of the first table dynamically
        $firstTableLastRow = count($this->dataInputs) + 1; // Assuming header is in row 1

        // Apply header styling to ONLY the first table's thead
        $sheet->getStyle("A1:H1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10, // Font size for headers
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'], // Yellow background
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Get the start row of the second table dynamically
        $secondTableStartRow = $firstTableLastRow + 2; // Add some spacing

        // Apply header styling ONLY to the second table's thead
        $sheet->getStyle("A{$secondTableStartRow}:D{$secondTableStartRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10, // Font size for headers
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'], // Yellow background
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        return [];
    }



    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_DATE_YYYYMMDD, // Start Date format
            'F' => NumberFormat::FORMAT_NUMBER_00, // Amount format (2 decimal places)
        ];
    }

    public function view(): View
    {
        return view('livewire.report', [
            'dataInputs' => $this->dataInputs,
            'isExport' => true,
            'charges' => $this->charges,
            'refund' => $this->refund,
            'pending_total' => $this->pending_total
        ]);
    }
}
