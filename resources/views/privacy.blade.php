<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Privacy Policy - Credova Lending Tracker</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Inter', sans-serif;
      background: linear-gradient(180deg, #F5F7FA 0%, #F5F7FA 100%);
      color: #1A1F2C;
      line-height: 1.7;
      padding: 40px 20px;
    }

    .policy-container {
      max-width: 900px;
      margin: 0 auto;
    }

    .back-button {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 24px;
      background: #134376;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      margin-bottom: 40px;
      transition: all 0.3s ease;
      letter-spacing: 0.3px;
    }

    .back-button:hover {
      background: #0F2D5F;
      transform: translateX(-4px);
    }

    .back-arrow {
      font-size: 18px;
    }

    .policy-header {
      background: #FFFFFF;
      border: 1px solid #DDE2EB;
      border-radius: 12px;
      padding: 40px;
      margin-bottom: 40px;
      animation: slideIn 0.5s ease-out;
    }

    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .policy-header h1 {
      font-size: 36px;
      font-weight: 700;
      color: #134376;
      margin-bottom: 12px;
      letter-spacing: -0.5px;
    }

    .policy-header .last-updated {
      font-size: 14px;
      color: #64748B;
      font-weight: 500;
    }

    .policy-content {
      background: #FFFFFF;
      border: 1px solid #DDE2EB;
      border-radius: 12px;
      padding: 40px;
      animation: slideIn 0.5s ease-out 0.1s both;
    }

    .policy-section {
      margin-bottom: 36px;
    }

    .policy-section:last-child {
      margin-bottom: 0;
    }

    .policy-section h2 {
      font-size: 20px;
      font-weight: 700;
      color: #134376;
      margin-bottom: 16px;
      letter-spacing: -0.3px;
    }

    .policy-section p {
      font-size: 15px;
      color: #475569;
      margin-bottom: 12px;
      line-height: 1.8;
    }

    .policy-section p:last-child {
      margin-bottom: 0;
    }

    .policy-section ul {
      margin-left: 24px;
      margin-bottom: 12px;
    }

    .policy-section li {
      font-size: 15px;
      color: #475569;
      margin-bottom: 8px;
      line-height: 1.8;
    }

    .policy-section li:last-child {
      margin-bottom: 0;
    }

    .highlight {
      background: rgba(19, 67, 118, 0.08);
      padding: 20px;
      border-left: 4px solid #134376;
      border-radius: 4px;
      margin: 20px 0;
      font-size: 14px;
      color: #475569;
    }

    .contact-info {
      background: #F5F7FA;
      padding: 20px;
      border-radius: 8px;
      margin-top: 12px;
      font-size: 14px;
      color: #475569;
    }

    .contact-info p {
      margin-bottom: 8px;
      font-size: 14px;
    }

    .contact-info p:last-child {
      margin-bottom: 0;
    }

    footer {
      margin-top: 60px;
    }

    .footer {
      background: #FFFFFF;
      border-top: 1px solid #DDE2EB;
      border-bottom: 1px solid #DDE2EB;
      padding: 40px 20px;
      text-align: center;
    }

    .footer-divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, #D9DEE8, transparent);
      margin: 0 0 30px 0;
    }

    .footer-divider:last-of-type {
      margin: 30px 0 0 0;
    }

    .footer-content {
      max-width: 900px;
      margin: 0 auto;
    }

    .footer-copyright {
      font-size: 14px;
      color: #134376;
      font-weight: 700;
      margin-bottom: 16px;
      letter-spacing: 0.3px;
    }

    .footer-links {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 12px;
      margin-bottom: 16px;
      flex-wrap: wrap;
    }

    .footer-links a {
      color: #475569;
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .footer-links a:hover {
      color: #134376;
    }

    .footer-separator {
      color: #CBD5E1;
      font-size: 13px;
    }

    .footer-powered {
      font-size: 12px;
      color: #94A3B8;
      font-weight: 400;
      letter-spacing: 0.3px;
    }
  </style>
</head>
<body>
  <div class="policy-container">
    <a href="{{ route('dashboard') }}" class="back-button">
      <span class="back-arrow">←</span>
      Dashboard
    </a>

    <div class="policy-header">
      <h1>Privacy Policy</h1>
      <p class="last-updated">Last updated: November 25, 2025</p>
    </div>

    <div class="policy-content">
      <div class="policy-section">
        <p>Credova operates a lending management and monitoring software-as-a-service (SaaS) platform exclusively for registered lenders and financial institutions. Credova does NOT offer loans, accept loan applications from borrowers, or handle borrower funds in any way. We only provide tools for lenders to manage their own loan portfolios.</p>
      </div>

      <div class="policy-section">
        <h2>Information we collect from you (the Lender / User)</h2>
        <ul>
          <li>Company name, business registration details, BSP/SEC licenses (if applicable)</li>
          <li>Name, position, email, and contact number of authorized users</li>
          <li>Billing and subscription information</li>
          <li>Login credentials and activity logs</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Information you upload about your borrowers (processed on your behalf)</h2>
        <p>When you use Credova to monitor your own borrowers, you may upload:</p>
        <ul>
          <li>Borrower names, contact details, loan amounts, payment records, IDs, etc.</li>
        </ul>
        <p>You remain the Data Controller of this borrower data. Credova acts only as your Data Processor under the Data Privacy Act.</p>
      </div>

      <div class="policy-section">
        <h2>How we use the information</h2>
        <ul>
          <li>To provide and maintain the Credova platform</li>
          <li>To send system notifications, billing invoices, and support messages</li>
          <li>To improve the service and generate anonymized analytics</li>
          <li>To comply with legal obligations (court orders, NPC, BIR, AMLC, etc.)</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>How we protect all data</h2>
        <ul>
          <li>Industry-standard encryption (in transit and at rest)</li>
          <li>Secure cloud hosting with regular penetration testing</li>
          <li>Role-based access control and audit logs</li>
          <li>Regular data privacy training for all staff</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Data retention</h2>
        <ul>
          <li>Your account data: retained while your subscription is active + 3 years</li>
          <li>Borrower data you upload: retained only as long as you keep it in the system (you control deletion)</li>
          <li>After account termination, all data is permanently deleted within 30 days unless required by law</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Your responsibilities as the Lender (Data Controller)</h2>
        <ul>
          <li>You must have lawful basis and borrower consent to upload their data</li>
          <li>You must inform your borrowers that their data is processed using Credova</li>
          <li>You remain responsible for responding to borrower rights requests (access, correction, deletion, etc.)</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Your rights</h2>
        <ul>
          <li>Access, correct, or delete your account data anytime</li>
          <li>Export or permanently delete all borrower records you uploaded</li>
          <li>Request a Data Processing Agreement (DPA) – available upon request</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Our Data Protection Officer (DPO)</h2>
        <div class="contact-info">
          <p><strong>Email:</strong> dpo@credova.ph</p>
          <p><strong>Address:</strong> [Your complete office address]</p>
          <p><strong>Phone:</strong> [Your contact number]</p>
        </div>
      </div>

      <div class="policy-section">
        <h2>Changes to this policy</h2>
        <p>We will notify you via email at least 30 days before any material changes take effect.</p>
      </div>

      <div class="policy-section highlight">
        <p>By using Credova, you acknowledge that you have read and understood this Privacy Policy.</p>
        <p style="margin-top: 12px;">Thank you for trusting Credova – the secure monitoring platform built exclusively for lenders.</p>
      </div>

      <div class="policy-section">
        <p style="font-weight: 600; color: #134376;">© 2025 Credova. All rights reserved.</p>
      </div>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-divider"></div>
    <div class="footer-content">
      <p class="footer-copyright">© 2025 Credova. All rights reserved.</p>
      <div class="footer-links">
        <a href="{{ route('privacy') }}">Privacy Policy</a>
        <span class="footer-separator">•</span>
        <a href="{{ route('terms') }}">Terms of Service</a>
        <span class="footer-separator">•</span>
        <a href="{{ route('cookies') }}">Cookie Policy</a>
        <span class="footer-separator">•</span>
        <a href="#">Contact Us</a>
      </div>
      <p class="footer-powered">Powered by Credova Lending Intelligence</p>
    </div>
    <div class="footer-divider"></div>
  </footer>
</body>
</html>
