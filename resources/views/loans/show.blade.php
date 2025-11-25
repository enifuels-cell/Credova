@extends('layouts.app')

@section('title', 'Loan Details')

@section('content')
<div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">

        <!-- Header Section -->
        <div style="background: white; border-radius: 12px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="margin: 0 0 5px 0; font-size: 32px; font-weight: 700; color: #2c3e50;">💰 Loan #{{ $loan->id }}</h1>
                <p style="margin: 0; color: #7f8c8d; font-size: 15px;">Loan details and payment tracking</p>
            </div>
            @php
                $statusColor = '#3498db';
                $statusBg = '#ebf5fb';
                if ($loan->status === 'active') {
                    $statusColor = '#f39c12';
                    $statusBg = '#fef5e7';
                } elseif ($loan->status === 'paid') {
                    $statusColor = '#27ae60';
                    $statusBg = '#eafaf1';
                }
            @endphp
            <span style="background: {{ $statusBg }}; color: {{ $statusColor }}; padding: 10px 20px; border-radius: 8px; font-weight: 700; text-transform: uppercase; font-size: 13px;">
                {{ ucfirst($loan->status) }}
            </span>
        </div>

        <!-- Borrower & Statistics Section -->
        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 30px; margin-bottom: 30px;">
            <!-- Borrower Info -->
            <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 700; color: #2c3e50;">👤 Borrower Information</h3>
                <div style="border-bottom: 1px solid #ecf0f1; padding-bottom: 15px; margin-bottom: 15px;">
                    <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 12px; font-weight: 600; text-transform: uppercase;">Name</p>
                    <p style="margin: 0; color: #2c3e50; font-size: 16px; font-weight: 600;"><a href="{{ route('borrowers.show', $loan->borrower_id) }}" style="color: #3498db; text-decoration: none;">{{ $loan->borrower->fullName() }}</a></p>
                </div>
                <div style="border-bottom: 1px solid #ecf0f1; padding-bottom: 15px; margin-bottom: 15px;">
                    <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 12px; font-weight: 600; text-transform: uppercase;">Phone</p>
                    <p style="margin: 0; color: #2c3e50; font-size: 15px;">{{ $loan->borrower->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 12px; font-weight: 600; text-transform: uppercase;">Email</p>
                    <p style="margin: 0; color: #2c3e50; font-size: 15px;">{{ $loan->borrower->email ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #3498db;">
                    <p style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">Principal</p>
                    <p style="margin: 0; font-size: 24px; font-weight: 700; color: #3498db;">₱{{ number_format($loan->principal, 2) }}</p>
                </div>
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #27ae60;">
                    <p style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">Total Due</p>
                    <p style="margin: 0; font-size: 24px; font-weight: 700; color: #27ae60;">₱{{ number_format($loan->total_due, 2) }}</p>
                </div>
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #e74c3c;">
                    <p style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">Balance</p>
                    <p style="margin: 0; font-size: 24px; font-weight: 700; color: #e74c3c;">₱{{ number_format($loan->balance, 2) }}</p>
                </div>
                <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #9b59b6;">
                    <p style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">Interest Rate</p>
                    <p style="margin: 0; font-size: 24px; font-weight: 700; color: #9b59b6;">{{ $loan->interest_rate ?? 0 }}%</p>
                </div>
            </div>
        </div>

        <!-- Loan Details Section -->
        <div style="background: white; border-radius: 12px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #2c3e50;">📋 Loan Details</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="border-bottom: 1px solid #ecf0f1; padding-bottom: 15px;">
                    <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 12px; font-weight: 600; text-transform: uppercase;">First Due Date</p>
                    <p style="margin: 0; color: #2c3e50; font-size: 16px; font-weight: 600;">{{ $loan->first_due_date->format('M d, Y') }}</p>
                </div>
                <div style="border-bottom: 1px solid #ecf0f1; padding-bottom: 15px;">
                    <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 12px; font-weight: 600; text-transform: uppercase;">Issued Date</p>
                    <p style="margin: 0; color: #2c3e50; font-size: 16px; font-weight: 600;">{{ $loan->issued_at?->format('M d, Y') ?? 'N/A' }}</p>
                </div>
                <div style="border-bottom: 1px solid #ecf0f1; padding-bottom: 15px;">
                    <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 12px; font-weight: 600; text-transform: uppercase;">Frequency</p>
                    <p style="margin: 0; color: #2c3e50; font-size: 16px; font-weight: 600;">{{ ucfirst($loan->frequency ?? 'N/A') }}</p>
                </div>
                <div style="border-bottom: 1px solid #ecf0f1; padding-bottom: 15px;">
                    <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 12px; font-weight: 600; text-transform: uppercase;">Term</p>
                    <p style="margin: 0; color: #2c3e50; font-size: 16px; font-weight: 600;">{{ $loan->term ?? 'N/A' }} periods</p>
                </div>
                <div style="border-bottom: 1px solid #ecf0f1; padding-bottom: 15px;">
                    <p style="margin: 0 0 5px 0; color: #7f8c8d; font-size: 12px; font-weight: 600; text-transform: uppercase;">Days Past Due</p>
                    <p style="margin: 0; color: #2c3e50; font-size: 16px; font-weight: 600;">
                        @php
                            $daysPastDue = now()->diffInDays($loan->first_due_date, false);
                        @endphp
                        @if ($daysPastDue < 0)
                            <span style="background: #ebf5fb; color: #3498db; padding: 4px 8px; border-radius: 4px; font-weight: 600;">{{ abs($daysPastDue) }} days remaining</span>
                        @else
                            <span style="background: #fadbd8; color: #c0392b; padding: 4px 8px; border-radius: 4px; font-weight: 600;">{{ $daysPastDue }} days overdue</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Payment Ledger Section -->
        <div style="background: white; border-radius: 12px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #2c3e50;">📊 Payment Ledger</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #f8f9fa; border-bottom: 2px solid #ecf0f1;">
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #2c3e50;">Date</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #2c3e50;">Type</th>
                            <th style="padding: 15px; text-align: right; font-weight: 600; color: #2c3e50;">Amount</th>
                            <th style="padding: 15px; text-align: right; font-weight: 600; color: #2c3e50;">Balance</th>
                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #2c3e50;">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ledger as $idx => $entry)
                        <tr style="border-bottom: 1px solid #ecf0f1; {{ $idx % 2 === 0 ? 'background-color: #f8f9fa;' : '' }}">
                            <td style="padding: 15px; color: #2c3e50;">{{ $entry['date'] ?? 'N/A' }}</td>
                            <td style="padding: 15px;">
                                @if($entry['type'] === 'issued')
                                    <span style="background: #ebf5fb; color: #3498db; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 12px;">Issued</span>
                                @else
                                    <span style="background: #eafaf1; color: #27ae60; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 12px;">Payment</span>
                                @endif
                            </td>
                            <td style="padding: 15px; text-align: right; color: #2c3e50; font-weight: 600;">₱{{ number_format($entry['amount'], 2) }}</td>
                            <td style="padding: 15px; text-align: right; color: #e74c3c; font-weight: 700;">₱{{ number_format($entry['balance'], 2) }}</td>
                            <td style="padding: 15px; color: #7f8c8d;">{{ $entry['note'] ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Record Payment Form -->
        @if($loan->status === 'active')
        <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 25px 0; font-size: 20px; font-weight: 700; color: #2c3e50;">💳 Record Payment</h3>
            <form method="POST" action="{{ route('payments.store') }}">
                @csrf
                <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                <input type="hidden" name="borrower_id" value="{{ $loan->borrower_id }}">

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; color: #2c3e50; font-weight: 600; margin-bottom: 8px;">Amount *</label>
                        <input type="number" name="amount" step="0.01" required value="{{ old('amount') }}" max="{{ $loan->balance }}" style="width: 100%; padding: 12px; border: 2px solid #ecf0f1; border-radius: 8px; font-size: 15px; transition: border-color 0.2s;" placeholder="Enter payment amount">
                        <small style="color: #7f8c8d; margin-top: 5px; display: block;">Maximum: ₱{{ number_format($loan->balance, 2) }}</small>
                    </div>
                    <div>
                        <label style="display: block; color: #2c3e50; font-weight: 600; margin-bottom: 8px;">Payment Date *</label>
                        <input type="date" name="paid_at" required value="{{ old('paid_at', date('Y-m-d')) }}" style="width: 100%; padding: 12px; border: 2px solid #ecf0f1; border-radius: 8px; font-size: 15px; transition: border-color 0.2s;">
                    </div>
                    <div>
                        <label style="display: block; color: #2c3e50; font-weight: 600; margin-bottom: 8px;">Payment Method</label>
                        <select name="method" style="width: 100%; padding: 12px; border: 2px solid #ecf0f1; border-radius: 8px; font-size: 15px; transition: border-color 0.2s;">
                            <option value="cash" {{ old('method') === 'cash' ? 'selected' : '' }}>💵 Cash</option>
                            <option value="bank_transfer" {{ old('method') === 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                            <option value="online" {{ old('method') === 'online' ? 'selected' : '' }}>💻 Online</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; color: #2c3e50; font-weight: 600; margin-bottom: 8px;">Notes</label>
                    <textarea name="notes" style="width: 100%; padding: 12px; border: 2px solid #ecf0f1; border-radius: 8px; font-size: 15px; min-height: 100px; resize: vertical; transition: border-color 0.2s;" placeholder="Add any notes about this payment...">{{ old('notes') }}</textarea>
                </div>

                <div style="display: flex; gap: 12px;">
                    <button type="submit" style="background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; font-size: 15px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(39, 174, 96, 0.3)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        ✓ Record Payment
                    </button>
                    <a href="{{ route('borrowers.show', $loan->borrower_id) }}" style="background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 15px; display: inline-flex; align-items: center; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                        ← Back
                    </a>
                </div>
            </form>
        </div>
        @else
        <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-align: center;">
            <p style="margin: 0 0 10px 0; color: #27ae60; font-size: 18px; font-weight: 700;">✓ This loan is already paid</p>
            <p style="margin: 0; color: #7f8c8d;">No further payments can be recorded for this loan.</p>
            <a href="{{ route('borrowers.show', $loan->borrower_id) }}" style="margin-top: 15px; background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 15px; display: inline-flex; align-items: center; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                ← Back to Borrower
            </a>
        </div>
        @endif

    </div>
</div>
@endsection
