<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class AgingReportExport implements FromCollection, WithHeadings, WithStyles
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $today = now()->toDateString();
        $loans = \App\Models\Loan::where('status', '!=', 'paid')
            ->with('borrower')
            ->get()
            ->map(function($loan) use ($today) {
                $daysPastDue = now()->diffInDays($loan->first_due_date, false) * -1;
                $bucket = 'Current';
                if ($daysPastDue > 90) $bucket = '>90 Days';
                elseif ($daysPastDue > 60) $bucket = '61-90 Days';
                elseif ($daysPastDue > 30) $bucket = '31-60 Days';
                elseif ($daysPastDue > 0) $bucket = '1-30 Days';

                return [
                    'Borrower Name' => $loan->borrower->fullName(),
                    'Email' => $loan->borrower->email,
                    'Phone' => $loan->borrower->phone,
                    'Principal' => $loan->principal,
                    'Interest Rate' => $loan->interest_rate . '%',
                    'Total Due' => $loan->total_due,
                    'Balance' => $loan->balance,
                    'Status' => $loan->status,
                    'Days Overdue' => max(0, $daysPastDue),
                    'Aging Bucket' => $bucket,
                    'First Due Date' => $loan->first_due_date->toDateString(),
                ];
            });

        return $loans;
    }

    public function headings(): array
    {
        return [
            'Borrower Name',
            'Email',
            'Phone',
            'Principal',
            'Interest Rate',
            'Total Due',
            'Balance',
            'Status',
            'Days Overdue',
            'Aging Bucket',
            'First Due Date',
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
