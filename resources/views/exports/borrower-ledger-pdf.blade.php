<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Borrower Ledger</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 20px; }
        .pdf-header { text-align: center; margin-bottom: 30px; border-bottom: 3px solid #0D3B66; padding-bottom: 15px; }
        .pdf-header img { max-width: 150px; height: auto; margin-bottom: 10px; }
        .pdf-header h1 { margin: 0; color: #0D3B66; font-size: 24px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #0D3B66; }
        .header p { margin: 5px 0; color: #666; }
        .borrower-info { background-color: #f0f0f0; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .borrower-info h3 { margin: 0 0 10px 0; color: #0D3B66; }
        .info-row { display: inline-block; width: 48%; margin-right: 2%; }
        .loan-section { margin: 30px 0; page-break-inside: avoid; }
        .loan-title { background-color: #0D3B66; color: white; padding: 10px; margin: 0; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #e0e0e0; padding: 8px; text-align: left; font-weight: bold; border-bottom: 2px solid #0D3B66; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .number { text-align: right; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #666; border-top: 2px solid #0D3B66; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="pdf-header">
        <img src="{{ public_path('credovalogo.png') }}" alt="Credova Logo">
        <h1>Borrower Ledger Report</h1>
    </div>

    <div class="header">
        <p>Generated on {{ now()->format('F d, Y H:i') }}</p>
    </div>

    <div class="borrower-info">
        <h3>{{ $borrower->fullName() }}</h3>
        <div class="info-row">
            <strong>Email:</strong> {{ $borrower->email ?? 'N/A' }}<br>
            <strong>Phone:</strong> {{ $borrower->phone ?? 'N/A' }}
        </div>
        <div class="info-row">
            <strong>Address:</strong> {{ $borrower->address ?? 'N/A' }}<br>
            <strong>Member Since:</strong> {{ $borrower->created_at->format('M d, Y') }}
        </div>
    </div>

    @forelse($ledgers as $ledger)
        <div class="loan-section">
            <h4 class="loan-title">
                Loan #{{ $ledger['loan_id'] }}
                | Principal: &#8369;{{ number_format($ledger['loan']->principal, 2) }}
                | Status: {{ ucfirst($ledger['loan']->status) }}
                | Current Balance: &#8369;{{ number_format($ledger['current_balance'], 2) }}
            </h4>

            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Running Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ledger['entries'] as $entry)
                        <tr>
                            <td>{{ $entry['date'] ?? 'N/A' }}</td>
                            <td>{{ ucfirst(strtolower($entry['type'] ?? '')) }}</td>
                            <td class="number">&#8369;{{ is_numeric($entry['amount']) ? number_format((float)$entry['amount'], 2) : '0.00' }}</td>
                            <td class="number"><strong>&#8369;{{ is_numeric($entry['balance']) ? number_format((float)$entry['balance'], 2) : '0.00' }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p style="text-align: center; color: #999;">No loans found for this borrower</p>
    @endforelse

    <div class="footer">
        <p>This is a confidential document. &copy; 2025 Credova. All rights reserved.</p>
    </div>
</body>
</html>

