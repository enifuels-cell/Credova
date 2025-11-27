<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credova - Verify PIN</title>

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

        .verify-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .verify-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
            max-width: 400px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .verify-header {
            text-align: center;
        }

        .verify-header h1 {
            color: #134376;
            font-size: 24px;
            margin-bottom: 8px;
        }

        .verify-header p {
            color: #666;
            font-size: 14px;
        }

        .device-info {
            background: #f3f4f6;
            padding: 16px;
            border-radius: 8px;
            margin: 10px 0;
        }

        .device-info h3 {
            color: #134376;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .device-detail {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #666;
            margin: 4px 0;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        .form-label {
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .pin-input {
            background: #f3f4f6;
            border: 2px solid rgba(19, 67, 118, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-family: monospace;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .pin-input:focus {
            outline: none;
            border-color: #134376;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(19, 67, 118, 0.1);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 20px 0;
            padding: 12px;
            background: #eff6ff;
            border-radius: 8px;
            border-left: 4px solid #134376;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #134376;
        }

        .checkbox-group label {
            color: #333;
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .verify-button {
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
            margin-top: 16px;
        }

        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 67, 118, 0.4);
        }

        .verify-button:active {
            transform: translateY(0);
        }

        .logout-link {
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        .logout-link a {
            color: #134376;
            text-decoration: none;
            font-weight: 600;
        }

        .logout-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        /* Mobile Responsive */
        @media (max-width: 480px) {
            .verify-content {
                padding: 30px 20px;
                gap: 20px;
            }

            .verify-header h1 {
                font-size: 20px;
            }

            .device-info {
                padding: 12px;
            }

            .device-detail {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <div class="verify-container">
        <div class="verify-content">
            <!-- Header -->
            <div class="verify-header">
                <h1>🔐 Verify Your PIN</h1>
                <p>Enter your PIN to access your account on this device</p>
            </div>

            <!-- Device Info -->
            <div class="device-info">
                <h3>📱 Device Information</h3>
                <div class="device-detail">
                    <span><strong>Device Type:</strong></span>
                    <span>{{ ucfirst($deviceInfo['device_type']) }}</span>
                </div>
                <div class="device-detail">
                    <span><strong>Browser:</strong></span>
                    <span>{{ $deviceInfo['browser'] }}</span>
                </div>
                <div class="device-detail">
                    <span><strong>Operating System:</strong></span>
                    <span>{{ $deviceInfo['os'] }}</span>
                </div>
                <div class="device-detail">
                    <span><strong>IP Address:</strong></span>
                    <span>{{ $deviceInfo['ip_address'] }}</span>
                </div>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('verify-pin-submit') }}">
                @csrf

                <!-- PIN Input -->
                <div class="form-group">
                    <label class="form-label">Enter Your PIN</label>
                    <input type="password"
                           name="pin"
                           required
                           autofocus
                           class="pin-input"
                           placeholder="Enter your PIN"
                           pattern="\d+"
                           maxlength="6">
                </div>

                <!-- Trust Device Checkbox -->
                <div class="checkbox-group">
                    <input id="trust_device"
                           type="checkbox"
                           name="trust_device"
                           value="1">
                    <label for="trust_device">Trust this device for 30 days</label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="verify-button">
                    VERIFY PIN
                </button>

                <!-- Logout Link -->
                <div class="logout-link">
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Use another account
                    </a>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
