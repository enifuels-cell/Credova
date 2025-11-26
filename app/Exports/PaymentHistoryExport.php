<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Facades\Auth;

class PaymentHistoryExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        $user = Auth::user();
        $userBorrowers = $user->borrowers()->pluck('id');

        $payments = \App\Models\Payment::whereHas('loan', function($q) use ($userBorrowers) {
            $q->whereIn('borrower_id', $userBorrowers);
        })
        ->with('loan.borrower')
        ->orderBy('paid_at', 'desc')
        ->get()
        ->map(function($payment) {
            return [
                'Date' => $payment->paid_at->toDateString(),
                'Borrower' => $payment->loan->borrower->fullName(),
                'Borrower Phone' => $payment->loan->borrower->phone,
                'Loan ID' => $payment->loan->id,
                'Principal' => $payment->loan->principal,
                'Amount Paid' => $payment->amount,
                'Method' => $payment->method,
                'Remaining Balance' => $payment->loan->balance,
                'Notes' => $payment->notes ?? '',
            ];
        });

        return $payments;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Borrower',
            'Borrower Phone',
            'Loan ID',
            'Principal',
            'Amount Paid',
            'Method',
            'Remaining Balance',
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
