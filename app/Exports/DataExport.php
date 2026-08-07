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
    public $charges, $refund, $pending_total;

    public function __construct($dataInputs, $charges, $refund, $pending_total)
    {
        $this->dataInputs = $dataInputs;
        $this->charges = $charges;
        $this->refund = $refund;
        $this->pending_total = $pending_total;
    }

    /**
     * Flatten each DataInput + its items into one row per item.
     * Requires $dataInputs to have been loaded with ['user', 'items.boostType'].
     */
    private function flattenRows()
    {
        $rows = collect();

        foreach ($this->dataInputs as $dataInput) {
            $items = $dataInput->items;

            if ($items->isEmpty()) {
                $rows->push([
                    'page_name'     => $dataInput->page_name,
                    'customer_name' => $dataInput->customer_name,
                    'serviced_by'   => $dataInput->user->name ?? 'N/A',
                    'service_type'  => 'N/A',
                    'start_date'    => 'N/A',
                    'quantity'      => 0,
                    'price'         => 0,
                    'discount'      => 0,
                    'line_total'    => 0,
                    'record_total'  => number_format($dataInput->total_amount, 2),
                    'status'        => $dataInput->status->label(),
                    'remark'        => $dataInput->remark,
                ]);
                continue;
            }

            foreach ($items as $item) {
                $rows->push([
                    'page_name'     => $dataInput->page_name,
                    'customer_name' => $dataInput->customer_name,
                    'serviced_by'   => $dataInput->user->name ?? 'N/A',
                    'service_type'  => $item->boostType->name ?? 'N/A',
                    'start_date'    => $item->start_date
                        ? \Carbon\Carbon::parse($item->start_date)->format('Y-m-d')
                        : 'N/A',
                    'quantity'      => $item->amount,
                    'price'         => number_format($item->mm_kyat, 2),
                    'discount'      => number_format($item->discount, 2),
                    'line_total'    => number_format($item->line_total, 2),
                    'record_total'  => number_format($dataInput->total_amount, 2),
                    'status'        => $dataInput->status->label(),
                    'remark'        => $dataInput->remark,
                ]);
            }
        }

        return $rows;
    }

    /**
     * Overall total across every filtered DataInput (regardless of status),
     * counted once per record — NOT summed from the flattened per-item rows,
     * since that would double-count records with multiple items.
     */
    private function overallTotal(): float
    {
        return (float) $this->dataInputs->sum('total_amount');
    }

    public function styles(Worksheet $sheet)
    {
        // First (summary) table header — row 1
        $sheet->getStyle("A1:F1")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Second (detail) table header — row 4
        $secondTableStartRow = 4;
        $sheet->getStyle("A{$secondTableStartRow}:M{$secondTableStartRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Grand-total row at the bottom of the detail table — bold it
        $lastRow = $secondTableStartRow + 1 + $this->flattenRows()->count();
        $sheet->getStyle("A{$lastRow}:M{$lastRow}")->applyFromArray([
            'font' => ['bold' => true],
        ]);

        return [];
    }

    public function columnFormats(): array
    {
        return [
            // Detail table columns: A No, B Page, C Cus, D ServicedBy, E ServiceType,
            // F StartDate, G Qty, H Price, I Discount, J LineTotal, K RecordTotal, L Status, M Remark
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
            'K' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }

    public function view(): View
    {
        return view('exports.data-input-report', [
            'rows'          => $this->flattenRows(),
            'charges'       => $this->charges,
            'refund'        => $this->refund,
            'pending_total' => $this->pending_total,
            'overall_total' => $this->overallTotal(),
        ]);
    }
}
