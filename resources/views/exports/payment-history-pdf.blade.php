<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment History</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #0D3B66; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #0D3B66; color: white; padding: 10px; text-align: left; font-weight: bold; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .number { text-align: right; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment History</h1>
        <p>Generated on {{ now()->format('F d, Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Borrower</th>
                <th>Loan ID</th>
                <th>Amount Paid</th>
                <th>Method</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>{{ $payment->paid_at->format('M d, Y') }}</td>
                    <td>{{ $payment->loan->borrower->fullName() }}</td>
                    <td>{{ $payment->loan->id }}</td>
                    <td class="number">&#8369;{{ number_format($payment->amount, 2) }}</td>
                    <td>{{ ucfirst($payment->method) }}</td>
                    <td class="number">&#8369;{{ number_format($payment->loan->balance, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No payments found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This is a confidential document. © 2025 Credova. All rights reserved.</p>
    </div>
</body>
</html>
