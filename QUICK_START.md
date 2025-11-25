# 🚀 QUICK START GUIDE - Lending Tracker

## Immediate Actions to Get Started

### 1️⃣ Start the Server
```powershell
cd c:\Users\Administrator\lending-tracker
php artisan serve
```

You should see:
```
Laravel development server started on [http://127.0.0.1:8000]
```

### 2️⃣ Open in Browser
Visit: **http://localhost:8000**

You'll be automatically redirected to the Borrowers page.

### 3️⃣ See Demo Data
The system already has sample data:
- **Borrower**: Juan Dela Cruz
- **Loan**: ₱11,000 with 10% interest (₱9,000 remaining)
- **Status**: 40 days overdue

---

## 5-Minute Quick Tour

### Step 1: View All Borrowers (30 seconds)
- You're on the Borrowers page
- See "Juan Dela Cruz" in the list
- Notice: 1 Active Loan, ₱9,000.00 Outstanding Balance

### Step 2: View Borrower Details (1 minute)
- Click "View" next to Juan Dela Cruz
- See:
  - Contact info
  - Stats: 1 loan, active loans, total outstanding
  - Loan details table
  - Option to create new loan

### Step 3: View Loan Details (1 minute)
- From borrower page, click loan in the table
- See:
  - Borrower info
  - Loan stats
  - Payment ledger showing:
    - Loan issued: ₱11,000
    - Payment recorded: -₱2,000
    - Current balance: ₱9,000

### Step 4: Record a Payment (1 minute)
- Scroll to "Record Payment" section
- Enter:
  - Amount: 3000
  - Date: Today
  - Method: Cash
- Click "Record Payment"
- See balance update to ₱6,000

### Step 5: View Aging Report (1 minute)
- Click "Aging Report" in header
- See summary stats for overdue loans
- This loan shows 40+ days overdue

---

## Try These Actions

### Create a New Borrower
```
1. Click "+ Add Borrower" on Borrowers page
2. Fill in:
   - First Name: Maria
   - Last Name: Santos
   - Phone: 09123456789
   - Email: maria@example.com
   - Address: Manila
3. Click "Create Borrower"
```

### Create a New Loan
```
From the new borrower's page:
1. Scroll to "Create New Loan"
2. Fill in:
   - Principal: 50000
   - Interest Rate: 12
   - First Due Date: 2025-12-23
   - Frequency: Monthly
   - Term: 6
3. Click "Create Loan"
```

### Record Another Payment
```
From the loan page:
1. Scroll to "Record Payment"
2. Amount: 5000
3. Method: Bank Transfer
4. Click "Record Payment"
5. Balance automatically updates!
```

---

## Understanding the Numbers

### Loan Example
- **Principal**: ₱10,000 (money borrowed)
- **Interest Rate**: 10%
- **Interest Charged**: ₱1,000 (10% of principal)
- **Total Due**: ₱11,000 (principal + interest)

### Payment Flow
```
Total Due: ₱11,000
- Payment: ₱2,000
= Balance: ₱9,000

Balance: ₱9,000
- Payment: ₱3,000
= Balance: ₱6,000

Balance: ₱6,000
- Payment: ₱6,000
= Balance: ₱0 → Status becomes PAID ✅
```

---

## Buttons & Features

| Action | Where | Purpose |
|--------|-------|---------|
| "+ Add Borrower" | Borrowers page | Create new borrower |
| "View" | Borrowers table | See borrower details |
| "Pay" | Loan table | Quick pay button (future feature) |
| "Create Loan" | Borrower page form | Create new loan |
| "Record Payment" | Loan page form | Add payment to loan |
| "Aging Report" | Header nav | View overdue loans |
| "Back" | Bottom of pages | Return to previous page |

---

## Color Code Reference

### Status Badges
- 🔵 **Blue (Info)**: Active/Current loans
- 🟢 **Green (Success)**: Paid loans
- 🔴 **Red (Danger)**: Defaulted/Overdue loans
- 🟡 **Yellow (Warning)**: Slightly overdue (1-30 days)

### Button Colors
- 🔵 **Blue**: Primary action (view, submit)
- 🟢 **Green**: Success action (pay, record)
- 🟡 **Yellow**: Warning action
- ⚫ **Gray**: Secondary action (cancel, back)
- 🔴 **Red**: Danger action

---

## FAQ

**Q: Where is the data stored?**
A: MySQL database (default name: "laravel")

**Q: Can I edit an existing loan?**
A: Currently, you can only view and add payments. Loans are created once.

**Q: What happens when balance is fully paid?**
A: Status automatically changes to "PAID" ✅

**Q: Can I delete borrowers or loans?**
A: Current version is view/create only. Delete feature can be added.

**Q: How do I reset the demo data?**
A: Run: `php artisan migrate:fresh --seed --seeder=DemoSeeder`

---

## Keyboard Shortcuts

These views are mobile-responsive and work on phones too! Just use a smaller screen to see.

---

## Next Steps

1. ✅ Explore the system with demo data
2. ✅ Create your own borrowers
3. ✅ Create loans and track payments
4. ✅ Check the Aging Report regularly
5. ✅ (Optional) Add more features as needed

---

## Need Help?

Check **SYSTEM_DOCUMENTATION.md** for detailed technical information.

**Version**: 1.0 (November 23, 2025)
**Status**: ✅ Production Ready
