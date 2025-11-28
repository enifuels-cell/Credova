<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credova - PIN Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: linear-gradient(135deg, #134376 0%, #0F2D5F 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pin-login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100vh;
            padding: 20px;
        }

        .pin-login-content {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 380px;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            padding: 30px 20px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .logo-section {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 10px;
        }

        .logo-section img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            color: #134376;
            font-size: 22px;
            margin-bottom: 4px;
        }

        .header p {
            color: #666;
            font-size: 13px;
        }

        .pin-display-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .pin-display {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .pin-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ddd;
            border: 2px solid #134376;
            transition: all 0.2s ease;
        }

        .pin-dot.filled {
            background: #134376;
            box-shadow: 0 0 8px rgba(19, 67, 118, 0.4);
        }

        .form-group {
            display: none;
        }

        /* Number Pad Styles */
        .numberpad-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin: 20px 0;
        }

        .numberpad-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .number-button {
            width: 100%;
            aspect-ratio: 1;
            border: none;
            border-radius: 12px;
            font-size: 24px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.15s cubic-bezier(0.34, 1.56, 0.64, 1);
            background: linear-gradient(135deg, #4A90E2 0%, #357ABD 100%);
            box-shadow:
                0 10px 25px rgba(74, 144, 226, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 2px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .number-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.4) 0%, rgba(255, 255, 255, 0) 50%);
            pointer-events: none;
        }

        .number-button:active {
            transform: scale(0.95);
            box-shadow:
                0 5px 15px rgba(74, 144, 226, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -2px 4px rgba(0, 0, 0, 0.15);
        }

        .number-button:hover {
            transform: scale(1.05);
            box-shadow:
                0 15px 35px rgba(74, 144, 226, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.4),
                inset 0 -1px 2px rgba(0, 0, 0, 0.1);
        }

        .number-button.delete-btn {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            box-shadow:
                0 10px 25px rgba(239, 68, 68, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -1px 2px rgba(0, 0, 0, 0.1);
            font-size: 20px;
        }

        .number-button.delete-btn:active {
            box-shadow:
                0 5px 15px rgba(239, 68, 68, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.3),
                inset 0 -2px 4px rgba(0, 0, 0, 0.15);
        }

        .number-button.delete-btn:hover {
            box-shadow:
                0 15px 35px rgba(239, 68, 68, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.4),
                inset 0 -1px 2px rgba(0, 0, 0, 0.1);
        }

        /* Hidden PIN input */
        #pinInput {
            display: none;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px;
            background: #eff6ff;
            border-radius: 8px;
            border-left: 4px solid #134376;
            margin: 10px 0;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #134376;
        }

        .checkbox-group label {
            color: #333;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
        }

        .login-button {
            background: linear-gradient(135deg, #134376 0%, #0F2D5F 100%);
            color: white;
            padding: 12px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(19, 67, 118, 0.3);
            width: 100%;
            margin-top: 10px;
        }

        .login-button:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 67, 118, 0.4);
        }

        .login-button:active:not(:disabled) {
            transform: translateY(0);
        }

        .login-button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .login-links {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }

        .login-link {
            color: #134376;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .info-box {
            background: #eff6ff;
            border-left: 4px solid #134376;
            padding: 10px;
            border-radius: 4px;
            font-size: 12px;
            color: #134376;
            line-height: 1.4;
            margin: 10px 0;
        }

        .email-input-group {
            margin: 15px 0;
        }

        .email-input-group input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .email-input-group input[type="email"]:focus {
            outline: none;
            border-color: #134376;
            box-shadow: 0 0 0 3px rgba(19, 67, 118, 0.1);
        }

        .email-input-group input[type="email"]::placeholder {
            color: #999;
        }

        /* Mobile Responsive */
        @media (max-width: 480px) {
            .pin-login-content {
                padding: 25px 15px;
                gap: 15px;
            }

            .logo-section img {
                width: 80px;
                height: 80px;
            }

            .header h1 {
                font-size: 20px;
            }

            .numberpad-row {
                gap: 8px;
            }

            .number-button {
                font-size: 20px;
                border-radius: 10px;
            }

            .pin-dot {
                width: 14px;
                height: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="pin-login-container">
        <div class="pin-login-content">
            <!-- Logo -->
            <div class="logo-section">
                <img src="{{ asset('credovalogo.png') }}" alt="Credova Logo">
            </div>

            <!-- Header -->
            <div class="header">
                <h1>🔐 PIN Login</h1>
                <p>Enter your PIN</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Info -->
            <div class="info-box">
                ℹ️ Enter your email and PIN to access your account
            </div>

            <!-- Email Input (Visible) -->
            <div class="email-input-group">
                <input type="email"
                       name="email"
                       id="emailInput"
                       placeholder="Enter your email"
                       value="{{ old('email', request('email', '')) }}"
                       required>
            </div>            <!-- PIN Display -->
            <div class="pin-display-container">
                <div class="pin-display" id="pinDisplay">
                    <div class="pin-dot"></div>
                    <div class="pin-dot"></div>
                    <div class="pin-dot"></div>
                    <div class="pin-dot"></div>
                    <div class="pin-dot"></div>
                    <div class="pin-dot"></div>
                </div>
            </div>

            <!-- Form -->
            <form id="pinForm" method="POST" action="{{ route('pin-login-submit') }}">
                @csrf

                <!-- Email input is now in the form directly -->
                <!-- Hidden PIN input -->
                <input type="hidden" name="pin" id="pinInput">

                <!-- Number Pad -->
                <div class="numberpad-container">
                    <!-- Row 1 -->
                    <div class="numberpad-row">
                        <button type="button" class="number-button" data-number="1">1</button>
                        <button type="button" class="number-button" data-number="2">2</button>
                        <button type="button" class="number-button" data-number="3">3</button>
                    </div>

                    <!-- Row 2 -->
                    <div class="numberpad-row">
                        <button type="button" class="number-button" data-number="4">4</button>
                        <button type="button" class="number-button" data-number="5">5</button>
                        <button type="button" class="number-button" data-number="6">6</button>
                    </div>

                    <!-- Row 3 -->
                    <div class="numberpad-row">
                        <button type="button" class="number-button" data-number="7">7</button>
                        <button type="button" class="number-button" data-number="8">8</button>
                        <button type="button" class="number-button" data-number="9">9</button>
                    </div>

                    <!-- Row 4 -->
                    <div class="numberpad-row">
                        <button type="button" class="number-button" data-number="0" style="grid-column: 2;">0</button>
                        <button type="button" class="number-button delete-btn" id="deleteBtn">⌫</button>
                    </div>
                </div>

                <!-- Trust Device Checkbox -->
                <div class="checkbox-group">
                    <input id="trust_device"
                           type="checkbox"
                           name="trust_device"
                           value="1">
                    <label for="trust_device">Trust this device</label>
                </div>

                <!-- Login Button -->
                <button type="submit" class="login-button" id="loginBtn" disabled>
                    LOGIN
                </button>

                <!-- Links -->
                <div class="login-links">
                    <a href="{{ route('login') }}" class="login-link">Email & Password</a>
                    <a href="{{ route('register') }}" class="login-link">Register</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let pinCode = '';
        const maxPinLength = 6;
        const pinDisplay = document.getElementById('pinDisplay');
        const pinInput = document.getElementById('pinInput');
        const loginBtn = document.getElementById('loginBtn');
        const deleteBtn = document.getElementById('deleteBtn');
        const numberButtons = document.querySelectorAll('.number-button[data-number]');
        const pinDots = document.querySelectorAll('.pin-dot');

        // Number button clicks
        numberButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                if (pinCode.length < maxPinLength) {
                    pinCode += button.dataset.number;
                    updateDisplay();
                }
            });
        });

        // Delete button
        deleteBtn.addEventListener('click', (e) => {
            e.preventDefault();
            pinCode = pinCode.slice(0, -1);
            updateDisplay();
        });

        // Update display
        function updateDisplay() {
            pinInput.value = pinCode;

            // Update dots
            pinDots.forEach((dot, index) => {
                if (index < pinCode.length) {
                    dot.classList.add('filled');
                } else {
                    dot.classList.remove('filled');
                }
            });

            // Enable/disable login button
            loginBtn.disabled = pinCode.length < 4 || pinCode.length > 6;
        }

        // Auto-submit when pin reaches required length (optional)
        // Uncomment to enable auto-submit after 4 digits
        // function updateDisplay() {
        //     pinInput.value = pinCode;
        //     pinDots.forEach((dot, index) => {
        //         if (index < pinCode.length) {
        //             dot.classList.add('filled');
        //         } else {
        //             dot.classList.remove('filled');
        //         }
        //     });
        //     loginBtn.disabled = pinCode.length < 4;
        //
        //     if (pinCode.length === 6) {
        //         document.getElementById('pinForm').submit();
        //     }
        // }

        // Form submission
        document.getElementById('pinForm').addEventListener('submit', (e) => {
            if (pinCode.length < 4 || pinCode.length > 6) {
                e.preventDefault();
                alert('PIN must be 4-6 digits');
            }
        });

        // Keyboard support
        document.addEventListener('keydown', (e) => {
            if (e.key >= '0' && e.key <= '9' && pinCode.length < maxPinLength) {
                pinCode += e.key;
                updateDisplay();
            } else if (e.key === 'Backspace') {
                pinCode = pinCode.slice(0, -1);
                updateDisplay();
            }
        });

        // Initialize
        updateDisplay();
    </script>
</body>

</html>
