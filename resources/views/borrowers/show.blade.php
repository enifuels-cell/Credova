@extends('layouts.app')

@section('title', 'Borrower Details')

@section('content')
<div style="min-height: calc(100vh - 200px); background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 30px 20px; margin: -20px -20px 0 -20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <!-- Header -->
        <div style="background: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 20px;">
                <div>
                    <h2 style="margin: 0; font-size: 28px; font-weight: 700; color: #1a202c;">{{ $borrower->fullName() }}</h2>
                    <p style="margin: 8px 0 0 0; color: #718096; font-size: 14px;">Borrower Profile</p>
                </div>
                <a href="{{ route('borrowers.index') }}" style="padding: 10px 16px; background: #e2e8f0; color: #1a202c; text-decoration: none; border-radius: 6px; font-weight: 600; display: inline-block;">← Back</a>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-top: 24px;">
                <div>
                    <p style="margin: 0; color: #718096; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Phone</p>
                    <p style="margin: 6px 0 0 0; color: #1a202c; font-weight: 600;">{{ $borrower->phone ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p style="margin: 0; color: #718096; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Email</p>
                    <p style="margin: 6px 0 0 0; color: #1a202c; font-weight: 600;">{{ $borrower->email ?? 'Not provided' }}</p>
                </div>
                <div>
                    <p style="margin: 0; color: #718096; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Member Since</p>
                    <p style="margin: 6px 0 0 0; color: #1a202c; font-weight: 600;">{{ $borrower->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <p style="margin: 0; color: #718096; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Address</p>
                    <p style="margin: 6px 0 0 0; color: #1a202c; font-weight: 600;">{{ $borrower->address ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-top: 4px solid #3498db;">
                <p style="margin: 0; color: #718096; font-size: 12px; font-weight: 600; text-transform: uppercase;">Total Loans</p>
                <p style="margin: 12px 0 0 0; font-size: 32px; font-weight: 700; color: #3498db;">{{ $borrower->loans->count() }}</p>
            </div>
            <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-top: 4px solid #2ecc71;">
                <p style="margin: 0; color: #718096; font-size: 12px; font-weight: 600; text-transform: uppercase;">Active Loans</p>
                <p style="margin: 12px 0 0 0; font-size: 32px; font-weight: 700; color: #2ecc71;">{{ $borrower->loans->where('status', 'active')->count() }}</p>
            </div>
            <div style="background: white; padding: 24px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-top: 4px solid #e74c3c;">
                <p style="margin: 0; color: #718096; font-size: 12px; font-weight: 600; text-transform: uppercase;">Outstanding</p>
                <p style="margin: 12px 0 0 0; font-size: 28px; font-weight: 700; color: #e74c3c;">₱{{ number_format($borrower->loans->where('status', 'active')->sum('balance'), 2) }}</p>
            </div>
        </div>

        <!-- Loans Table -->
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); margin-bottom: 30px;">
            <h3 style="margin: 0 0 24px 0; font-size: 20px; font-weight: 700; color: #1a202c;">📋 Loans</h3>
            @if($borrower->loans->count())
                <div style="overflow-x: auto;">
                    <table style="width: 100%; font-size: 14px;">
                        <thead>
                            <tr style="border-bottom: 2px solid #e2e8f0; background: #f7fafc;">
                                <th style="text-align: left; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Loan ID</th>
                                <th style="text-align: right; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Principal</th>
                                <th style="text-align: right; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Total Due</th>
                                <th style="text-align: right; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Balance</th>
                                <th style="text-align: center; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;\">Interest</th>
                                <th style="text-align: center; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;\">Due Date</th>
                                <th style="text-align: center; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;\">Status</th>
                                <th style="text-align: center; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;\">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrower->loans as $loan)
                            <tr style="border-bottom: 1px solid #e2e8f0;\">\n                                <td style="padding: 14px; color: #666;">#{{ $loan->id }}</td>\n                                <td style="text-align: right; padding: 14px; color: #1a202c; font-weight: 600;\">₱{{ number_format($loan->principal, 2) }}</td>\n                                <td style="text-align: right; padding: 14px; color: #1a202c; font-weight: 600;\">₱{{ number_format($loan->total_due, 2) }}</td>\n                                <td style="text-align: right; padding: 14px; color: #e74c3c; font-weight: 700;\">₱{{ number_format($loan->balance, 2) }}</td>\n                                <td style="text-align: center; padding: 14px; color: #718096;\">{{ $loan->interest_rate }}%</td>\n                                <td style="text-align: center; padding: 14px; color: #718096;\">{{ $loan->first_due_date->format('M d, Y') }}</td>\n                                <td style="text-align: center; padding: 14px;\">\n                                    @if($loan->status === 'active')\n                                        <span style=\"background: #3498db; color: white; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px;\">Active</span>\n                                    @elseif($loan->status === 'paid')\n                                        <span style=\"background: #2ecc71; color: white; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px;\">Paid</span>\n                                    @else\n                                        <span style=\"background: #e74c3c; color: white; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px;\">Defaulted</span>\n                                    @endif\n                                </td>\n                                <td style="text-align: center; padding: 14px;\">\n                                    <a href="{{ route('loans.show', $loan->id) }}" style="padding: 6px 12px; background: #667eea; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 12px; display: inline-block;\">View</a>\n                                </td>\n                            </tr>\n                            @endforeach\n                        </tbody>\n                    </table>\n                </div>\n            @else\n                <p style=\"color: #718096; text-align: center; padding: 40px 20px; margin: 0;\"><em>No loans found for this borrower.</em></p>\n            @endif\n        </div>

    <h3 style="margin-top: 30px; border-bottom: 2px solid #007bff; padding-bottom: 10px;">Create New Loan</h3>
    <form method="POST" action="{{ route('loans.store') }}" style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px;">
        @csrf
        <input type="hidden" name="borrower_id" value="{{ $borrower->id }}">

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="principal"><strong>Principal Amount</strong> <span style="color: red;">*</span></label>
                    <input type="number" id="principal" name="principal" step="0.01" placeholder="Enter amount (e.g., 10000)" required value="{{ old('principal') }}">
                    <small style="color: #666;">The loan amount to be issued</small>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="interest_rate"><strong>Interest Rate</strong> <span style="color: red;">*</span></label>
                    <input type="number" id="interest_rate" name="interest_rate" step="0.01" placeholder="Enter percentage (e.g., 10)" value="{{ old('interest_rate', 0) }}">
                    <small style="color: #666;">Percentage (e.g., 10 for 10%)</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="issued_at"><strong>Issued Date</strong></label>
                    <input type="date" id="issued_at" name="issued_at" value="{{ old('issued_at', date('Y-m-d')) }}">
                    <small style="color: #666;">Date the loan is issued</small>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="first_due_date"><strong>First Due Date</strong> <span style="color: red;">*</span></label>
                    <input type="date" id="first_due_date" name="first_due_date" required value="{{ old('first_due_date') }}">
                    <small style="color: #666;">When the first payment is due</small>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="frequency"><strong>Payment Frequency</strong> <span style="color: red;">*</span></label>
                    <select id="frequency" name="frequency" required>
                        <option value="">-- Choose Payment Frequency --</option>
                        <option value="daily" {{ old('frequency') === 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="weekly" {{ old('frequency') === 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('frequency') === 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                    <small style="color: #666;">How often payment is expected</small>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="term"><strong>Term (number of periods)</strong></label>
                    <input type="number" id="term" name="term" placeholder="e.g., 6" value="{{ old('term') }}">
                    <small style="color: #666;">Total number of payment periods</small>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="notes"><strong>Notes</strong></label>
            <textarea id="notes" name="notes" placeholder="Add any additional notes about this loan...">{{ old('notes') }}</textarea>
            <small style="color: #666;">Optional: Any special terms or notes</small>
        </div>

        <div style="margin-top: 20px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">✓ Create Loan</button>
            <a href="{{ route('borrowers.index') }}" class="btn btn-secondary">← Back</a>
        </div>
    </form>
</div>
@endsection
