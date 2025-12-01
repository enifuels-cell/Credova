<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Credova - Settings</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --brand: #0D3B66;
      --brand-light: #EBF3FA;
      --brand-dark: #082640;
      --text-main: #1A1A1A;
      --text-muted: #6B7280;
      --bg-main: #F6F8FA;
      --card-bg: #FFFFFF;
      --radius: 12px;
      --shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'SF Pro Display', sans-serif;
      background: var(--bg-main);
      color: var(--text-main);
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      min-height: 100vh;
    }

    /* Header */
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

    .logo {
      width: 100px;
      height: 100px;
      border-radius: 12px;
      object-fit: cover;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .logo:hover {
      transform: scale(1.05);
      box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.2);
    }

    .brand h1 {
      font-size: 28px;
      font-weight: 700;
      letter-spacing: -0.4px;
      line-height: 1.05;
      color: #FFFFFF;
    }

    .tagline {
      font-size: 13px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      opacity: 0.8;
      color: #FFFFFF;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      padding: 0 20px;
      width: 100%;
    }

    .settings-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 32px;
    }

    .settings-header h2 {
      font-size: 32px;
      font-weight: 700;
      color: var(--text-main);
    }

    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      color: var(--brand);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      padding: 8px 16px;
      border-radius: 8px;
    }

    .back-link:hover {
      background: var(--brand-light);
      transform: translateX(-4px);
    }

    /* Settings Card */
    .settings-card {
      background: var(--card-bg);
      border-radius: var(--radius);
      padding: 32px;
      box-shadow: var(--shadow);
      margin-bottom: 24px;
    }

    .settings-section {
      margin-bottom: 32px;
    }

    .settings-section:last-child {
      margin-bottom: 0;
    }

    .settings-section h3 {
      font-size: 18px;
      font-weight: 700;
      color: var(--text-main);
      margin-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .settings-description {
      font-size: 14px;
      color: var(--text-muted);
      margin-bottom: 16px;
    }

    .settings-divider {
      height: 1px;
      background: #E5E7EB;
      margin: 24px 0;
    }

    /* Form Styles */
    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: var(--text-main);
      margin-bottom: 8px;
    }

    .form-group input[type="password"],
    .form-group input[type="email"] {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #E5E7EB;
      border-radius: 8px;
      font-size: 14px;
      font-family: inherit;
      transition: all 0.2s ease;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--brand);
      box-shadow: 0 0 0 3px rgba(13, 59, 102, 0.1);
    }

    .form-group input::placeholder {
      color: var(--text-muted);
    }

    /* Buttons */
    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: var(--brand);
      color: white;
      box-shadow: 0 4px 12px rgba(13, 59, 102, 0.25);
    }

    .btn-primary:hover {
      background: var(--brand-dark);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(13, 59, 102, 0.35);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .btn-secondary {
      background: var(--brand-light);
      color: var(--brand);
      border: 2px solid var(--brand);
    }

    .btn-secondary:hover {
      background: var(--brand);
      color: white;
    }

    .btn-danger {
      background: #EF4444;
      color: white;
    }

    .btn-danger:hover {
      background: #DC2626;
    }

    /* Alert Messages */
    .alert {
      padding: 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .alert-success {
      background: #DBEAFE;
      color: #0369A1;
      border-left: 4px solid #0284C7;
    }

    .alert-error {
      background: #FEE2E2;
      color: #991B1B;
      border-left: 4px solid #DC2626;
    }

    .alert-info {
      background: #DBEAFE;
      color: #075985;
      border-left: 4px solid #0284C7;
    }

    /* PIN Input Grid */
    .pin-setup-section {
      background: var(--brand-light);
      padding: 20px;
      border-radius: 8px;
      margin-top: 16px;
    }

    .pin-status {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 16px;
      background: #DBEAFE;
      border-radius: 8px;
      margin-bottom: 16px;
      border-left: 4px solid var(--brand);
    }

    .pin-status.not-set {
      background: #FEF3C7;
      border-left-color: #FBBF24;
    }

    .pin-status-icon {
      font-size: 20px;
    }

    .pin-status-text {
      flex: 1;
    }

    .pin-status-text p {
      margin: 0;
      font-size: 14px;
    }

    .pin-status-text p.label {
      color: var(--text-muted);
      font-size: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .pin-status-text p.value {
      font-weight: 700;
      color: var(--text-main);
      margin-top: 4px;
    }

    /* Footer */
    .settings-footer {
      display: flex;
      gap: 12px;
      margin-top: 24px;
    }

    /* Responsive */
    @media (max-width: 640px) {
      header {
        padding: 20px;
        gap: 12px;
      }

      .logo {
        width: 70px;
        height: 70px;
      }

      .brand h1 {
        font-size: 20px;
      }

      .container {
        margin: 20px auto;
        padding: 0 16px;
      }

      .settings-header {
        flex-direction: column;
        align-items: flex-start;
      }

      .settings-card {
        padding: 20px;
      }

      .settings-header h2 {
        font-size: 24px;
        margin-bottom: 16px;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <a href="{{ route('dashboard') }}" style="text-decoration: none; display: flex; align-items: center; gap: 8px; flex: 1;">
      <img src="{{ asset('logo1.png') }}" alt="CREDOVA Logo" class="logo">
      <div class="brand">
        <h1>CREDOVA</h1>
        <div class="tagline">Track. Manage. Grow</div>
      </div>
    </a>
  </header>

  <div class="container">
    <!-- Settings Header -->
    <div class="settings-header">
      <h2>⚙️ Settings</h2>
      <a href="{{ route('dashboard') }}" class="back-link">← Back to Dashboard</a>
    </div>

    <!-- Settings Card -->
    <div class="settings-card">
      <!-- PIN Setup Section -->
      <div class="settings-section">
        <h3>🔐 PIN Security</h3>
        <p class="settings-description">Set up a PIN for quick and secure login on your devices</p>

        @if (session('success'))
          <div class="alert alert-success">
            <span class="pin-status-icon">✓</span>
            <div>{{ session('success') }}</div>
          </div>
        @endif

        @if ($errors->any())
          <div class="alert alert-error">
            <span class="pin-status-icon">✕</span>
            <div>
              @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- PIN Status -->
        <div class="pin-status {{ $userPin && $userPin->is_set ? '' : 'not-set' }}">
          <span class="pin-status-icon">{{ $userPin && $userPin->is_set ? '✓' : '⚠️' }}</span>
          <div class="pin-status-text">
            <p class="label">Current Status</p>
            <p class="value">
              @if ($userPin && $userPin->is_set)
                PIN is set (Set on {{ $userPin->pin_set_at->format('M d, Y') }})
              @else
                PIN is not set
              @endif
            </p>
          </div>
        </div>

        <!-- PIN Setup Form -->
        <form method="POST" action="{{ route('settings.update-pin') }}">
          @csrf

          <div class="form-group">
            <label for="current_password">
              {{ $userPin && $userPin->is_set ? 'Confirm Password to Change PIN' : 'Confirm Password to Set PIN' }}
            </label>
            <input
              type="password"
              id="current_password"
              name="current_password"
              placeholder="Enter your password"
              required>
          </div>

          <div class="form-group">
            <label for="pin">
              {{ $userPin && $userPin->is_set ? 'New PIN' : 'Set Your PIN' }}
            </label>
            <input
              type="password"
              id="pin"
              name="pin"
              placeholder="4-6 digits"
              inputmode="numeric"
              pattern="[0-9]{4,6}"
              required>
            <p class="settings-description" style="margin-top: 8px;">Must be 4-6 digits long</p>
          </div>

          <div class="form-group">
            <label for="pin_confirmation">Confirm PIN</label>
            <input
              type="password"
              id="pin_confirmation"
              name="pin_confirmation"
              placeholder="Re-enter your PIN"
              inputmode="numeric"
              pattern="[0-9]{4,6}"
              required>
          </div>

          <div class="settings-footer">
            <button type="submit" class="btn btn-primary">
              {{ $userPin && $userPin->is_set ? '🔄 Update PIN' : '✓ Set PIN' }}
            </button>
          </div>
        </form>
      </div>

      <div class="settings-divider"></div>

      <!-- Account Section -->
      <div class="settings-section">
        <h3>👤 Account</h3>
        <p class="settings-description">Your account information</p>

        <div class="form-group">
          <label>Email Address</label>
          <input type="email" value="{{ Auth::user()->email }}" disabled style="background: #F3F4F6; cursor: not-allowed;">
        </div>

        <div class="form-group">
          <label>Name</label>
          <input type="text" value="{{ Auth::user()->name }}" disabled style="background: #F3F4F6; cursor: not-allowed;">
        </div>

        <div class="form-group">
          <label>Member Since</label>
          <input type="text" value="{{ Auth::user()->created_at->format('M d, Y') }}" disabled style="background: #F3F4F6; cursor: not-allowed;">
        </div>
      </div>
    </div>
  </div>

  <script>
    // Sidebar Toggle (if needed)
    const logoButton = document.querySelector('.logo');
    const sidebar = document.getElementById('sidebar');

    if (logoButton && sidebar) {
      logoButton.addEventListener('click', function(e) {
        e.preventDefault();
        sidebar.classList.toggle('active');
      });
    }

    // PIN input formatting
    const pinInputs = document.querySelectorAll('input[pattern*="0-9"]');
    pinInputs.forEach(input => {
      input.addEventListener('input', function() {
        // Remove non-numeric characters
        this.value = this.value.replace(/[^0-9]/g, '');
        // Limit to 6 digits
        if (this.value.length > 6) {
          this.value = this.value.slice(0, 6);
        }
      });

      input.addEventListener('keydown', function(e) {
        // Allow backspace
        if (e.key !== 'Backspace' && e.key !== 'Tab' && !/[0-9]/.test(e.key)) {
          e.preventDefault();
        }
      });
    });
  </script>
</body>
</html>
