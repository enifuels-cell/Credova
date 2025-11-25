<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Terms of Service - Credova Lending Tracker</title>
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
      <h1>Terms of Service</h1>
      <p class="last-updated">Last updated: November 25, 2025</p>
    </div>

    <div class="policy-content">
      <div class="policy-section">
        <p>Credova is a lending-management SaaS platform for licensed lenders only. We do NOT provide loans to the public, accept borrowers, or handle borrower money.</p>
        <p>By signing up, accessing, or using Credova (the "Service"), you agree to these Terms of Service.</p>
      </div>

      <div class="policy-section">
        <h2>Acceptance of Terms</h2>
        <p>These Terms form a legally binding agreement between you (the "Lender" or "User") and Credova. If you do not agree, you may not use the Service.</p>
      </div>

      <div class="policy-section">
        <h2>Service Description</h2>
        <p>Credova provides a cloud-based software platform that enables registered lenders to securely upload, monitor, track, and manage their own loan portfolios and borrower records.</p>
      </div>

      <div class="policy-section">
        <h2>Eligibility & Registration</h2>
        <ul>
          <li>You must be a legitimate lending company, cooperative, or licensed financial institution operating in the Philippines.</li>
          <li>You warrant that all registration information is true, accurate, and complete.</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Subscription & Payment</h2>
        <ul>
          <li>Access is granted on a paid subscription basis (monthly or annual).</li>
          <li>Fees are non-refundable except as expressly stated.</li>
          <li>We may suspend or terminate access for non-payment after 15 days past due.</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Your Responsibilities</h2>
        <ul>
          <li>You are solely responsible for all borrower data you upload.</li>
          <li>You must have lawful basis and borrower consent before uploading personal information.</li>
          <li>You agree to comply with the Data Privacy Act of 2012 (RA 10173), BSP/SEC regulations, Anti-Money Laundering laws, and all applicable laws.</li>
          <li>You will inform your borrowers that Credova is used to process their data.</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Prohibited Use</h2>
        <p>You may not:</p>
        <ul>
          <li>Use Credova for illegal, unlicensed, or predatory lending</li>
          <li>Share your account with unauthorized persons or competitors</li>
          <li>Attempt to copy, modify, reverse-engineer, or resell the Service</li>
          <li>Upload viruses or malicious code</li>
          <li>Use the Service to harass, abuse, or harm others</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Account Termination</h2>
        <ul>
          <li>You may cancel your subscription anytime with 30 days' notice.</li>
          <li>We may immediately terminate or suspend your account for breach of these Terms or illegal activity.</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Data & Privacy</h2>
        <ul>
          <li>You own your data. We act only as your Data Processor (see our Privacy Policy and Data Processing Agreement).</li>
          <li>Upon cancellation, you have 30 days to export your data. After that, it will be permanently deleted.</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Intellectual Property</h2>
        <p>Credova's software, logo, design, and all content (except your uploaded data) are owned by Credova and protected by copyright and intellectual property laws.</p>
      </div>

      <div class="policy-section">
        <h2>Limitation of Liability</h2>
        <ul>
          <li>The Service is provided "as-is" without warranties.</li>
          <li>Credova is not liable for your lending decisions, borrower disputes, or regulatory penalties.</li>
          <li>Our maximum liability shall not exceed the amount you paid us in the last 12 months.</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>Indemnification</h2>
        <p>You agree to indemnify and hold Credova harmless from any claims arising from your use of the Service or violation of laws.</p>
      </div>

      <div class="policy-section">
        <h2>Governing Law</h2>
        <p>These Terms shall be governed by the laws of the Republic of the Philippines. Any dispute shall be resolved exclusively in the courts of [Your City/Metro Manila].</p>
      </div>

      <div class="policy-section">
        <h2>Changes to Terms</h2>
        <p>We may update these Terms. We will notify you by email at least 30 days before material changes take effect.</p>
      </div>

      <div class="policy-section">
        <h2>Contact Us</h2>
        <div class="contact-info">
          <p><strong>Email:</strong> support@credova.ph</p>
          <p><strong>Address:</strong> [Your complete office address]</p>
        </div>
      </div>

      <div class="policy-section highlight">
        <p>By continuing to use Credova, you confirm that you have read, understood, and agree to these Terms of Service.</p>
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
