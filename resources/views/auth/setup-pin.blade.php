<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credova - Set Your PIN</title>

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

        .pin-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .pin-content {
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

        .pin-header {
            text-align: center;
        }

        .pin-header h1 {
            color: #134376;
            font-size: 24px;
            margin-bottom: 8px;
        }

        .pin-header p {
            color: #666;
            font-size: 14px;
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

        .pin-input-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .pin-input {
            width: 60px;
            height: 60px;
            border: 2px solid rgba(19, 67, 118, 0.3);
            border-radius: 12px;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            transition: all 0.3s ease;
            font-family: monospace;
        }

        .pin-input:focus {
            outline: none;
            border-color: #134376;
            box-shadow: 0 0 0 4px rgba(19, 67, 118, 0.1);
        }

        .pin-input::placeholder {
            color: #9ca3af;
        }

        .password-input {
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

        .password-input:focus {
            outline: none;
            border-color: #134376;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(19, 67, 118, 0.1);
        }

        .pin-info {
            background: #eff6ff;
            border-left: 4px solid #134376;
            padding: 12px;
            border-radius: 4px;
            font-size: 13px;
            color: #134376;
            line-height: 1.5;
        }

        .pin-button {
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

        .pin-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 67, 118, 0.4);
        }

        .pin-button:active {
            transform: translateY(0);
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
            .pin-content {
                padding: 30px 20px;
                gap: 20px;
            }

            .pin-header h1 {
                font-size: 20px;
            }

            .pin-input {
                width: 50px;
                height: 50px;
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="pin-container">
        <div class="pin-content">
            <!-- Header -->
            <div class="pin-header">
                <h1>🔐 Set Your PIN</h1>
                <p>Create a 4-6 digit PIN for secure login</p>
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
            <form method="POST" action="{{ route('store-setup-pin') }}">
                @csrf

                <!-- PIN Input -->
                <div class="form-group">
                    <label class="form-label">Enter PIN (4-6 digits)</label>
                    <input type="password"
                           name="pin"
                           required
                           autofocus
                           class="password-input"
                           placeholder="Enter 4-6 digits"
                           pattern="\d{4,6}"
                           maxlength="6">
                </div>

                <!-- PIN Confirmation -->
                <div class="form-group">
                    <label class="form-label">Confirm PIN</label>
                    <input type="password"
                           name="pin_confirmation"
                           required
                           class="password-input"
                           placeholder="Re-enter PIN"
                           pattern="\d{4,6}"
                           maxlength="6">
                </div>

                <!-- Info -->
                <div class="pin-info">
                    ℹ️ <strong>PIN Information:</strong> Your PIN will be used along with trusted devices for quick login, similar to GCash.
                </div>

                <!-- Submit Button -->
                <button type="submit" class="pin-button">
                    SET PIN
                </button>
            </form>
        </div>
    </div>
</body>

</html>
