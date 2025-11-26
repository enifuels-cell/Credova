<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aging Report</title>
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
        <h1>Aging Report</h1>
        <p>Generated on {{ now()->format('F d, Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Borrower Name</th>
                <th>Phone</th>
                <th>Principal</th>
                <th>Balance</th>
                <th>Days Overdue</th>
                <th>Status</th>
                <th>Aging Bucket</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $loan)
                @php
                    $daysPastDue = now()->diffInDays($loan->first_due_date, false) * -1;
                    $bucket = 'Current';
                    if ($daysPastDue > 90) $bucket = '>90 Days';
                    elseif ($daysPastDue > 60) $bucket = '61-90 Days';
                    elseif ($daysPastDue > 30) $bucket = '31-60 Days';
                    elseif ($daysPastDue > 0) $bucket = '1-30 Days';
                @endphp
                <tr>
                    <td>{{ $loan->borrower->fullName() }}</td>
                    <td>{{ $loan->borrower->phone }}</td>
                    <td class="number">&#8369;{{ number_format($loan->principal, 2) }}</td>
                    <td class="number">&#8369;{{ number_format($loan->balance, 2) }}</td>
                    <td class="number">{{ max(0, $daysPastDue) }}</td>
                    <td>{{ ucfirst($loan->status) }}</td>
                    <td><strong>{{ $bucket }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">No loans found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This is a confidential document. © 2025 Credova. All rights reserved.</p>
    </div>
</body>
</html>
