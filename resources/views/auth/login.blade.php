<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credova - Secure Login</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background: url('{{ asset("background.png") }}') center/cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .login-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
            max-width: 400px;
            width: 100%;
        }

        .logo-section {
            display: flex;
            justify-content: center;
            width: 100%;
            margin-bottom: 20px;
        }

        .logo-section img {
            width: 160px;
            height: 160px;
            object-fit: contain;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 16px;
        }

        .form-input {
            background: #f3f4f6;
            border: 2px solid rgba(19, 67, 118, 0.3);
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-input:focus {
            outline: none;
            border-color: #134376;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(19, 67, 118, 0.1);
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .remember-section {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 16px 0;
        }

        .remember-section input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #134376;
        }

        .remember-section label {
            color: white;
            font-size: 14px;
            cursor: pointer;
            user-select: none;
        }

        .signin-button {
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

        .signin-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 67, 118, 0.4);
        }

        .signin-button:active {
            transform: translateY(0);
        }

        .signup-section {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            margin-top: 20px;
        }

        .signup-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .signup-link {
            color: #134376;
            font-weight: 600;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.95);
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .signup-link:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(19, 67, 118, 0.2);
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
        @media (max-width: 768px) {
            .login-container {
                padding: 20px;
            }

            .login-content {
                gap: 20px;
                max-width: 90vw;
            }

            .logo-section img {
                width: 140px;
                height: 140px;
            }

            .form-input {
                padding: 12px 14px;
                font-size: 16px;
            }

            .signin-button {
                padding: 12px 24px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 16px;
            }

            .login-content {
                gap: 16px;
                max-width: 100%;
            }

            .logo-section img {
                width: 120px;
                height: 120px;
            }

            .form-input {
                padding: 11px 12px;
                font-size: 16px;
                border-radius: 10px;
            }

            .signin-button {
                padding: 11px 20px;
                font-size: 13px;
                border-radius: 10px;
            }

            .signup-section {
                flex-direction: column;
                gap: 8px;
                align-items: center;
            }

            .signup-text {
                font-size: 13px;
            }

            .signup-link {
                padding: 6px 12px;
                font-size: 12px;
                border-radius: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-content">
            <!-- Logo -->
            <div class="logo-section">
                <img src="{{ asset('credovalogo.png') }}" alt="Credova Logo">
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
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Input -->
                <div class="form-group">
                    <input type="email"
                           name="email"
                           required
                           autofocus
                           class="form-input"
                           placeholder="Email Address"
                           value="{{ old('email') }}">
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <input type="password"
                           name="password"
                           required
                           class="form-input"
                           placeholder="Password">
                </div>

                <!-- Remember Checkbox -->
                <div class="remember-section">
                    <input id="remember"
                           type="checkbox"
                           name="remember"
                           class="remember-checkbox">
                    <label for="remember">Remember this device</label>
                </div>

                <!-- Sign In Button -->
                <button type="submit" class="signin-button">
                    SIGN IN
                </button>
            </form>

            <!-- Register Section -->
            <div class="signup-section">
                <span class="signup-text">Don't have an account?</span>
                <a href="{{ route('register') }}" class="signup-link">REGISTER</a>
            </div>
        </div>
    </div>
</body>
</html>
