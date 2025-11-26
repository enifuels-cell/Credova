# Credova - Lending Management System

A modern, mobile-first lending management and collection tracking platform built with Laravel and pure CSS. Designed specifically for lenders and financial institutions to manage loan portfolios efficiently.

## Core Features

### Borrower Management
- **Add, edit, delete borrowers** - Complete borrower lifecycle management
- **Store personal info** - Name, email, phone number, address
- **Quick search/filter** - Search by name, phone, or borrower status
- **Duplicate prevention** - Automatic validation to prevent duplicate phone/email entries

### Loan Management
- **Loan records** - Track principal, interest rate, term, and payment frequency
- **Auto-calculations** - Automatically calculates total due and remaining balance
- **Loan status tracking** - Active, overdue, or paid status
- **Multiple payment frequencies** - Daily, weekly, twice-monthly, or monthly payments
- **Custom payment terms** - Support for 1-1825 day custom loan durations

### Payment Tracking
- **Record payments** - Log payments with date, amount, and method (cash, bank, online)
- **Automatic balance updates** - Loan balance updates automatically after payment
- **Flexible payments** - Support for both partial and full payments
- **Payment history** - Complete chronological record of all payments

### Collection Tracking
- **Assign collectors** - Assign collectors to specific loans
- **Log collection attempts** - Record date, outcome, and amount collected
- **Track history** - Complete audit trail of all collection activities
- **Collection metrics** - Monitor collection success rates per collector

### Reports & Analytics
- **Aging report** - Break down overdue loans: current, 1–30, 31–60, 61–90, >90 days
- **Outstanding balances** - View outstanding balances per borrower
- **Collection metrics** - Track collection success rate per collector
- **Portfolio overview** - Dashboard with key financial metrics and ROI
- **Active loans tracking** - Monitor count and status of active loans

### Borrower Ledger
- **Chronological record** - Complete history of loans, payments, and collection attempts per borrower
- **Running balance** - Automatic calculation of running balance for each transaction
- **Full audit trail** - Complete visibility into borrower transaction history

## Technology Stack

- **Backend**: Laravel 11 (PHP)
- **Frontend**: Blade templates with pure CSS (no Tailwind CDK)
- **Database**: SQLite
- **Build Tool**: Vite
- **Authentication**: Laravel session-based with CSRF protection
- **Testing**: PHPUnit with 18+ test cases

## System Architecture

### Database Schema
- **Users** - System users with roles (admin, collector, user)
- **Borrowers** - Borrower information and contact details
- **Loans** - Loan records with principal, interest, and payment frequency
- **Payments** - Payment transaction history
- **Collectors** - Collector profiles and assignments
- **Collection Attempts** - Log of all collection activities

### API Endpoints
- `POST /api/borrowers` - Create new borrower
- `POST /api/loans` - Create new loan
- `GET /api/borrowers` - Fetch user's borrowers
- `GET /api/loans/aging-details` - Get aging analysis
- `GET /api/recent-payments` - Fetch recent payments
- `GET /api/reports/aging` - Generate aging report

## Mobile-First Design

The platform is built with a mobile-first approach featuring:
- Responsive layout for mobile (≤640px), tablet (480-768px), and desktop (≥1024px)
- Touch-optimized interface
- Viewport locking to prevent unwanted zoom/pan
- Smooth animations and transitions

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & npm
- SQLite

### Quick Start

```bash
# Clone repository
git clone <repository-url>
cd lending-tracker

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Seed test data (optional)
php artisan db:seed --class=DemoSeeder

# Build frontend assets
npm run build

# Start development server
php artisan serve
```

### Test Credentials

For local development testing:
- **Admin**: admin@test.com / password123
- **Collector**: collector@test.com / password123
- **User**: user@test.com / password123

## Testing

Run the full test suite:

```bash
php artisan test --no-coverage
```

Run specific test:

```bash
php artisan test tests/Feature/AccountCreationTest.php
```

All tests pass with 100% success rate (18 tests, 62 assertions).

## Features Status

✅ **Implemented**
- Complete borrower management
- Loan creation and tracking
- Payment recording and balance updates
- Collection attempt logging
- Dashboard with financial metrics
- Mobile-first responsive design
- Authentication and user roles
- API endpoints for all operations
- Comprehensive test coverage

🔄 **In Development**
- Report export to Excel/PDF
- Advanced filtering and search
- Bulk operations

## Browser Support

- Chrome/Edge (latest)
- Firefox (latest)
- Safari (latest)
- Mobile browsers (iOS Safari, Chrome Android)

## License

This project is proprietary software licensed for use by authorized users only.

## Support

For issues or questions, contact the development team.
