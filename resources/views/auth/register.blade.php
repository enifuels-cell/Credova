<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credova - Create Account</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#134376',
                        'primary-dark': '#0F2D5F',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: url('{{ asset("background.png") }}') center/cover no-repeat fixed;
            min-height: 100vh;
        }
        input:focus-visible { outline: none; }

        .register-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px;
        }

        .register-content {
            display: flex;
            flex-direction: column;
            gap: 30px;
            max-width: 400px;
        }

        .form-input {
            background: #f3f4f6;
            border: 2px solid rgba(19, 67, 118, 0.3);
            border-radius: 12px;
            padding: 8px 18px;
            font-size: 14px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            width: 100%;
        }

        .form-input:focus {
            border-color: #134376;
            box-shadow: 0 0 0 4px rgba(19, 67, 118, 0.1);
            outline: none;
        }

        .signup-button {
            background: linear-gradient(135deg, #134376 0%, #0F2D5F 100%);
            color: white;
            padding: 10px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(19, 67, 118, 0.3);
            width: 100%;
        }

        .signup-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 67, 118, 0.4);
        }

        .logo-section {
            display: flex;
            justify-content: center;
            width: 100%;
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
            color: white;
        }

        .form-header p {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        .signin-section {
            display: flex;
            gap: 10px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
            justify-content: center;
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
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .signin-link:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(19, 67, 118, 0.2);
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: -12px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-content">
            <!-- Logo -->
            <div class="logo-section">
                <img src="{{ asset('credovalogo.png') }}"
                     alt="Credova"
                     style="width: 160px; height: 160px; object-fit: contain;">
            </div>

            <!-- Header -->
            <div class="form-header">
                <h1>Create Account</h1>
                <p>Join Credova to manage your lending</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-4">
                @csrf

                <!-- Name Input -->
                <div class="form-group">
                    <input type="text"
                           name="name"
                           required
                           class="form-input"
                           placeholder="Full Name"
                           value="{{ old('name') }}">
                    @error('name')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Input -->
                <div class="form-group">
                    <input type="email"
                           name="email"
                           required
                           class="form-input"
                           placeholder="Email Address"
                           value="{{ old('email') }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <input type="password"
                           name="password"
                           required
                           class="form-input"
                           placeholder="Password (min 8 characters)">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password Input -->
                <div class="form-group">
                    <input type="password"
                           name="password_confirmation"
                           required
                           class="form-input"
                           placeholder="Confirm Password">
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
