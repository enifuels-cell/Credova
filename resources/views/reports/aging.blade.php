@extends('layouts.app')

@section('title', 'Aging Report')

@section('content')
<div style="background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 40px 20px;">
    <div style="max-width: 1200px; margin: 0 auto;">
        <!-- Header Section -->
        <div style="background: white; border-radius: 12px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
            <h1 style="margin: 0 0 10px 0; font-size: 32px; font-weight: 700; color: #2c3e50;">📊 Aging Report</h1>
            <p style="margin: 0; color: #7f8c8d; font-size: 15px;">
                <em>Outstanding loan balances grouped by how many days past due they are. This report helps identify which loans need immediate attention.</em>
            </p>
        </div>

        <!-- Statistics Cards Section -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #3498db;">
                <h4 style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">Current (Not Due)</h4>
                <div id="current" style="font-size: 24px; font-weight: 700; color: #3498db;">₱0.00</div>
            </div>

            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #f39c12;">
                <h4 style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">1-30 Days Overdue</h4>
                <div id="overdue_1_30" style="font-size: 24px; font-weight: 700; color: #f39c12;">₱0.00</div>
            </div>

            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #e67e22;">
                <h4 style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">31-60 Days Overdue</h4>
                <div id="overdue_31_60" style="font-size: 24px; font-weight: 700; color: #e67e22;">₱0.00</div>
            </div>

            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #e74c3c;">
                <h4 style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">61-90 Days Overdue</h4>
                <div id="overdue_61_90" style="font-size: 24px; font-weight: 700; color: #e74c3c;">₱0.00</div>
            </div>

            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #c0392b;">
                <h4 style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">90+ Days Overdue</h4>
                <div id="overdue_gt_90" style="font-size: 24px; font-weight: 700; color: #c0392b;">₱0.00</div>
            </div>

            <div style="background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-left: 5px solid #27ae60;">
                <h4 style="margin: 0 0 10px 0; color: #7f8c8d; font-size: 13px; font-weight: 600; text-transform: uppercase;">Total Outstanding</h4>
                <div id="total" style="font-size: 24px; font-weight: 700; color: #27ae60;">₱0.00</div>
            </div>
        </div>

        <!-- Detailed Table Section -->
        <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
            <h3 style="margin: 0 0 20px 0; font-size: 20px; font-weight: 700; color: #2c3e50;">📋 Detailed Aging List</h3>

            <div id="loadingMessage" style="text-align: center; padding: 60px 20px;">
                <p style="color: #7f8c8d; font-size: 16px;"><em>Loading report...</em></p>
            </div>

            <table id="agingTable" style="display: none; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #f8f9fa; border-bottom: 2px solid #ecf0f1;">
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #2c3e50; font-size: 14px;">Borrower</th>
                        <th style="padding: 15px; text-align: left; font-weight: 600; color: #2c3e50; font-size: 14px;">Loan ID</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #2c3e50; font-size: 14px;">Principal</th>
                        <th style="padding: 15px; text-align: right; font-weight: 600; color: #2c3e50; font-size: 14px;">Balance</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #2c3e50; font-size: 14px;">Due Date</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #2c3e50; font-size: 14px;">Days Overdue</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #2c3e50; font-size: 14px;">Status</th>
                        <th style="padding: 15px; text-align: center; font-weight: 600; color: #2c3e50; font-size: 14px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="agingTableBody">
                </tbody>
            </table>
        </div>
    </div>
</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('{{ route("api.reports.aging") }}')
                .then(response => response.json())
                .then(data => {
                    // Update summary stats
                    document.getElementById('current').textContent = '₱' + parseFloat(data.current || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    document.getElementById('overdue_1_30').textContent = '₱' + parseFloat(data['1_30'] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    document.getElementById('overdue_31_60').textContent = '₱' + parseFloat(data['31_60'] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    document.getElementById('overdue_61_90').textContent = '₱' + parseFloat(data['61_90'] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    document.getElementById('overdue_gt_90').textContent = '₱' + parseFloat(data.gt_90 || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    document.getElementById('total').textContent = '₱' + parseFloat(data.total || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    // Hide loading message
                    document.getElementById('loadingMessage').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error loading aging report:', error);
                    document.getElementById('loadingMessage').innerHTML = '<p style="color: #e74c3c;">Error loading report. Please refresh the page.</p>';
                });

            // Load detailed list
            fetch('/api/loans/aging-details')
                .then(response => response.json())
                .then(loans => {
                    const tbody = document.getElementById('agingTableBody');
                    if (loans.length === 0) {
                        document.getElementById('agingTable').style.display = 'none';
                        document.getElementById('loadingMessage').innerHTML = '<p style="color: #27ae60;"><em>✓ No overdue loans found. Great job!</em></p>';
                    } else {
                        document.getElementById('agingTable').style.display = 'table';
                        loans.forEach((loan, idx) => {
                            const daysOverdue = Math.max(0, Math.floor((new Date() - new Date(loan.first_due_date)) / (1000 * 60 * 60 * 24)));
                            let statusColor = '#3498db';
                            let statusBg = '#ebf5fb';
                            if (daysOverdue > 90) {
                                statusColor = '#c0392b';
                                statusBg = '#fadbd8';
                            } else if (daysOverdue > 60) {
                                statusColor = '#e74c3c';
                                statusBg = '#fadbd8';
                            } else if (daysOverdue > 30) {
                                statusColor = '#e67e22';
                                statusBg = '#fdebd0';
                            } else if (daysOverdue > 0) {
                                statusColor = '#f39c12';
                                statusBg = '#fef5e7';
                            }

                            const row = `
                                <tr style="border-bottom: 1px solid #ecf0f1; ${idx % 2 === 0 ? 'background-color: #f8f9fa;' : ''}">
                                    <td style="padding: 15px; color: #2c3e50;">${loan.borrower.first_name} ${loan.borrower.last_name}</td>
                                    <td style="padding: 15px; color: #3498db; font-weight: 600;">#${loan.id}</td>
                                    <td style="padding: 15px; text-align: right; color: #2c3e50;">₱${parseFloat(loan.principal).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                    <td style="padding: 15px; text-align: right; color: #e74c3c; font-weight: 700;">₱${parseFloat(loan.balance).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                                    <td style="padding: 15px; text-align: center; color: #7f8c8d;">${new Date(loan.first_due_date).toLocaleDateString()}</td>
                                    <td style="padding: 15px; text-align: center;"><span style="background-color: ${statusBg}; color: ${statusColor}; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 13px;">${daysOverdue} days</span></td>
                                    <td style="padding: 15px; text-align: center;"><span style="background-color: #ebf5fb; color: #3498db; padding: 6px 12px; border-radius: 6px; font-weight: 600; font-size: 13px;">${loan.status}</span></td>
                                    <td style="padding: 15px; text-align: center;"><a href="/loans/${loan.id}" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 13px; display: inline-block; transition: transform 0.2s;">View</a></td>
                                </tr>
                            `;
                            tbody.innerHTML += row;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading aging details:', error);
                });
        });
    </script>
@endsection
