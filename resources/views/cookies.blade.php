<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cookie Policy - Credova Lending Tracker</title>
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

    .cookie-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 14px;
    }

    .cookie-table thead {
      background: #F5F7FA;
    }

    .cookie-table th {
      padding: 12px;
      text-align: left;
      font-weight: 700;
      color: #134376;
      border: 1px solid #DDE2EB;
    }

    .cookie-table td {
      padding: 12px;
      border: 1px solid #DDE2EB;
      color: #475569;
    }

    .cookie-table tr:hover {
      background: #F5F7FA;
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

    /* Mobile Responsive */
    @media (max-width: 768px) {
      body {
        padding: 20px 16px;
      }

      .policy-container {
        max-width: 100%;
      }

      .policy-header h1 {
        font-size: 28px;
      }

      .policy-header {
        margin-bottom: 40px;
      }

      .policy-section {
        padding: 20px;
        margin-bottom: 16px;
      }

      .policy-section h2 {
        font-size: 16px;
      }

      .policy-section p,
      .policy-section li {
        font-size: 14px;
      }

      .policy-table {
        font-size: 12px;
        overflow-x: auto;
      }

      .back-button {
        font-size: 13px;
        padding: 8px 12px;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 16px 12px;
      }

      .policy-header h1 {
        font-size: 22px;
      }

      .policy-header {
        margin-bottom: 24px;
      }

      .policy-section {
        padding: 16px;
        margin-bottom: 12px;
      }

      .policy-section h2 {
        font-size: 14px;
      }

      .policy-section p,
      .policy-section li {
        font-size: 13px;
        line-height: 1.6;
      }

      .policy-table {
        font-size: 11px;
        overflow-x: auto;
        display: block;
      }

      .policy-table th,
      .policy-table td {
        padding: 8px 6px;
      }

      .back-button {
        font-size: 12px;
        padding: 6px 10px;
      }
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
      <h1>Cookie Policy</h1>
      <p class="last-updated">Last updated: November 25, 2025</p>
    </div>

    <div class="policy-content">
      <div class="policy-section">
        <p>This Cookie Policy explains how Credova (a lending-management SaaS platform for lenders only) uses cookies and similar technologies on our website and web dashboard.</p>
      </div>

      <div class="policy-section">
        <h2>1. What Are Cookies?</h2>
        <p>Cookies are small text files placed on your device when you visit our site. They help the website remember your preferences and improve your experience.</p>
      </div>

      <div class="policy-section">
        <h2>2. Do We Use Cookies?</h2>
        <p>Yes, but only strictly necessary and performance/analytics cookies. We do NOT use advertising, tracking, or third-party marketing cookies.</p>
      </div>

      <div class="policy-section">
        <table class="cookie-table">
          <thead>
            <tr>
              <th>Type</th>
              <th>Purpose</th>
              <th>Examples & Tools Used</th>
              <th>Duration</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Strictly Necessary</strong></td>
              <td>Keep you logged in, secure your session, remember language settings</td>
              <td>Session cookies, CSRF tokens</td>
              <td>Session or up to 1 year</td>
            </tr>
            <tr>
              <td><strong>Performance & Analytics</strong></td>
              <td>Understand how lenders use the dashboard (e.g., most-used features) – all data is anonymized</td>
              <td>Plausible Analytics (privacy-friendly, no personal data) or self-hosted Matomo</td>
              <td>Up to 12 months</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="policy-section">
        <h2>3. We Do NOT Use</h2>
        <ul>
          <li>Google Analytics (standard version)</li>
          <li>Facebook/Meta Pixel</li>
          <li>Advertising or retargeting cookies</li>
          <li>Cookies that track you across other websites</li>
        </ul>
      </div>

      <div class="policy-section">
        <h2>4. Borrower Data Is Never Involved</h2>
        <p>Cookies are only used for logged-in lenders and administrators. No borrower personal information is ever stored in cookies.</p>
      </div>

      <div class="policy-section">
        <h2>5. Managing Cookies</h2>
        <p>You can control cookies through your browser settings:</p>
        <ul>
          <li>Block or delete cookies anytime</li>
          <li>Most browsers allow you to refuse non-essential cookies</li>
        </ul>
        <p style="margin-top: 12px; font-weight: 500; color: #134376;">Note: Blocking strictly necessary cookies may prevent you from logging in or using parts of the dashboard.</p>
      </div>

      <div class="policy-section">
        <h2>6. Consent</h2>
        <p>By continuing to use Credova after seeing this notice, you consent to our use of strictly necessary and anonymized performance cookies.</p>
      </div>

      <div class="policy-section">
        <h2>7. Changes to This Policy</h2>
        <p>We may update this Cookie Policy. Changes will be posted here with the updated date.</p>
      </div>

      <div class="policy-section">
        <h2>8. Questions?</h2>
        <div class="contact-info">
          <p><strong>Email:</strong> support@credova.ph</p>
        </div>
      </div>

      <div class="policy-section highlight">
        <p>Thank you for trusting Credova – built for lenders, built with privacy in mind.</p>
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
