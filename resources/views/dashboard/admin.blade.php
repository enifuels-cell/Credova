<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
  <title>Credova - Lending Management</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }

    @keyframes slideInLeft {
      from { opacity: 0; transform: translateX(-20px); }
      to { opacity: 1; transform: translateX(0); }
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Inter', sans-serif;
      background: #FFFFFF;
      color: #1A1F2C;
      line-height: 1.3;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-rendering: optimizeLegibility;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      overflow-x: hidden;
      width: 100vw;
      position: relative;
    }

    .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; flex: 1; }

    /* Mobile-first base (apply always under 640px) */
    @media (max-width: 640px) {
      body {
        background: #FAFBFC;
      }

      .container {
        padding: 48px 20px 0 20px;
        margin: 0;
      }

      .big-amount {
        font-size: 32px;
        font-weight: 800;
        color: #0F172A;
      }

      .action-buttons {
        display: flex;
        flex-direction: row;
        gap: 16px;
        margin-top: 32px;
      }

      .action-buttons button {
        flex: 1;
        padding: 16px 20px;
        background: #1E293B;
        color: white;
        font-weight: 600;
        border-radius: 16px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
      }

      .action-buttons button:hover {
        background: #0F172A;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.12);
      }

      .metric-row {
        display: flex;
        flex-direction: row;
        gap: 12px;
        margin-top: 32px;
        padding: 0 20px;
        overflow-x: auto;
        overflow-y: hidden;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
      }

      .metric-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        flex: 1;
        min-width: 100px;
        text-align: center;
      }

      .financial-card {
        margin: 0;
        margin-top: 48px;
        margin-left: 20px;
        margin-right: 20px;
        background: #FFFFFF;
        border-radius: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        overflow: hidden;
      }

      .financial-header {
        background: #6366F1;
        padding: 16px 24px;
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
      }

      .financial-body {
        padding: 24px;
        display: flex;
        flex-direction: column;
        gap: 32px;
      }

      .financial-row {
        display: flex;
        flex-direction: column;
        gap: 4px;
      }

      .financial-row-label {
        font-size: 13px;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
      }

      .financial-row-value {
        font-size: 28px;
        font-weight: 800;
        color: #0F172A;
      }

      .financial-row-value.green {
        color: #16A34A;
      }
    }

    /* Tablet 480px – 768px → slightly larger fonts & padding */
    @media (min-width: 480px) and (max-width: 768px) {
      .big-amount {
        font-size: 40px;
      }

      .metric-card {
        padding: 20px 24px;
      }

      .action-buttons button {
        padding-top: 16px;
        padding-bottom: 16px;
      }

      .financial-body {
        gap: 28px;
      }
    }

    /* Desktop ≥ 1024px → original layout */
    @media (min-width: 1024px) {
      body {
        background: #FAFBFC;
      }

      .container {
        margin: 0 auto;
        padding: 0 20px;
        flex: 1;
      }

      header {
        padding: 12px 20px;
        gap: 8px;
      }

      .logo {
        width: 48px !important;
        height: 48px !important;
      }

      .brand h1 {
        font-size: 16px !important;
        letter-spacing: -0.4px;
        font-weight: 700;
      }

      .brand .tagline {
        font-size: 11px !important;
        letter-spacing: 0.5px;
        font-weight: 600;
      }

      .faq-link {
        position: static;
        transform: none;
        font-size: 11px;
        padding: 6px 12px;
        margin-left: auto;
        border-radius: 8px;
        font-weight: 600;
      }

      .faq-link:hover {
        transform: none;
        background: rgba(255, 255, 255, 0.25);
      }

      .card {
        border-radius: 12px;
        margin: 0;
        background: #FFFFFF;
      }

      .collectible {
        flex-direction: column;
        padding: 28px 20px;
        border-bottom: 1px solid #E5E7EB;
        gap: 18px;
      }

      .collectible > div:first-child {
        flex: 1;
        width: 100%;
      }

      .collectible-label {
        font-size: 13px;
        color: #9CA3AF;
        margin-bottom: 8px;
        font-weight: 500;
      }

      .amount {
        font-size: 42px;
        margin-bottom: 14px;
        font-weight: 800;
        line-height: 1;
        color: #134376;
      }

      .currency {
        font-size: 24px;
        margin-left: 6px;
        font-weight: 700;
        color: #134376;
      }

      .total-collected {
        font-size: 12px;
        padding: 8px 16px;
        background: #E8F7EE;
        color: #1F8F50;
        border: 1px solid rgba(31, 143, 80, 0.2);
        border-radius: 20px;
        display: inline-block;
        font-weight: 600;
      }

      .chart {
        width: 120px;
        height: 56px;
        margin: 0;
        align-self: flex-end;
      }

      .btn-divider {
        height: 0;
        background: transparent;
        margin: 0;
      }

      .btn-row {
        padding: 28px 20px;
        gap: 16px;
        flex-direction: row;
        justify-content: center;
      }

      .btn {
        flex: 1;
        padding: 16px 24px;
        font-size: 14px;
        border-radius: 16px;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(19, 67, 118, 0.15);
      }

      .stats {
        padding: 20px 20px;
        gap: 12px;
        flex-direction: row;
        justify-content: space-between;
      }

      .stat {
        flex: 1;
        padding: 14px 12px;
        gap: 8px;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
      }

      .stat:nth-child(2) {
        margin: 0 8px;
      }

      .stat-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
      }

      .stat-icon svg {
        width: 20px !important;
        height: 20px !important;
      }

      .stat-tag {
        font-size: 9px;
        letter-spacing: 0.5px;
        font-weight: 700;
        text-transform: uppercase;
        color: #6B7280;
      }

      .stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #134376;
        line-height: 1;
      }

      .stat:nth-child(2) .stat-tag {
        color: #D64545;
      }

      .stat:nth-child(2) .stat-value {
        color: #D64545;
      }

      .stat:nth-child(3) .stat-tag {
        color: #1F8F50;
      }

      .stat:nth-child(3) .stat-value {
        color: #1F8F50;
      }

      .stat-content {
        gap: 4px;
        width: 100%;
      }

      .section-title {
        margin: 32px 20px 16px;
        padding: 12px 20px;
        font-size: 13px;
        border-radius: 20px;
        background: rgba(19, 67, 118, 0.08);
        color: #134376;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(19, 67, 118, 0.15);
      }

      .financial-cards {
        padding: 20px 20px 0;
        grid-template-columns: 1fr;
        gap: 0;
        display: flex;
        flex-direction: column;
      }

      .f-card {
        padding: 18px 20px;
        border-radius: 24px;
        background: #F3F4F6;
        border: none;
        margin-bottom: 12px;
      }

      .f-card:last-child {
        margin-bottom: 20px;
      }

      .f-label {
        font-size: 11px;
        margin-bottom: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9CA3AF;
      }

      .f-value {
        font-size: 34px;
        font-weight: 900;
        margin-bottom: 2px;
        color: #134376;
        line-height: 1;
      }

      .f-value.green {
        color: #1F8F50;
      }

      .f-value.blue {
        color: #134376;
      }

      .f-sub {
        display: none;
      }

      .section {
        margin-top: 24px;
        background: #FFFFFF;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #E5E7EB;
      }

      .section h2 {
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 14px;
        color: #134376;
      }

      .borrower-item {
        padding: 10px;
        flex-direction: column;
        gap: 6px;
        border-radius: 8px;
        margin-bottom: 6px;
      }

      .borrower-info {
        gap: 6px;
      }

      .borrower-name {
        font-size: 12px;
      }

      .borrower-loan {
        font-size: 10px;
      }

      .borrower-amount {
        font-size: 10px;
      }

      .payment-status {
        min-width: auto;
        font-size: 10px;
        padding: 5px 8px;
      }

      .borrower-performance-item {
        padding: 10px;
        flex-direction: column;
        gap: 8px;
        border-radius: 8px;
        margin-bottom: 6px;
      }

      .borrower-performance-info {
        flex-direction: row;
        gap: 8px;
        align-items: flex-start;
      }

      .borrower-details {
        gap: 3px;
        flex: 1;
      }

      .borrower-credit-score {
        align-items: flex-end;
        gap: 3px;
        min-width: 70px;
      }

      .borrower-credit-score > div:first-child {
        font-size: 18px;
      }

      .borrower-credit-score > div:last-child {
        font-size: 9px;
        padding: 2px 4px;
      }

      .payment-item {
        padding: 10px;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 6px;
        border-radius: 8px;
      }

      .payment-info {
        font-size: 13px;
      }

      .payment-name {
        font-size: 12px;
        margin-bottom: 2px;
      }

      .payment-loan {
        font-size: 10px;
      }

      .payment-amount {
        font-size: 14px;
      }

      .payment-date {
        font-size: 10px;
      }

      .modal-content {
        width: 90vw;
        max-width: 400px;
        border-radius: 14px;
        margin: 15% auto;
      }

      .modal-header {
        padding: 20px 20px 16px;
      }

      .modal-header h2 {
        font-size: 18px;
      }

      .modal-header p {
        font-size: 13px;
      }

      .close-btn {
        right: 20px;
        top: 20px;
        font-size: 24px;
      }

      .modal-body {
        padding: 20px;
        max-height: calc(85vh - 100px);
      }

      .form-group {
        margin-bottom: 16px;
      }

      .form-group label {
        font-size: 12px;
        margin-bottom: 6px;
      }

      .form-input,
      .form-group input,
      .form-group select {
        font-size: 16px;
        padding: 11px 12px;
        border-radius: 10px;
      }

      .form-row {
        grid-template-columns: 1fr;
        gap: 12px;
      }

      .modal-footer {
        padding: 16px 20px;
        gap: 10px;
      }

      .btn-cancel,
      .btn-submit {
        padding: 10px 20px;
        font-size: 13px;
        border-radius: 8px;
      }

      .dashboard-footer {
        padding: 20px 16px;
        margin-top: 24px;
      }

      .footer-content {
        max-width: 100%;
      }

      .footer-copyright {
        font-size: 12px;
        margin-bottom: 12px;
      }

      .footer-links {
        flex-direction: column;
        gap: 6px;
        margin-bottom: 12px;
      }

      .footer-separator {
        display: none;
      }

      .footer-powered {
        font-size: 11px;
      }
    }

    @media (max-width: 480px) {
      .container {
        margin: 12px auto;
        padding: 0 10px;
        flex: 1;
      }

      header {
        padding: 12px 14px;
        gap: 6px;
      }

      .logo {
        width: 48px !important;
        height: 48px !important;
      }

      .brand h1 {
        font-size: 15px !important;
        letter-spacing: -0.3px;
      }

      .brand .tagline {
        display: none;
      }

      .faq-link {
        font-size: 10px;
        padding: 4px 8px;
        border-radius: 6px;
      }

      .card {
        border-radius: 12px;
      }

      .collectible {
        padding: 18px 14px;
        gap: 14px;
      }

      .collectible-label {
        font-size: 11px;
      }

      .amount {
        font-size: 36px;
        margin-bottom: 10px;
      }

      .currency {
        font-size: 22px;
      }

      .total-collected {
        font-size: 11px;
        padding: 6px 12px;
      }

      .chart {
        width: 100%;
        height: 50px;
      }

      .btn-row {
        padding: 14px 12px;
        gap: 8px;
      }

      .btn {
        padding: 10px 16px;
        font-size: 12px;
        border-radius: 20px;
      }

      .stats {
        padding: 14px 12px;
        gap: 8px;
      }

      .stat {
        padding: 10px 12px;
        gap: 10px;
      }

      .stat-icon {
        width: 32px;
        height: 32px;
        min-width: 32px;
        border-radius: 8px;
      }

      .stat-icon svg {
        width: 20px !important;
        height: 20px !important;
      }

      .stat-content {
        gap: 2px;
      }

      .stat-tag {
        font-size: 9px;
        letter-spacing: 0.2px;
      }

      .stat-value {
        font-size: 18px;
      }

      .section-title {
        margin: 12px 12px 10px;
        padding: 8px 16px;
        font-size: 12px;
        border-radius: 6px;
      }

      .financial-cards {
        padding: 0 12px 16px;
        gap: 8px;
      }

      .f-card {
        padding: 14px 12px;
        border-radius: 10px;
      }

      .f-label {
        font-size: 9px;
      }

      .f-value {
        font-size: 24px;
      }

      .section {
        margin-top: 12px;
        padding: 12px;
        border-radius: 12px;
      }

      .section h2 {
        font-size: 14px;
        margin-bottom: 10px;
      }

      .borrower-item {
        padding: 10px;
        gap: 6px;
        border-radius: 8px;
        margin-bottom: 6px;
      }

      .borrower-info {
        gap: 6px;
      }

      .borrower-name {
        font-size: 12px;
      }

      .borrower-loan,
      .borrower-amount,
      .borrower-status {
        font-size: 10px;
      }

      .payment-status {
        font-size: 10px;
        padding: 5px 8px;
      }

      .borrower-performance-item {
        padding: 10px;
        gap: 8px;
        border-radius: 8px;
        margin-bottom: 6px;
      }

      .borrower-performance-info {
        gap: 8px;
      }

      .borrower-details {
        gap: 3px;
      }

      .borrower-credit-score {
        gap: 3px;
        min-width: 70px;
      }

      .borrower-credit-score > div:first-child {
        font-size: 18px;
      }

      .borrower-credit-score > div:last-child {
        font-size: 9px;
        padding: 2px 4px;
      }

      .payment-item {
        padding: 10px;
        gap: 8px;
        border-radius: 8px;
        margin-bottom: 6px;
      }

      .payment-info {
        font-size: 13px;
      }

      .payment-name {
        font-size: 12px;
      }

      .payment-loan {
        font-size: 10px;
      }

      .payment-amount {
        font-size: 15px;
      }

      .payment-date {
        font-size: 10px;
      }

      .modal-content {
        width: 90vw;
        max-width: 350px;
        border-radius: 12px;
        margin: 20% auto;
      }

      .modal-header {
        padding: 16px 16px 12px;
      }

      .modal-header h2 {
        font-size: 16px;
      }

      .modal-header p {
        font-size: 12px;
      }

      .close-btn {
        right: 16px;
        top: 16px;
        font-size: 22px;
      }

      .modal-body {
        padding: 16px;
        max-height: calc(85vh - 80px);
      }

      .form-group {
        margin-bottom: 12px;
      }

      .form-group label {
        font-size: 11px;
        margin-bottom: 5px;
      }

      .form-input,
      .form-group input,
      .form-group select {
        font-size: 16px;
        padding: 10px 10px;
        border-radius: 8px;
      }

      .form-row {
        gap: 10px;
      }

      .modal-footer {
        padding: 12px 16px;
        gap: 8px;
      }

      .btn-cancel,
      .btn-submit {
        padding: 9px 16px;
        font-size: 12px;
        border-radius: 6px;
      }

      .dashboard-footer {
        padding: 16px 12px;
        margin-top: 16px;
      }

      .footer-content {
        max-width: 100%;
      }

      .footer-copyright {
        font-size: 11px;
        margin-bottom: 8px;
      }

      .footer-links {
        gap: 4px;
        margin-bottom: 8px;
      }

      .footer-links a {
        font-size: 10px;
      }

      .footer-powered {
        font-size: 10px;
      }
    }

    /* Desktop styles ≥ 1024px */
    header {
      background: #134376;
      color: white;
      padding: 20px 40px;
      display: flex;
      align-items: center;
      gap: 8px;
      position: relative;
      box-shadow: 0 4px 10px rgba(13, 27, 62, 0.25);
      width: 100%;
    }

    header::after {
      content: "";
      position: absolute;
      bottom: -2px;
      left: 0;
      right: 0;
      height: 1px;
      background: linear-gradient(90deg, transparent, #134376, transparent);
      opacity: 0.4;
      border-radius: 50%;
    }

    .faq-link {
      color: white;
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
      font-style: italic;
      padding: 8px 16px;
      border-radius: 8px;
      transition: all 0.3s ease;
      cursor: pointer;
      letter-spacing: 0.3px;
      position: absolute;
      right: 40px;
      top: 50%;
      transform: translateY(-50%);
    }

    .faq-link:hover {
      background: rgba(255, 255, 255, 0.15);
      transform: translateY(-50%) translateX(2px);
    }

    /* Footer Styling */
    .dashboard-footer {
      background: #FFFFFF;
      border-top: 1px solid #DDE2EB;
      padding: 40px 20px;
      text-align: center;
      width: 100%;
      margin-top: auto;
    }

    .footer-divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, #D9DEE8, transparent);
      margin: 0 0 30px 0;
    }

    .dashboard-footer .footer-divider:last-of-type {
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

    .logo {
      width: 100px;
      height: 100px;
      border-radius: 12px;
      object-fit: cover;
    }

    .brand h1 {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: -0.4px;
      line-height: 1.05;
      text-shadow: 0 0 1px rgba(255,255,255,0.1);
      color: #FFFFFF;
    }

    .tagline {
      font-size: 13px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      opacity: 0.8;
      line-height: 1.1;
      color: #FFFFFF;
    }

    /* Main Card with Enhanced Depth */
    .card {
      background: #FFFFFF;
      border-radius: 0 0 16px 16px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.06);
      overflow: hidden;
      border: 1px solid #E2E6EF;
    }

    /* Today's Collectible */
    .collectible {
      padding: 40px 48px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #D9DEE8;
      animation: fadeIn 0.6s ease-out;
    }

    .collectible > div:first-child {
      flex: 1;
    }

    .collectible-label {
      font-size: 14px;
      color: #6B7280;
      margin-bottom: 12px;
      font-weight: 400;
      animation: fadeIn 0.6s ease-out 0.1s both;
      letter-spacing: 0.3px;
      line-height: 1.1;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
    }

    .amount {
      font-size: 52px;
      font-weight: 700;
      color: #134376;
      margin-bottom: 16px;
      animation: countUp 1s ease-out 0.2s both;
      letter-spacing: -0.4px;
      line-height: 1.05;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
      font-feature-settings: "tnum";
    }

    .currency {
      font-size: 32px;
      margin-left: 12px;
      font-weight: 600;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
    }

    .total-collected {
      background: #E8F7EE;
      color: #1F8F50;
      padding: 10px 20px;
      border-radius: 50px;
      font-size: 14px;
      font-weight: 600;
      border: 1px solid rgba(31, 143, 80, 0.2);
      display: inline-block;
      animation: fadeIn 0.6s ease-out 0.3s both;
      letter-spacing: 0.3px;
      line-height: 1.1;
    }

    .chart {
      width: 220px;
      height: 60px;
      position: relative;
      animation: slideInLeft 0.7s ease-out 0.3s both;
    }

    /* Buttons Enhancement */
    .btn-divider {
      height: 1px;
      background: rgba(0,0,0,0.05);
      margin: 0;
    }

    .btn-row {
      padding: 28px 48px;
      display: flex;
      gap: 64px;
      justify-content: center;
      border-bottom: 0;
      position: relative;
      background: #FFFFFF;
      z-index: 10;
    }

    .btn {
      background: linear-gradient(135deg, #134376 0%, #0F2D5F 100%);
      color: #FFFFFF;
      border: none;
      padding: 14px 40px;
      border-radius: 9999px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(19, 67, 118, 0.25);
      position: relative;
      overflow: hidden;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(19, 67, 118, 0.35);
      background: linear-gradient(135deg, #0F2D5F 0%, #0A1F47 100%);
    }

    .btn:active {
      animation: buttonPress 0.2s ease-out;
    }

    /* Stats - Modern Pill Style */
    .stats {
      padding: 28px 48px;
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
      border-bottom: 0;
      margin-top: 0;
      position: relative;
      z-index: 11;
    }

    .stat {
      background: linear-gradient(135deg, #FFFFFF 0%, #F9FAFC 100%);
      padding: 16px 28px;
      border-radius: 14px;
      border: 1px solid #E2E6EF;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
      min-width: fit-content;
      display: flex;
      align-items: center;
      gap: 16px;
      transition: all 0.3s ease;
      animation: liftUp 0.6s ease-out;
    }

    .stat:nth-child(1) { animation-delay: 0.4s; }
    .stat:nth-child(2) { animation-delay: 0.5s; }
    .stat:nth-child(3) { animation-delay: 0.6s; }

    .stat:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.08);
      border-color: #D9DEE8;
      background: linear-gradient(135deg, #FFFFFF 0%, #FFFFFF 100%);
    }

    .stat-content {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .stat-icon {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      background: #FFFFFF;
      border: 1px solid #DDE2EB;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
    }

    .stat-icon img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      padding: 4px;
    }

    .stat-tag {
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      color: #6B7280;
      opacity: 0.75;
      line-height: 1.1;
    }

    .stat-value {
      font-size: 24px;
      font-weight: 700;
      color: #134376;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
      line-height: 1.05;
      font-feature-settings: "tnum";
    }

    .stat:nth-child(1) .stat-tag { color: #134376; }
    .stat:nth-child(2) .stat-tag { color: #D64545; }
    .stat:nth-child(3) .stat-tag { color: #1F8F50; }

    /* Section Title Enhanced */
    .section-title {
      margin: 24px 48px 16px;
      background: linear-gradient(135deg, rgba(19, 67, 118, 0.1) 0%, rgba(19, 67, 118, 0.05) 100%);
      color: #134376;
      padding: 12px 28px;
      border-radius: 50px;
      font-size: 15px;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      border: 1px solid rgba(19, 67, 118, 0.2);
      box-shadow: 0 2px 8px rgba(19, 67, 118, 0.08);
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .section-title::before {
      content: "📊";
      font-size: 16px;
    }

    /* Financial Overview Cards Enhanced */
    .financial-cards {
      padding: 0 48px 32px;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .f-card {
      background: linear-gradient(135deg, #F5F7FA 0%, #EEF1F6 100%);
      padding: 20px 24px;
      border-radius: 16px;
      border: 1px solid #DDE2EB;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .f-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.08);
      border-color: #D9DEE8;
      background: linear-gradient(135deg, #F9FAFC 0%, #F5F7FA 100%);
    }    .f-label {
      font-size: 12px;
      color: #6B7280;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      margin-bottom: 12px;
      font-weight: 600;
      opacity: 0.75;
      line-height: 1.1;
    }

    .f-value {
      font-size: 32px;
      font-weight: 700;
      margin-bottom: 6px;
      color: #134376;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
      line-height: 1.05;
      letter-spacing: -0.4px;
      font-feature-settings: "tnum";
    }

    .f-value.green { color: #1F8F50; }
    .f-value.blue { color: #134376; }

    .f-sub {
      font-size: 13px;
      color: #6B7280;
      font-weight: 400;
      display: none;
      opacity: 0.55;
    }

    /* Sections Enhanced */
    .section {
      margin-top: 28px;
      background: #FFFFFF;
      border-radius: 16px;
      padding: 32px 48px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.06);
      border: 1px solid #E2E6EF;
    }

    .section h2 {
      font-size: 18px;
      font-weight: 700;
      margin-bottom: 24px;
      color: #134376;
      display: flex;
      align-items: center;
      gap: 8px;
      letter-spacing: -0.4px;
      line-height: 1.05;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
    }

    .collector-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 20px;
      background: linear-gradient(135deg, #F5F7FA 0%, #EEF1F6 100%);
      border-radius: 14px;
      margin-bottom: 10px;
      border: 1px solid #DDE2EB;
      transition: all 0.2s ease;
    }

    .collector-item:hover {
      transform: translateX(2px);
      background: linear-gradient(135deg, #FFFFFF 0%, #F9FAFC 100%);
      border-color: #1A73E8;
    }

    .collector-info {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .avatar {
      width: 40px;
      height: 40px;
      background: linear-gradient(135deg, #1A73E8 0%, #1557B0 100%);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      font-size: 13px;
      box-shadow: 0 2px 8px rgba(26, 115, 232, 0.2);
    }

    .collector-name {
      font-weight: 700;
      color: #0D1B3E;
      line-height: 1.05;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
      letter-spacing: -0.4px;
    }

    .collector-email {
      font-size: 13px;
      color: #6B7280;
      font-weight: 400;
      opacity: 0.55;
      line-height: 1.3;
    }

    .collections-count {
      font-weight: 600;
      color: #1A73E8;
      background: rgba(26, 115, 232, 0.1);
      padding: 6px 14px;
      border-radius: 50px;
      font-size: 13px;
      border: 1px solid rgba(26, 115, 232, 0.2);
      letter-spacing: 0.3px;
      line-height: 1.1;
      font-feature-settings: "tnum";
    }

    /* Borrowers Performance */
    .borrower-performance-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 20px;
      background: linear-gradient(135deg, #F5F7FA 0%, #EEF1F6 100%);
      border-radius: 14px;
      margin-bottom: 10px;
      border: 1px solid #DDE2EB;
      transition: all 0.2s ease;
    }

    .borrower-performance-item:hover {
      transform: translateX(2px);
      background: linear-gradient(135deg, #FFFFFF 0%, #F9FAFC 100%);
      border-color: #1A73E8;
    }

    .borrower-performance-info {
      display: flex;
      align-items: center;
      gap: 12px;
      flex: 1;
    }

    .borrower-details {
      display: flex;
      flex-direction: column;
    }

    .borrower-name {
      font-weight: 700;
      color: #0D1B3E;
      line-height: 1.05;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
      letter-spacing: -0.4px;
      font-size: 14px;
    }

    .borrower-credit-score {
      text-align: right;
    }

    /* Recent Payments */
    .payment-item {
      background: linear-gradient(135deg, #E8F7EE 0%, #F0FBF7 100%);
      padding: 16px 20px;
      border-radius: 14px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
      border: 1px solid rgba(31, 143, 80, 0.15);
      transition: all 0.2s ease;
    }

    .payment-item:hover {
      transform: translateX(2px);
      border-color: rgba(31, 143, 80, 0.3);
      background: linear-gradient(135deg, #E0F5E9 0%, #E8F9F2 100%);
    }

    .payment-info {
      font-size: 15px;
    }

    .payment-name {
      font-weight: 700;
      color: #0D1B3E;
      margin-bottom: 2px;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
      letter-spacing: -0.4px;
      line-height: 1.05;
    }

    .payment-loan {
      font-size: 13px;
      color: #6B7280;
      font-weight: 400;
      opacity: 0.55;
      line-height: 1.3;
    }

    .payment-amount {
      font-weight: 700;
      color: #1F8F50;
      font-size: 18px;
      margin-bottom: 2px;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
      letter-spacing: -0.4px;
      font-feature-settings: "tnum";
    }

    .payment-date {
      font-size: 12px;
      color: #6B7280;
      font-weight: 400;
      opacity: 0.55;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
      animation: fadeIn 0.3s ease-out;
    }

    .modal-content {
      background-color: #FFFFFF;
      margin: 10% auto;
      padding: 0;
      border-radius: 16px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
      width: 90%;
      max-width: 600px;
      max-height: 85vh;
      overflow: hidden;
      animation: slideInUp 0.4s ease-out;
      display: flex;
      flex-direction: column;
      border: 1px solid rgba(0,0,0,0.05);
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .modal-header {
      background: linear-gradient(135deg, #0D1B3E 0%, #1A2F4F 100%);
      color: white;
      padding: 32px 32px 24px;
      border-bottom: none;
    }

    .modal-header h2 {
      margin: 0;
      font-size: 24px;
      font-weight: 700;
      letter-spacing: -0.4px;
      line-height: 1.05;
      text-shadow: 0 0 1px rgba(255,255,255,0.1);
    }

    .modal-header p {
      margin: 8px 0 0;
      font-size: 14px;
      opacity: 0.75;
      line-height: 1.3;
      font-weight: 400;
    }

    .close-btn {
      position: absolute;
      right: 32px;
      top: 32px;
      font-size: 28px;
      font-weight: bold;
      color: white;
      cursor: pointer;
      border: none;
      background: none;
      opacity: 0.8;
      transition: opacity 0.2s;
    }

    .close-btn:hover {
      opacity: 1;
    }

    .modal-body {
      padding: 32px;
      overflow-y: auto;
      flex: 1;
      max-height: calc(85vh - 120px);
    }

    .modal-body::-webkit-scrollbar {
      width: 8px;
    }

    .modal-body::-webkit-scrollbar-track {
      background: transparent;
    }

    .modal-body::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.1);
      border-radius: 4px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
      background: rgba(0, 0, 0, 0.2);
    }

    .form-group {
      margin-bottom: 24px;
    }

    .form-group label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: rgba(0,0,0,0.7);
      margin-bottom: 8px;
      letter-spacing: 0.3px;
      line-height: 1.1;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 12px 16px;
      font-size: 15px;
      font-weight: 400;
      line-height: 1.3;
      border: 1px solid #D9DEE8;
      border-radius: 10px;
      font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Inter', sans-serif;
      transition: all 0.3s ease;
      background: #F5F7FA;
      color: #1A1F2C;
    }

    .form-group input:focus,
    .form-group select:focus {
      outline: none;
      border-color: #1A73E8;
      background: #FFFFFF;
      box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    .form-group.full {
      grid-column: 1 / -1;
    }

    .modal-footer {
      padding: 24px 32px;
      background: #F5F7FA;
      border-top: 1px solid #D9DEE8;
      display: flex;
      gap: 12px;
      justify-content: flex-end;
    }

    .btn-cancel {
      background: #FFFFFF;
      color: #0D1B3E;
      border: 1px solid #D9DEE8;
      padding: 12px 28px;
      border-radius: 9999px;
      font-size: 15px;
      font-weight: 600;
      letter-spacing: 0.3px;
      line-height: 1.1;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .btn-cancel:hover {
      background: #F1F3F8;
      border-color: #1A73E8;
    }

    .btn-submit {
      background: linear-gradient(135deg, #0D1B3E 0%, #112352 100%);
      color: white;
      border: none;
      padding: 12px 32px;
      border-radius: 9999px;
      font-size: 15px;
      font-weight: 600;
      letter-spacing: 0.3px;
      line-height: 1.1;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(13, 27, 62, 0.25);
    }

    .btn-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(13, 27, 62, 0.35);
      background: linear-gradient(135deg, #112352 0%, #0A162F 100%);
    }

    .payment-schedule-info {
      padding: 16px;
      background: rgba(59, 130, 246, 0.05);
      border-left: 4px solid #3B82F6;
      border-radius: 8px;
      font-size: 13px;
      color: #1E40AF;
      margin-top: 8px;
      display: none;
    }

    /* Skeleton Loader */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: shimmer 2s infinite;
      border-radius: 8px;
    }

    .skeleton-text {
      height: 12px;
      width: 60%;
      margin-bottom: 8px;
    }

    .skeleton-value {
      height: 28px;
      width: 40%;
      margin-bottom: 8px;
    }

    /* Borrower List Modal */
    .borrower-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 16px;
      background: linear-gradient(135deg, #f8fafc 0%, #f3f4f6 100%);
      border-radius: 12px;
      margin-bottom: 12px;
      border: 1px solid rgba(0,0,0,0.04);
      transition: all 0.2s ease;
      cursor: pointer;
    }

    #borrowersList {
      display: flex;
      flex-direction: column;
      gap: 0;
    }

    .borrower-item:hover {
      transform: translateX(4px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .borrower-info {
      flex: 1;
    }

    .borrower-name {
      font-weight: 700;
      color: #0F172A;
      font-size: 15px;
      margin-bottom: 4px;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
      line-height: 1.05;
      letter-spacing: -0.4px;
    }

    .borrower-loan {
      font-size: 13px;
      color: #64748B;
      margin-bottom: 4px;
      font-weight: 400;
      opacity: 0.55;
      line-height: 1.3;
    }

    .borrower-amount {
      font-weight: 600;
      color: #3b82f6;
      font-size: 14px;
      text-shadow: 0 0 1px rgba(0,0,0,0.05);
    }

    .payment-status {
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      line-height: 1.1;
    }

    .status-on-time {
      background: rgba(34, 197, 94, 0.1);
      color: #22C55E;
      border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .status-due-soon {
      background: rgba(245, 158, 11, 0.15);
      color: #b45309;
      border: 1px solid rgba(245, 158, 11, 0.3);
    }

    .status-delayed {
      background: rgba(239, 68, 68, 0.15);
      color: #991b1b;
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    /* Borrowers List Container */
    #borrowersList {
      max-height: 400px;
      overflow-y: auto;
      padding-right: 8px;
    }

    #borrowersList::-webkit-scrollbar {
      width: 8px;
    }

    #borrowersList::-webkit-scrollbar-track {
      background: transparent;
    }

    #borrowersList::-webkit-scrollbar-thumb {
      background: rgba(0, 0, 0, 0.1);
      border-radius: 4px;
    }

    #borrowersList::-webkit-scrollbar-thumb:hover {
      background: rgba(0, 0, 0, 0.2);
    }

    /* Update Payment Form */
    .form-group-payment {
      margin-bottom: 20px;
    }

    .form-group-payment label {
      display: block;
      font-weight: 700;
      color: #0F172A;
      margin-bottom: 8px;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    .form-group-payment input {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid rgba(0,0,0,0.08);
      border-radius: 10px;
      font-size: 14px;
      font-weight: 400;
      line-height: 1.3;
      font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Inter', sans-serif;
      transition: all 0.3s ease;
    }

    .form-group-payment input:focus {
      outline: none;
      border-color: #3B82F6;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
      background: #F8FAFC;
    }

  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <img src="{{ asset('logo1.png') }}" alt="CREDOVA Logo" class="logo">
    <div class="brand">
      <h1>CREDOVA</h1>
      <div class="tagline">Track. Manage. Grow</div>
    </div>
    <a href="{{ route('faq') }}" class="faq-link">FAQ</a>
  </header>

  <div class="container">
      <!-- Today's Collectible -->
      <div class="collectible">
        <div>
          <div class="collectible-label">Today's Collectible</div>
          <div class="amount"><span id="todayCollectible">9,000</span><span class="currency">PHP</span></div>
          <div class="total-collected">Total Collected <span id="totalCollected">₱2,000</span></div>
        </div>
        <div class="chart">
          <svg id="businessChart" viewBox="0 0 200 80" style="width: 100%; height: 100%;">
            <!-- Grid background -->
            <rect width="200" height="80" fill="transparent"/>
            <!-- Line chart -->
            <polyline points="10,60 50,45 90,20 130,35 170,10" fill="none" stroke="#1F8F50" stroke-width="2" vector-effect="non-scaling-stroke"/>
            <!-- Fill area -->
            <polygon points="10,60 50,45 90,20 130,35 170,10 170,80 130,80 90,80 50,80 10,80" fill="rgba(31, 143, 80, 0.2)"/>
            <!-- Data points -->
            <circle cx="10" cy="60" r="2" fill="#1F8F50"/>
            <circle cx="50" cy="45" r="2" fill="#1F8F50"/>
            <circle cx="90" cy="20" r="2" fill="#1F8F50"/>
            <circle cx="130" cy="35" r="2" fill="#1F8F50"/>
            <circle cx="170" cy="10" r="2" fill="#1F8F50"/>
          </svg>
        </div>
      </div>

      <!-- Divider Before Buttons -->
      <div class="btn-divider"></div>

      <!-- Buttons -->
      <div class="btn-row">
        <button class="btn" onclick="openAddAccountModal()">+Add Accounts</button>
        <button class="btn" onclick="openViewAccountsModal()">View Accounts</button>
      </div>

      <!-- Quick Stats -->
      <div class="stats">
        <div class="stat" onclick="openActiveLoansModal()" style="cursor: pointer;">
          <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              <!-- Document/Invoice Icon -->
              <path d="M12 6h20v30H12z" fill="#3b82f6" opacity="0.15" stroke="#3b82f6" stroke-width="1.5"/>
              <path d="M16 12h12M16 18h12M16 24h8" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              <circle cx="36" cy="32" r="8" fill="#3b82f6" opacity="0.2" stroke="#3b82f6" stroke-width="1.5"/>
              <text x="36" y="36" text-anchor="middle" font-size="10" font-weight="bold" fill="#3b82f6">+</text>
            </svg>
          </div>
          <div class="stat-content">
            <div class="stat-tag">Active Loans</div>
            <div class="stat-value" id="activeLoansCount">5</div>
          </div>
        </div>
        <div class="stat" onclick="openOverdueModal()" style="cursor: pointer;">
          <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              <!-- Clock/Timer Icon -->
              <circle cx="24" cy="24" r="14" fill="#ef4444" opacity="0.15" stroke="#ef4444" stroke-width="1.5"/>
              <line x1="24" y1="14" x2="24" y2="24" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
              <line x1="24" y1="24" x2="32" y2="24" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
              <circle cx="24" cy="24" r="2" fill="#ef4444"/>
              <path d="M36 10l4-4M36 38l4 4" stroke="#ef4444" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <div class="stat-content">
            <div class="stat-tag">Overdue</div>
            <div class="stat-value" id="overdueCount">3</div>
          </div>
        </div>
        <div class="stat" onclick="openROIModal()" style="cursor: pointer;">
          <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
              <!-- Growth/Chart Icon -->
              <rect x="8" y="28" width="6" height="12" fill="#22C55E" opacity="0.15" stroke="#22C55E" stroke-width="1.5" rx="1"/>
              <rect x="18" y="20" width="6" height="20" fill="#22C55E" opacity="0.35" stroke="#22C55E" stroke-width="1.5" rx="1"/>
              <rect x="28" y="12" width="6" height="28" fill="#22C55E" stroke="#22C55E" stroke-width="1.5" rx="1"/>
              <path d="M8 40h32" stroke="#22C55E" stroke-width="2" stroke-linecap="round"/>
              <path d="M32 16l-6 6-4-4-8 8" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
          </div>
          <div class="stat-content">
            <div class="stat-tag">Avg ROI</div>
            <div class="stat-value" id="avgROI">12%</div>
          </div>
        </div>
      </div>

      <!-- Financial Overview Title -->
      <div class="section-title">Financial Overview</div>

      <!-- Financial Overview Cards -->
      <div class="financial-cards">
        <div class="f-card">
          <div class="f-label">Capital Released</div>
          <div class="f-value" id="capitalReleased">₱10,000</div>
          <div class="f-sub">Principal deployed</div>
        </div>
        <div class="f-card">
          <div class="f-label">Interest Earned</div>
          <div class="f-value green" id="interestEarned">₱1,000</div>
          <div class="f-sub">Revenue</div>
        </div>
        <div class="f-card">
          <div class="f-label">Return on Investment</div>
          <div class="f-value blue" id="roiPercentage">10%</div>
          <div class="f-sub">Performance</div>
        </div>
        <div class="f-card">
          <div class="f-label">Status</div>
          <div class="f-value green" id="portfolioStatus">Profitable</div>
        </div>
      </div>
    </div>

    <!-- Borrowers Performance -->
    <div class="section">
      <h2>Borrowers Performance</h2>
      <div id="borrowersPerformanceList"></div>
    </div>

    <!-- Recent Payments -->
    <div class="section">
      <h2>Recent Payments</h2>
      <div id="recentPaymentsList">
        <!-- Recent payments will be loaded here from API -->
      </div>
    </div>
  </div>

  <!-- Add Account Modal -->
  <div id="addAccountModal" class="modal">
    <div class="modal-content">
      <div style="position: relative;">
        <div class="modal-header">
          <h2>Add New Account</h2>
          <p>Enter borrower details and loan information</p>
        </div>
        <button class="close-btn" onclick="closeModal()">&times;</button>
      </div>
      <form id="addAccountForm" onsubmit="handleAddAccount(event)">
        <div class="modal-body">
          <!-- Borrower Information -->
          <div class="form-row">
            <div class="form-group">
              <label for="borrowerName">Borrower Name *</label>
              <input type="text" id="borrowerName" name="borrowerName" placeholder="Full name" required>
            </div>
            <div class="form-group">
              <label for="borrowerEmail">Email *</label>
              <input type="email" id="borrowerEmail" name="borrowerEmail" placeholder="email@example.com" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="borrowerPhone">Phone Number *</label>
              <input type="tel" id="borrowerPhone" name="borrowerPhone" placeholder="+63 9XX XXX XXXX" required>
            </div>
            <div class="form-group">
              <label for="borrowerAddress">Address *</label>
              <input type="text" id="borrowerAddress" name="borrowerAddress" placeholder="Street address" required>
            </div>
          </div>

          <!-- Loan Information -->
          <hr style="margin: 24px 0; border: none; border-top: 1px solid rgba(0,0,0,0.05);">

          <div class="form-row">
            <div class="form-group">
              <label for="loanAmount">Loan Amount (₱) *</label>
              <input type="number" id="loanAmount" name="loanAmount" placeholder="50000" min="1000" required onchange="updatePayableAmount()">
            </div>
            <div class="form-group">
              <label for="loanDays">Contract Days *</label>
              <input type="number" id="loanDays" name="loanDays" placeholder="30" min="1" required>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label for="interestRate">Interest Rate (%) *</label>
              <input type="number" id="interestRate" name="interestRate" placeholder="10" min="0" step="0.1" required onchange="updatePayableAmount()">
            </div>
            <div class="form-group">
              <label for="payableAmount">Payable Amount (₱) *</label>
              <input type="number" id="payableAmount" name="payableAmount" placeholder="Auto-calculated" readonly style="background: #f5f7fa; cursor: not-allowed;">
            </div>
          </div>

          <!-- Payment Schedule -->
          <div class="form-group full">
            <label for="paymentFrequency">Payment Frequency *</label>
            <select id="paymentFrequency" name="paymentFrequency" onchange="updatePaymentSchedule()" required>
              <option value="">-- Select Payment Frequency --</option>
              <option value="daily">Daily</option>
              <option value="weekly">Weekly</option>
              <option value="twice-monthly">Twice a Month</option>
              <option value="monthly">Monthly</option>
            </select>
          </div>

          <!-- Conditional field for Twice Monthly -->
          <div id="twiceMonthlyDays" style="display: none;">
            <div class="form-row">
              <div class="form-group">
                <label for="paymentDay1">First Payment Day (1-28) *</label>
                <input type="number" id="paymentDay1" name="paymentDay1" min="1" max="28" placeholder="e.g., 5">
              </div>
              <div class="form-group">
                <label for="paymentDay2">Second Payment Day (1-28) *</label>
                <input type="number" id="paymentDay2" name="paymentDay2" min="1" max="28" placeholder="e.g., 20">
              </div>
            </div>
          </div>

          <div class="payment-schedule-info" id="scheduleInfo"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
          <button type="submit" class="btn-submit">Add Account</button>
        </div>
      </form>
    </div>
  </div>

  <!-- View Accounts Modal -->
  <div id="viewAccountsModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
      <div class="modal-header">
        <h2>Borrower Accounts</h2>
        <button class="close-btn" onclick="closeViewAccountsModal()">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Total Balance Summary -->
        <div style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%); padding: 24px; border-radius: 14px; margin-bottom: 24px; color: white;">
          <div style="font-size: 13px; opacity: 0.8; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 600;">Total Remaining Balance</div>
          <div style="font-size: 36px; font-weight: 700; letter-spacing: -0.4px; font-feature-settings: 'tnum';" id="totalBalance">₱0</div>
          <div style="font-size: 12px; opacity: 0.7; margin-top: 8px;" id="borrowerCount">0 borrowers</div>
        </div>
        <!-- Borrowers List -->
        <div id="borrowersList">
          <!-- Borrowers will be loaded here -->
        </div>
      </div>
    </div>
  </div>

  <!-- Update Payment Modal -->
  <div id="updatePaymentModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
      <div class="modal-header">
        <h2>Update Borrower Payment</h2>
        <button class="close-btn" onclick="closeUpdatePaymentModal()">&times;</button>
      </div>
      <form id="updatePaymentForm" onsubmit="handlePaymentUpdate(event)">
        <div class="modal-body">
          <div style="background: linear-gradient(135deg, #f8fafc 0%, #f3f4f6 100%); padding: 16px; border-radius: 12px; margin-bottom: 20px;">
            <div style="font-weight: 700; color: #0f172a; margin-bottom: 4px; font-size: 14px;" id="updateBorrowerName">Juan Dela Cruz</div>
            <div style="font-size: 12px; color: #64748b; margin-bottom: 8px;">Account Information</div>
          </div>

          <!-- Payment Summary -->
          <div class="payment-summary">
            <div class="summary-row">
              <span class="summary-label">Total Loan Amount:</span>
              <span class="summary-value" id="updateTotalLoan">₱0</span>
            </div>
            <div class="summary-row">
              <span class="summary-label">Already Paid:</span>
              <span class="summary-value" id="updatePaidAmount">₱0</span>
            </div>
            <div class="summary-row">
              <span class="summary-label">Remaining Balance:</span>
              <span class="summary-value" id="updateRemainingBalance">₱0</span>
            </div>
          </div>

          <!-- Payment Input -->
          <div class="form-group-payment">
            <label for="paymentAmount">Payment Amount (₱)</label>
            <input type="number" id="paymentAmount" name="paymentAmount" placeholder="Enter amount paid" min="0" step="100" required>
          </div>

          <!-- Updated Balance Preview -->
          <div style="background: rgba(59, 130, 246, 0.08); padding: 12px; border-radius: 10px; border: 1px solid rgba(59, 130, 246, 0.2); margin-top: 16px;">
            <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Balance After Payment:</div>
            <div style="font-size: 20px; font-weight: 800; color: #3b82f6;" id="newRemainingBalance">₱0</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-cancel" onclick="closeUpdatePaymentModal()">Cancel</button>
          <button type="submit" class="btn-submit">Confirm Payment</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Active Loans Modal -->
  <div id="activeLoansModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
      <div class="modal-header">
        <h2>Active Loans</h2>
        <button class="close-btn" onclick="closeActiveLoansModal()">&times;</button>
      </div>
      <div class="modal-body">
        <div id="activeLoansContent"></div>
      </div>
    </div>
  </div>

  <!-- Overdue Modal -->
  <div id="overdueModal" class="modal">
    <div class="modal-content" style="max-width: 700px;">
      <div class="modal-header">
        <h2>Overdue Accounts</h2>
        <button class="close-btn" onclick="closeOverdueModal()">&times;</button>
      </div>
      <div class="modal-body">
        <div id="overdueContent"></div>
      </div>
    </div>
  </div>

  <!-- ROI Report Modal -->
  <div id="roiModal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
      <div class="modal-header">
        <h2>ROI Comprehensive Report</h2>
        <button class="close-btn" onclick="closeROIModal()">&times;</button>
      </div>
      <div class="modal-body">
        <div id="roiContent"></div>
      </div>
    </div>
  </div>

  <script>
    // Modal Functions
    function openAddAccountModal() {
      document.getElementById('addAccountModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('addAccountModal').style.display = 'none';
      document.getElementById('addAccountForm').reset();
      document.getElementById('twiceMonthlyDays').style.display = 'none';
      document.getElementById('scheduleInfo').style.display = 'none';
    }

    function openViewAccountsModal() {
      document.getElementById('viewAccountsModal').style.display = 'block';
      loadBorrowersList();
    }

    function closeViewAccountsModal() {
      document.getElementById('viewAccountsModal').style.display = 'none';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
      const addModal = document.getElementById('addAccountModal');
      const viewModal = document.getElementById('viewAccountsModal');
      const updateModal = document.getElementById('updatePaymentModal');
      const activeLoansModal = document.getElementById('activeLoansModal');
      const overdueModal = document.getElementById('overdueModal');
      const roiModal = document.getElementById('roiModal');
      if (event.target === addModal) {
        closeModal();
      }
      if (event.target === viewModal) {
        closeViewAccountsModal();
      }
      if (event.target === updateModal) {
        closeUpdatePaymentModal();
      }
      if (event.target === activeLoansModal) {
        closeActiveLoansModal();
      }
      if (event.target === overdueModal) {
        closeOverdueModal();
      }
      if (event.target === roiModal) {
        closeROIModal();
      }
    }

    // Sample borrowers data (in production, fetch from backend)
    let borrowersData = [];

    // Fetch borrowers data from API
    async function fetchBorrowersData() {
      try {
        const response = await fetch('/api/borrowers');
        borrowersData = await response.json();

        // If no borrowers, use empty array (user has fresh account)
        if (!borrowersData || borrowersData.length === 0) {
          borrowersData = [];
        }
      } catch (error) {
        console.error('Error fetching borrowers:', error);
        borrowersData = [];
      }
    }

    function loadBorrowersList() {
      const borrowersList = document.getElementById('borrowersList');
      borrowersList.innerHTML = '';

      // Sort borrowers by urgency (most delayed first, then due soon, then on-time)
      const sortedBorrowers = [...borrowersData].sort((a, b) => {
        const daysA = Math.ceil((a.dueDate - new Date()) / (1000 * 60 * 60 * 24));
        const daysB = Math.ceil((b.dueDate - new Date()) / (1000 * 60 * 60 * 24));

        // Check if fully paid
        const aPaid = a.amount - a.paidAmount === 0;
        const bPaid = b.amount - b.paidAmount === 0;

        // Paid borrowers go to bottom
        if (aPaid && !bPaid) return 1;
        if (!aPaid && bPaid) return -1;
        if (aPaid && bPaid) return 0;

        // Otherwise sort by days (most urgent first)
        return daysA - daysB;
      });

      sortedBorrowers.forEach((borrower) => {
        const daysUntilDue = Math.ceil((borrower.dueDate - new Date()) / (1000 * 60 * 60 * 24));
        const remainingBalance = borrower.amount - borrower.paidAmount;
        let statusClass = 'status-on-time';
        let statusText = '✓ On Time';

        // Check if fully paid
        if (remainingBalance === 0) {
          statusClass = 'status-on-time';
          statusText = '✅ Paid in Full';
        } else if (daysUntilDue < 0) {
          statusClass = 'status-delayed';
          statusText = `⚠ Delayed ${Math.abs(daysUntilDue)} days`;
        } else if (daysUntilDue <= 3) {
          statusClass = 'status-due-soon';
          statusText = `⏰ Due in ${daysUntilDue} days`;
        }

        const borrowerHTML = `
          <div class="borrower-item" onclick="openUpdatePaymentModal(${borrower.id})">
            <div class="borrower-info">
              <div class="borrower-name">${borrower.name}</div>
              <div class="borrower-loan">Loan Duration: ${borrower.days} days | Total Loan: ₱${borrower.amount.toLocaleString()}</div>
              <div class="borrower-amount">Paid: ₱${borrower.paidAmount.toLocaleString()} | Remaining: <strong>₱${remainingBalance.toLocaleString()}</strong></div>
            </div>
            <div class="payment-status ${statusClass}">${statusText}</div>
          </div>
        `;
        borrowersList.innerHTML += borrowerHTML;
      });

      updateDashboardMetrics();
    }

    function openUpdatePaymentModal(borrowerId) {
      const borrower = borrowersData.find(b => b.id === borrowerId);
      if (!borrower) return;

      // Update modal with borrower info
      document.getElementById('updateBorrowerName').textContent = borrower.name;
      document.getElementById('updateTotalLoan').textContent = `₱${borrower.amount.toLocaleString()}`;
      document.getElementById('updatePaidAmount').textContent = `₱${borrower.paidAmount.toLocaleString()}`;

      const remainingBalance = borrower.amount - borrower.paidAmount;
      document.getElementById('updateRemainingBalance').textContent = `₱${remainingBalance.toLocaleString()}`;
      document.getElementById('newRemainingBalance').textContent = `₱${remainingBalance.toLocaleString()}`;

      // Clear input and set data attribute
      document.getElementById('paymentAmount').value = '';
      document.getElementById('updatePaymentForm').dataset.borrowerId = borrowerId;

      // Open modal
      document.getElementById('updatePaymentModal').style.display = 'block';

      // Add event listener for real-time balance preview
      document.getElementById('paymentAmount').addEventListener('input', updateBalancePreview);
    }

    function closeUpdatePaymentModal() {
      document.getElementById('updatePaymentModal').style.display = 'none';
      document.getElementById('updatePaymentForm').reset();
    }

    function openActiveLoansModal() {
      document.getElementById('activeLoansModal').style.display = 'block';
      renderActiveLoansModal();
    }

    function closeActiveLoansModal() {
      document.getElementById('activeLoansModal').style.display = 'none';
    }

    function openOverdueModal() {
      document.getElementById('overdueModal').style.display = 'block';
      renderOverdueModal();
    }

    function closeOverdueModal() {
      document.getElementById('overdueModal').style.display = 'none';
    }

    function openROIModal() {
      document.getElementById('roiModal').style.display = 'block';
      renderROIModal();
    }

    function closeROIModal() {
      document.getElementById('roiModal').style.display = 'none';
    }

    function updateBalancePreview() {
      const paymentAmount = parseFloat(document.getElementById('paymentAmount').value) || 0;
      const borrowerId = parseInt(document.getElementById('updatePaymentForm').dataset.borrowerId);
      const borrower = borrowersData.find(b => b.id === borrowerId);

      if (borrower) {
        const remainingBalance = borrower.amount - borrower.paidAmount;
        const newBalance = remainingBalance - paymentAmount;
        document.getElementById('newRemainingBalance').textContent = `₱${Math.max(0, newBalance).toLocaleString()}`;
      }
    }

    function handlePaymentUpdate(event) {
      event.preventDefault();

      const borrowerId = parseInt(document.getElementById('updatePaymentForm').dataset.borrowerId);
      const paymentAmount = parseFloat(document.getElementById('paymentAmount').value);
      const borrower = borrowersData.find(b => b.id === borrowerId);

      if (!borrower || paymentAmount <= 0) {
        alert('Please enter a valid payment amount');
        return;
      }

      const remainingBalance = borrower.amount - borrower.paidAmount;
      if (paymentAmount > remainingBalance) {
        alert(`Payment cannot exceed remaining balance of ₱${remainingBalance.toLocaleString()}`);
        return;
      }

      // Update borrower data
      borrower.paidAmount += paymentAmount;
      const newRemainingBalance = borrower.amount - borrower.paidAmount;

      // Show confirmation
      alert(`✓ Payment of ₱${paymentAmount.toLocaleString()} recorded for ${borrower.name}\nNew Balance: ₱${newRemainingBalance.toLocaleString()}`);

      // Close modal and reload borrowers list
      closeUpdatePaymentModal();
      loadBorrowersList();

      // Update all dashboard metrics and borrowers performance
      updateDashboardMetrics();
      renderBorrowersPerformance();

      // In production, send this to your Laravel backend:
      // fetch(`/api/borrowers/${borrowerId}/payment`, {
      //   method: 'POST',
      //   headers: { 'Content-Type': 'application/json' },
      //   body: JSON.stringify({ amount: paymentAmount })
      // })
    }

    function renderActiveLoansModal() {
      const content = document.getElementById('activeLoansContent');
      const activeLoans = borrowersData.filter(b => (b.amount - b.paidAmount) > 0);

      let html = '<div style="display: flex; flex-direction: column; gap: 12px;">';
      activeLoans.forEach(borrower => {
        const remaining = borrower.amount - borrower.paidAmount;
        const paid = (borrower.paidAmount / borrower.amount) * 100;
        html += `
          <div style="background: #F5F7FA; padding: 16px; border-radius: 10px; border: 1px solid #DDE2EB;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
              <div style="font-weight: 700; color: #0D1B3E; font-size: 15px;">${borrower.name}</div>
              <div style="font-size: 13px; color: #64748b; background: #FFFFFF; padding: 4px 12px; border-radius: 6px; border: 1px solid #E2E6EF;">Loan #${borrower.id}</div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px; font-size: 13px;">
              <div><span style="color: #64748b;">Total Loan:</span> <span style="font-weight: 700; color: #0D1B3E;">₱${borrower.amount.toLocaleString()}</span></div>
              <div><span style="color: #64748b;">Remaining:</span> <span style="font-weight: 700; color: #D64545;">₱${remaining.toLocaleString()}</span></div>
            </div>
            <div style="background: #FFFFFF; border-radius: 6px; overflow: hidden; height: 6px;">
              <div style="background: linear-gradient(90deg, #1A73E8 0%, #1557B0 100%); width: ${paid}%; height: 100%;"></div>
            </div>
            <div style="margin-top: 8px; font-size: 12px; color: #64748b;">Progress: ${Math.round(paid)}% paid</div>
          </div>
        `;
      });
      html += '</div>';
      content.innerHTML = activeLoans.length > 0 ? html : '<div style="padding: 20px; text-align: center; color: #64748b;">No active loans</div>';
    }

    function renderOverdueModal() {
      const content = document.getElementById('overdueContent');
      const overdueLoans = borrowersData.filter(b => {
        const daysUntilDue = Math.ceil((b.dueDate - new Date()) / (1000 * 60 * 60 * 24));
        return daysUntilDue < 0;
      });

      let html = '<div style="display: flex; flex-direction: column; gap: 12px;">';
      overdueLoans.forEach(borrower => {
        const daysOverdue = Math.abs(Math.ceil((borrower.dueDate - new Date()) / (1000 * 60 * 60 * 24)));
        const remaining = borrower.amount - borrower.paidAmount;
        html += `
          <div style="background: #FDECEC; padding: 16px; border-radius: 10px; border: 1px solid #E8C5C5;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
              <div style="font-weight: 700; color: #D64545; font-size: 15px;">${borrower.name}</div>
              <div style="font-size: 12px; color: #D64545; background: #FDECEC; padding: 4px 12px; border-radius: 6px; border: 1px solid #E8C5C5;">⚠ ${daysOverdue} days overdue</div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; font-size: 13px;">
              <div><span style="color: #64748b;">Total Loan:</span> <span style="font-weight: 700; color: #0D1B3E;">₱${borrower.amount.toLocaleString()}</span></div>
              <div><span style="color: #64748b;">Remaining:</span> <span style="font-weight: 700; color: #D64545;">₱${remaining.toLocaleString()}</span></div>
              <div><span style="color: #64748b;">Paid:</span> <span style="font-weight: 700; color: #1F8F50;">₱${borrower.paidAmount.toLocaleString()}</span></div>
            </div>
          </div>
        `;
      });
      html += '</div>';
      content.innerHTML = overdueLoans.length > 0 ? html : '<div style="padding: 20px; text-align: center; color: #64748b;">No overdue accounts</div>';
    }

    function renderROIModal() {
      const content = document.getElementById('roiContent');

      // Calculate ROI metrics
      const totalLoaned = borrowersData.reduce((sum, b) => sum + b.amount, 0);
      const totalInterest = borrowersData.reduce((sum, b) => sum + (b.paidAmount - b.amount) * 0, 0) ||
                            borrowersData.reduce((sum, b) => {
                              const roi = ((b.paidAmount / b.amount) - 1) * 100;
                              return sum + (b.amount * (roi / 100));
                            }, 0);
      const avgROI = borrowersData.length > 0 ?
                     (borrowersData.reduce((sum, b) => {
                       const roi = ((b.paidAmount / b.amount) - 1) * 100;
                       return sum + roi;
                     }, 0) / borrowersData.length).toFixed(2) : 0;

      let html = `
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; margin-bottom: 24px;">
          <div style="background: linear-gradient(135deg, #E8F7EE 0%, #F0FBF7 100%); padding: 20px; border-radius: 12px; border: 1px solid #D5F0E4;">
            <div style="font-size: 12px; color: #64748b; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Total Loaned</div>
            <div style="font-size: 28px; font-weight: 700; color: #1F8F50;">₱${totalLoaned.toLocaleString()}</div>
          </div>
          <div style="background: linear-gradient(135deg, #E0F2FE 0%, #E8F8FE 100%); padding: 20px; border-radius: 12px; border: 1px solid #C8E6F5;">
            <div style="font-size: 12px; color: #64748b; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Average ROI</div>
            <div style="font-size: 28px; font-weight: 700; color: #0369A1;">${avgROI}%</div>
          </div>
          <div style="background: linear-gradient(135deg, #FFF6DD 0%, #FFFCE8 100%); padding: 20px; border-radius: 12px; border: 1px solid #F5E6B8;">
            <div style="font-size: 12px; color: #64748b; margin-bottom: 8px; text-transform: uppercase; font-weight: 600;">Interest Income</div>
            <div style="font-size: 28px; font-weight: 700; color: #E6A100;">₱${Math.round(totalInterest).toLocaleString()}</div>
          </div>
        </div>
        <div style="background: #F5F7FA; padding: 20px; border-radius: 12px; border: 1px solid #DDE2EB;">
          <div style="font-weight: 700; color: #0D1B3E; margin-bottom: 16px; font-size: 14px;">ROI by Borrower</div>
          <div style="display: flex; flex-direction: column; gap: 12px;">
      `;

      borrowersData.forEach(borrower => {
        const roi = ((borrower.paidAmount / borrower.amount) - 1) * 100;
        const bgColor = roi >= 0 ? '#E8F7EE' : '#FDECEC';
        const textColor = roi >= 0 ? '#1F8F50' : '#D64545';

        html += `
          <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: white; border-radius: 8px; border: 1px solid #E2E6EF;">
            <div style="flex: 1;">
              <div style="font-weight: 600; color: #0D1B3E; font-size: 14px;">${borrower.name}</div>
              <div style="font-size: 12px; color: #64748b;">Loaned: ₱${borrower.amount.toLocaleString()} | Paid: ₱${borrower.paidAmount.toLocaleString()}</div>
            </div>
            <div style="background: ${bgColor}; color: ${textColor}; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 14px;">${roi.toFixed(2)}%</div>
          </div>
        `;
      });

      html += `
          </div>
        </div>
      `;

      content.innerHTML = html;
    }

    function updateDashboardMetrics() {
      // Calculate metrics
      const totalLoaned = borrowersData.reduce((sum, b) => sum + b.amount, 0);
      const totalPaid = borrowersData.reduce((sum, b) => sum + b.paidAmount, 0);
      const totalRemaining = totalLoaned - totalPaid;
      const activeLoanCount = borrowersData.filter(b => (b.amount - b.paidAmount) > 0).length;
      const overdueCount = borrowersData.filter(b => {
        const daysUntilDue = Math.ceil((b.dueDate - new Date()) / (1000 * 60 * 60 * 24));
        return daysUntilDue < 0 && (b.amount - b.paidAmount) > 0;
      }).length;

      // Calculate interest (assuming 10% annual interest, pro-rata)
      const interestEarned = Math.round(totalPaid * 0.10);

      // Calculate ROI
      const roi = totalPaid > 0 ? Math.round((interestEarned / totalPaid) * 100) : 0;

      // Determine portfolio status
      const status = roi > 0 ? 'Profitable' : 'Pending';

      // Update UI elements
      document.getElementById('todayCollectible').textContent = totalRemaining.toLocaleString();
      document.getElementById('totalCollected').textContent = `₱${totalPaid.toLocaleString()}`;
      document.getElementById('activeLoansCount').textContent = activeLoanCount;
      document.getElementById('overdueCount').textContent = overdueCount;
      document.getElementById('avgROI').textContent = `${roi}%`;
      document.getElementById('capitalReleased').textContent = `₱${totalLoaned.toLocaleString()}`;
      document.getElementById('interestEarned').textContent = `₱${interestEarned.toLocaleString()}`;
      document.getElementById('roiPercentage').textContent = `${roi}%`;
      document.getElementById('portfolioStatus').textContent = status;
      document.getElementById('totalRemainingBalanceCard').textContent = `₱${totalRemaining.toLocaleString()}`;

      // Chart uses static SVG
    }

    // Calculate payable amount based on loan amount and interest rate
    function updatePayableAmount() {
      const loanAmount = parseFloat(document.getElementById('loanAmount').value) || 0;
      const interestRate = parseFloat(document.getElementById('interestRate').value) || 0;

      const interest = (loanAmount * interestRate) / 100;
      const payableAmount = loanAmount + interest;

      document.getElementById('payableAmount').value = payableAmount.toFixed(2);
    }

    // Load recent payments from API
    async function loadRecentPayments() {
      try {
        const response = await fetch('/api/recent-payments');
        const payments = await response.json();
        const paymentsList = document.getElementById('recentPaymentsList');

        if (!payments || payments.length === 0) {
          paymentsList.innerHTML = '<div style="text-align: center; padding: 40px; color: #64748b;">No recent payments yet</div>';
          return;
        }

        paymentsList.innerHTML = payments.map(payment => `
          <div class="payment-item">
            <div class="payment-info">
              <div class="payment-name">${payment.borrower_name}</div>
              <div class="payment-loan">Loan Amount: ₱${parseInt(payment.loan_amount).toLocaleString()}</div>
            </div>
            <div style="text-align:right;">
              <div class="payment-amount">₱${parseInt(payment.amount).toLocaleString()}</div>
              <div class="payment-date">${new Date(payment.created_at).toLocaleDateString()}</div>
            </div>
          </div>
        `).join('');
      } catch (error) {
        console.error('Error loading recent payments:', error);
        document.getElementById('recentPaymentsList').innerHTML = '<div style="text-align: center; padding: 40px; color: #64748b;">Unable to load payments</div>';
      }
    }

    // Update payment schedule info based on frequency
    function updatePaymentSchedule() {
      const frequency = document.getElementById('paymentFrequency').value;
      const scheduleInfo = document.getElementById('scheduleInfo');
      const twiceMonthlyDays = document.getElementById('twiceMonthlyDays');

      if (frequency === 'daily') {
        scheduleInfo.innerHTML = '💡 Borrower will pay <strong>daily</strong> throughout the contract period.';
        scheduleInfo.style.display = 'block';
        twiceMonthlyDays.style.display = 'none';
      } else if (frequency === 'weekly') {
        scheduleInfo.innerHTML = '💡 Borrower will pay <strong>every week</strong> on the same day.';
        scheduleInfo.style.display = 'block';
        twiceMonthlyDays.style.display = 'none';
      } else if (frequency === 'twice-monthly') {
        scheduleInfo.innerHTML = '💡 Borrower will pay <strong>twice a month</strong> on specified days.';
        scheduleInfo.style.display = 'block';
        twiceMonthlyDays.style.display = 'block';
      } else if (frequency === 'monthly') {
        scheduleInfo.innerHTML = '💡 Borrower will pay <strong>once a month</strong> on the same date.';
        scheduleInfo.style.display = 'block';
        twiceMonthlyDays.style.display = 'none';
      } else {
        scheduleInfo.style.display = 'none';
        twiceMonthlyDays.style.display = 'none';
      }
    }

    // Handle form submission
    function handleAddAccount(event) {
      event.preventDefault();

      // Collect form data
      const formData = {
        borrowerName: document.getElementById('borrowerName').value,
        borrowerEmail: document.getElementById('borrowerEmail').value,
        borrowerPhone: document.getElementById('borrowerPhone').value,
        borrowerAddress: document.getElementById('borrowerAddress').value,
        loanAmount: document.getElementById('loanAmount').value,
        loanDays: document.getElementById('loanDays').value,
        paymentFrequency: document.getElementById('paymentFrequency').value,
        paymentDay1: document.getElementById('paymentDay1').value || null,
        paymentDay2: document.getElementById('paymentDay2').value || null,
      };

      // Validate twice-monthly payment days
      if (formData.paymentFrequency === 'twice-monthly') {
        if (!formData.paymentDay1 || !formData.paymentDay2) {
          alert('Please specify both payment days for twice-monthly payments');
          return;
        }
        if (formData.paymentDay1 === formData.paymentDay2) {
          alert('Payment days must be different');
          return;
        }
      }

      // Log the data (in production, this would be sent to the server)
      console.log('New Account Created:', formData);
      alert(`Account created for ${formData.borrowerName}\nLoan: ₱${formData.loanAmount}\nDuration: ${formData.loanDays} days\nPayment: ${formData.paymentFrequency}`);

      // Add new borrower to the data array
      const newBorrower = {
        id: borrowersData.length + 1,
        name: formData.borrowerName,
        email: formData.borrowerEmail,
        phone: formData.borrowerPhone,
        address: formData.borrowerAddress,
        amount: parseInt(formData.loanAmount),
        paidAmount: 0,
        days: parseInt(formData.loanDays),
        dueDate: new Date(Date.now() + parseInt(formData.loanDays) * 24 * 60 * 60 * 1000),
        status: 'on-time'
      };
      borrowersData.push(newBorrower);

      // Clear form and close modal
      closeModal();

      // Update dashboard and performance list
      updateDashboardMetrics();
      renderBorrowersPerformance();

      // In production, you would send this to your Laravel backend
      // Example: fetch('/api/borrowers', { method: 'POST', body: JSON.stringify(formData) })
    }

    // Function to render borrowers performance with credit scores
    function renderBorrowersPerformance() {
      const performanceList = document.getElementById('borrowersPerformanceList');
      performanceList.innerHTML = '';

      // Calculate credit scores for all borrowers
      const borrowersWithScores = borrowersData.map((borrower) => {
        const remainingBalance = borrower.amount - borrower.paidAmount;
        const daysUntilDue = Math.ceil((borrower.dueDate - new Date()) / (1000 * 60 * 60 * 24));

        // Calculate credit score (0-900) based on payment behavior
        let creditScore = 750; // Base score

        if (remainingBalance === 0) {
          creditScore = 900; // Perfect payment
        } else if (daysUntilDue < 0) {
          creditScore = 600 + (Math.abs(daysUntilDue) * -10); // Deduct for each delayed day
          creditScore = Math.max(300, creditScore);
        } else if (daysUntilDue <= 3) {
          creditScore = 720;
        } else {
          const paidPercentage = (borrower.paidAmount / borrower.amount) * 100;
          creditScore = 750 + (paidPercentage * 0.3); // Bonus for paid percentage
        }

        // Convert 0-900 scale to 0-100 percentage
        const creditScorePercentage = Math.round((creditScore / 900) * 100);

        return { ...borrower, creditScore, creditScorePercentage };
      });

      // Sort by credit score descending (highest first)
      const sortedBorrowers = borrowersWithScores.sort((a, b) => b.creditScore - a.creditScore);

      sortedBorrowers.forEach((borrower) => {
        const creditScorePercentage = borrower.creditScorePercentage;

        // Determine background color based on score
        let bgColor = '#F5F7FA';
        let textColor = '#64748b';

        if (creditScorePercentage >= 80) {
          bgColor = '#E8F7EE'; // Green background
          textColor = '#1F8F50'; // Green text
        } else if (creditScorePercentage >= 60) {
          bgColor = '#E0F2FE'; // Light blue background
          textColor = '#0369A1'; // Blue text
        } else {
          bgColor = '#FDECEC'; // Red background
          textColor = '#D64545'; // Red text
        }

        const initials = borrower.name.split(' ').map(n => n[0]).join('').toUpperCase();

        const performanceItem = document.createElement('div');
        performanceItem.className = 'borrower-performance-item';
        performanceItem.innerHTML = `
          <div class="borrower-performance-info">
            <div class="avatar" style="background: linear-gradient(135deg, #134376 0%, #0F2D5F 100%); color: white; font-weight: 700; font-size: 14px;">${initials}</div>
            <div class="borrower-details">
              <div class="borrower-name">${borrower.name}</div>
              <div class="borrower-status" style="display: flex; gap: 12px; margin-top: 4px; font-size: 12px;">
                <span style="color: #64748b;">Loan: ₱${borrower.amount.toLocaleString()}</span>
                <span style="color: #64748b;">Paid: ₱${borrower.paidAmount.toLocaleString()}</span>
              </div>
            </div>
          </div>
          <div class="borrower-credit-score" style="display: flex; flex-direction: column; align-items: flex-end; gap: 6px;">
            <div style="font-size: 24px; font-weight: 700; color: #0D1B3E;">${creditScorePercentage}%</div>
            <div style="font-size: 11px; color: ${textColor}; background: ${bgColor}; padding: 3px 8px; border-radius: 6px; font-weight: 600;">Credit Score</div>
          </div>
        `;
        performanceList.appendChild(performanceItem);
      });
    }

    // Initialize dashboard on page load
    document.addEventListener('DOMContentLoaded', async function() {
      await fetchBorrowersData();
      await loadRecentPayments();
      renderBorrowersPerformance();
      updateDashboardMetrics();
    });

    // Chart using SVG - no JS initialization needed
    updateDashboardMetrics();
  </script>

  <footer class="dashboard-footer">
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
