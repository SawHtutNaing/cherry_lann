<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DataExport implements FromView, WithStyles
{
    public $dataInputs;
    public $charges, $refund, $pending_total, $overall_total;

    public function __construct($dataInputs, $charges, $refund, $pending_total, $overall_total)
    {
        $this->dataInputs = $dataInputs;
        $this->charges = $charges;
        $this->refund = $refund;
        $this->pending_total = $pending_total;
        $this->overall_total = $overall_total;
    }

    /**
     * How many rows the detail table renders (one per item, or one fallback
     * row for a DataInput with no items) — needed to scope styles/formats
     * to just those rows instead of the whole column.
     */
    private function detailRowCount(): int
    {
        return $this->dataInputs->sum(function ($dataInput) {
            return max(1, $dataInput->items->count());
        });
    }

    public function styles(Worksheet $sheet)
    {
        // First (summary) table header — row 1
        // Campaing, Charge, Refund, Total, Pending, Overall Total = 6 columns, A–F
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
        // No, Page Name, Cus Name, Serviced By, Service Type, Start Date,
        // Quantity, Amount, Discount, Total Amount, Status, Remark = 12 columns, A–L
        $secondTableStartRow = 4;
        $sheet->getStyle("A{$secondTableStartRow}:L{$secondTableStartRow}")->applyFromArray([
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

        // Number/date formats scoped to ONLY the detail table's data rows.
        // (Using WithColumnFormatting here would apply to the whole column,
        // which collides with the summary table above — e.g. column F is
        // "Start Date" in the detail table but "Overall Total" in the
        // summary table, so a whole-column date format corrupts that cell.)
        $firstDataRow = $secondTableStartRow + 1; // row 5
        $lastDataRow = $secondTableStartRow + $this->detailRowCount(); // row 4 + N

        if ($this->detailRowCount() > 0) {
            $sheet->getStyle("F{$firstDataRow}:F{$lastDataRow}")
                ->getNumberFormat()
                ->setFormatCode('dd/mm/yy');

            $sheet->getStyle("H{$firstDataRow}:J{$lastDataRow}")
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
        }

        // Auto-size every column so nothing ever renders as ### again.
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return [];
    }

    public function view(): View
    {
        return view('exports.data-input-report', [
            'dataInputs'    => $this->dataInputs,
            'charges'       => $this->charges,
            'refund'        => $this->refund,
            'pending_total' => $this->pending_total,
            'overall_total' => $this->overall_total,
        ]);
    }
}
