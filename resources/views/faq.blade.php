<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>FAQ - Credova Lending Tracker</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Inter', sans-serif;
      background: linear-gradient(180deg, #F5F7FA 0%, #F5F7FA 100%);
      color: #1A1F2C;
      line-height: 1.6;
      padding: 40px 20px;
    }

    .faq-container {
      max-width: 900px;
      margin: 0 auto;
    }

    .faq-header {
      text-align: center;
      margin-bottom: 60px;
    }

    .faq-header h1 {
      font-size: 42px;
      font-weight: 700;
      color: #134376;
      margin-bottom: 12px;
      letter-spacing: -0.5px;
    }

    .faq-header p {
      font-size: 16px;
      color: #64748B;
      font-weight: 400;
    }

    .faq-item {
      background: #FFFFFF;
      border: 1px solid #DDE2EB;
      border-radius: 12px;
      padding: 28px;
      margin-bottom: 20px;
      animation: slideIn 0.5s ease-out;
      transition: all 0.3s ease;
    }

    .faq-item:hover {
      box-shadow: 0 8px 20px rgba(19, 67, 118, 0.08);
      border-color: #D9DEE8;
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

    .faq-question {
      font-size: 18px;
      font-weight: 700;
      color: #134376;
      margin-bottom: 12px;
      line-height: 1.4;
      letter-spacing: -0.3px;
    }

    .faq-answer {
      font-size: 15px;
      color: #475569;
      line-height: 1.7;
      font-weight: 400;
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

    footer {
      margin-top: 80px;
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

      .faq-container {
        max-width: 100%;
      }

      .faq-header h1 {
        font-size: 28px;
        margin-bottom: 8px;
      }

      .faq-header p {
        font-size: 14px;
      }

      .faq-header {
        margin-bottom: 40px;
      }

      .faq-item {
        padding: 20px;
        margin-bottom: 16px;
        border-radius: 10px;
      }

      .faq-question {
        font-size: 16px;
        margin-bottom: 10px;
      }

      .faq-answer {
        font-size: 14px;
      }

      .back-button {
        font-size: 13px;
        padding: 8px 12px;
      }

      .section-title {
        font-size: 16px;
        margin-top: 32px;
      }

      .footer-links {
        gap: 8px;
      }

      .footer-links a {
        font-size: 12px;
      }

      .footer-separator {
        font-size: 12px;
      }

      .footer-powered {
        font-size: 11px;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 16px 12px;
      }

      .faq-header h1 {
        font-size: 22px;
      }

      .faq-header p {
        font-size: 13px;
      }

      .faq-header {
        margin-bottom: 24px;
      }

      .faq-item {
        padding: 16px;
        margin-bottom: 12px;
      }

      .faq-question {
        font-size: 14px;
      }

      .faq-answer {
        font-size: 13px;
        line-height: 1.6;
      }

      .back-button {
        font-size: 12px;
        padding: 6px 10px;
      }

      .section-title {
        font-size: 14px;
        margin-top: 24px;
      }

      .footer-links {
        flex-direction: column;
        gap: 4px;
      }

      .footer-separator {
        display: none;
      }

      .footer-links a {
        font-size: 11px;
      }
    }
  </style>
</head>
<body>
  <div class="faq-container">
    <a href="{{ route('dashboard') }}" class="back-button">
      <span class="back-arrow">←</span>
      Dashboard
    </a>

    <div class="faq-header">
      <h1>Frequently Asked Questions</h1>
      <p>Find answers to common questions about Credova Lending Tracker</p>
    </div>

    <div class="faq-items">
      <div class="faq-item">
        <div class="faq-question">1. What is this Credova Lending Tracker System?</div>
        <div class="faq-answer">It's a digital platform that allows lenders to record borrower information, track loans, monitor payments, and automatically see due and overdue accounts.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">2. Who can use this system?</div>
        <div class="faq-answer">Any lending operator—small or mid-size—who needs an organized way to manage borrowers, loan details, and collections.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">3. What features are included?</div>
        <div class="faq-answer">
          <ul style="margin-left: 20px; margin-top: 8px;">
            <li>Borrower information storage</li>
            <li>Loan creation with principal, interest, and terms</li>
            <li>Automated balance computation</li>
            <li>Payment tracking</li>
            <li>Due and overdue reminders</li>
            <li>Comprehensive reports and analytics</li>
            <li>Secure cloud access</li>
          </ul>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question">4. Do I need technical skills to use it?</div>
        <div class="faq-answer">No. The system is built to be simple and easy to navigate even for first-time users.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">5. How many borrowers can I add?</div>
        <div class="faq-answer">There's no strict limit. Your subscription tier determines your recommended capacity, but the system can handle both small and large borrower lists.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">6. Is my data secure?</div>
        <div class="faq-answer">Yes. All data is stored in a secured environment with regular backups to ensure confidentiality and reliability.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">7. Can I access the system on my phone?</div>
        <div class="faq-answer">Yes. The platform is mobile-responsive and works on phones, tablets, and computers.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">8. Can I export reports?</div>
        <div class="faq-answer">Yes. You can generate and export loan and payment reports for accounting or auditing purposes.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">9. Do you offer customer support?</div>
        <div class="faq-answer">Yes. Depending on your subscription plan, support ranges from basic assistance to priority support for higher tiers.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">10. Is there a contract or lock-in period?</div>
        <div class="faq-answer">No lock-in is required. You can subscribe monthly and cancel any time.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">11. How much is the monthly subscription?</div>
        <div class="faq-answer">Pricing depends on your tier, but most lenders fall between ₱999 – ₱1,499 per month. Custom plans are available for larger operations.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">12. Can I migrate my existing borrower data into the system?</div>
        <div class="faq-answer">Yes. You can manually encode them or request optional data migration assistance.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">13. What happens if I miss a monthly payment?</div>
        <div class="faq-answer">Your account will be temporarily paused, but your data will remain safe. Once payment is settled, full access is restored.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">14. Can multiple staff members use the system?</div>
        <div class="faq-answer">Yes. Multi-user access can be enabled depending on your subscription.</div>
      </div>

      <div class="faq-item">
        <div class="faq-question">15. Will you add more features in the future?</div>
        <div class="faq-answer">Yes. Updates and improvements are continuously rolled out, and active subscribers receive them automatically.</div>
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
