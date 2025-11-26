<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BorrowerLedgerExport implements FromCollection, WithHeadings, WithStyles
{
    protected $borrower;

    public function __construct($borrower)
    {
        $this->borrower = $borrower;
    }

    public function collection()
    {
        $entries = collect();

        foreach ($this->borrower->loans as $loan) {
            $balance = $loan->total_due;

            $entries->push([
                'Date' => $loan->issued_at ? $loan->issued_at->toDateString() : 'N/A',
                'Type' => 'Loan Issued',
                'Loan ID' => $loan->id,
                'Principal' => $loan->principal,
                'Interest Rate' => $loan->interest_rate . '%',
                'Amount' => $loan->total_due,
                'Running Balance' => $balance,
                'Status' => $loan->status,
                'Notes' => 'Loan issued with ' . $loan->frequency . ' payment frequency',
            ]);

            foreach ($loan->payments()->orderBy('paid_at')->get() as $payment) {
                $balance -= $payment->amount;
                $entries->push([
                    'Date' => $payment->paid_at->toDateString(),
                    'Type' => 'Payment',
                    'Loan ID' => $loan->id,
                    'Principal' => '',
                    'Interest Rate' => '',
                    'Amount' => -$payment->amount,
                    'Running Balance' => round($balance, 2),
                    'Status' => 'Received',
                    'Notes' => 'Payment via ' . $payment->method,
                ]);
            }
        }

        return $entries->sortBy('Date');
    }

    public function headings(): array
    {
        return [
            'Date',
            'Type',
            'Loan ID',
            'Principal',
            'Interest Rate',
            'Amount',
            'Running Balance',
            'Status',
            'Notes',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '0D3B66']],
            ],
        ];
    }
}
