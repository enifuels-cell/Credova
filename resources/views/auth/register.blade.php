<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credova - Create Account</title>

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

        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .register-content {
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
            margin-bottom: 10px;
        }

        .logo-section img {
            width: 160px;
            height: 160px;
            object-fit: contain;
        }

        .form-header {
            display: flex;
            flex-direction: column;
            gap: 8px;
            text-align: center;
            color: white;
        }

        .form-header h1 {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .form-header p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.3px;
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

        .signup-button {
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
            margin-top: 8px;
        }

        .signup-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 67, 118, 0.4);
        }

        .signup-button:active {
            transform: translateY(0);
        }

        .signin-section {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            margin-top: 20px;
        }

        .signin-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
        }

        .signin-link {
            color: #134376;
            font-weight: 600;
            text-decoration: none;
            background: rgba(255, 255, 255, 0.95);
            padding: 6px 14px;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .signin-link:hover {
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

        .error-message li {
            margin-left: 20px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .register-container {
                padding: 20px;
            }

            .register-content {
                gap: 20px;
                max-width: 90vw;
            }

            .logo-section img {
                width: 140px;
                height: 140px;
            }

            .form-header h1 {
                font-size: 24px;
            }

            .form-input {
                padding: 12px 14px;
                font-size: 16px;
            }

            .signup-button {
                padding: 12px 24px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .register-container {
                padding: 16px;
            }

            .register-content {
                gap: 16px;
                max-width: 100%;
            }

            .logo-section img {
                width: 120px;
                height: 120px;
            }

            .form-header h1 {
                font-size: 20px;
            }

            .form-input {
                padding: 11px 12px;
                font-size: 16px;
                border-radius: 10px;
            }

            .signup-button {
                padding: 11px 20px;
                font-size: 13px;
                border-radius: 10px;
            }

            .signin-section {
                flex-direction: column;
                gap: 8px;
                align-items: center;
            }

            .signin-text {
                font-size: 13px;
            }

            .signin-link {
                padding: 6px 12px;
                font-size: 12px;
                border-radius: 6px;
            }
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-content">
            <!-- Logo -->
            <div class="logo-section">
                <img src="{{ asset('credovalogo.png') }}" alt="Credova Logo">
            </div>

            <!-- Header -->
            <div class="form-header">
                <h1>Create Account</h1>
                <p>Join Credova to manage your lending</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-message">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name Input -->
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text"
                           id="name"
                           name="name"
                           required
                           autofocus
                           class="form-input"
                           placeholder="Full Name"
                           value="{{ old('name') }}">
                </div>

                <!-- Email Input -->
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email"
                           id="email"
                           name="email"
                           required
                           class="form-input"
                           placeholder="email@example.com"
                           value="{{ old('email') }}">
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password">Password (min 8 characters) *</label>
                    <input type="password"
                           id="password"
                           name="password"
                           required
                           class="form-input"
                           placeholder="Enter a strong password">
                </div>

                <!-- Confirm Password Input -->
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input type="password"
                           id="password_confirmation"
                           name="password_confirmation"
                           required
                           class="form-input"
                           placeholder="Confirm your password">
                </div>

                <!-- Sign Up Button -->
                <button type="submit" class="signup-button">
                    CREATE ACCOUNT
                </button>
            </form>

            <!-- Sign In Section -->
            <div class="signin-section">
                <span class="signin-text">Already have an account?</span>
                <a href="{{ route('login') }}" class="signin-link">SIGN IN</a>
            </div>
        </div>
    </div>
</body>
</html>
