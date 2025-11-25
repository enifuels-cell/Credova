@extends('layouts.app')

@section('title', 'Borrowers')

@section('content')
<div style="min-height: calc(100vh - 200px); background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); padding: 30px 20px; margin: -20px -20px 0 -20px;">
    <div style="max-width: 1400px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 style="margin: 0; font-size: 32px; font-weight: 700; color: #1a202c;">👥 Borrowers</h2>
            <a href="#" onclick="document.getElementById('addBorrowerForm').style.display = document.getElementById('addBorrowerForm').style.display === 'none' ? 'block' : 'none'; return false;" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); display: inline-block;">+ Add Borrower</a>
        </div>

    <div id="addBorrowerForm" style="display: none; background: white; padding: 30px; border-radius: 12px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
        <h3 style="margin: 0 0 24px 0; font-size: 20px; font-weight: 700; color: #1a202c;">Add New Borrower</h3>
        <form method="POST" action="{{ route('borrowers.store') }}">
            @csrf
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" name="first_name" required value="{{ old('first_name') }}">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}">
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="{{ old('email') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address">{{ old('address') }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Create Borrower</button>
            <a href="#" onclick="document.getElementById('addBorrowerForm').style.display = 'none'; return false;" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    @if($borrowers->count())
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); overflow-x: auto;">
            <table style="width: 100%; font-size: 14px;">
                <thead>
                    <tr style="border-bottom: 2px solid #e2e8f0; background: #f7fafc;">
                        <th style="text-align: left; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">ID</th>
                        <th style="text-align: left; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Name</th>
                        <th style="text-align: left; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Phone</th>
                        <th style="text-align: left; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Email</th>
                        <th style="text-align: left; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Address</th>
                        <th style="text-align: center; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Loans</th>
                        <th style="text-align: right; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Outstanding</th>
                        <th style="text-align: center; padding: 14px; color: #4a5568; font-weight: 600; font-size: 12px; text-transform: uppercase;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrowers as $borrower)
                    <tr style="border-bottom: 1px solid #e2e8f0; transition: background 0.2s;">
                        <td style="padding: 14px; color: #666;">#{{ $borrower->id }}</td>
                        <td style="padding: 14px;"><strong style="color: #1a202c;">{{ $borrower->fullName() }}</strong></td>
                        <td style="padding: 14px; color: #718096;">{{ $borrower->phone ?? '-' }}</td>
                        <td style="padding: 14px; color: #718096;">{{ $borrower->email ?? '-' }}</td>
                        <td style="padding: 14px; color: #718096;">{{ $borrower->address ?? '-' }}</td>
                        <td style="padding: 14px; text-align: center;">
                            @php
                                $activeLoans = $borrower->loans()->where('status', 'active')->count();
                            @endphp
                            <span style="background: #3498db; color: white; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 12px;">{{ $activeLoans }}</span>
                        </td>
                        <td style="padding: 14px; text-align: right;">
                            @php
                                $balance = $borrower->loans()->where('status', 'active')->sum('balance');
                            @endphp
                            <strong style="color: #e74c3c;">₱{{ number_format($balance, 2) }}</strong>
                        </td>
                        <td style="padding: 14px; text-align: center;">
                            <a href="{{ route('borrowers.show', $borrower->id) }}" style="padding: 6px 12px; background: #667eea; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 12px; display: inline-block;">View</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 30px; display: flex; justify-content: center;">
            {{ $borrowers->links() }}
        </div>
    @else
        <div style="background: white; padding: 50px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); text-align: center;">
            <p style="color: #718096; font-size: 16px; margin: 0;">No borrowers found. <a href="#" onclick="document.getElementById('addBorrowerForm').style.display = 'block'; return false;" style="color: #667eea; text-decoration: none; font-weight: 600;">Create one now →</a></p>
        </div>
    @endif
    </div>
</div>
@endsection
