<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credova - Secure Login</title>

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

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px;
        }

        .login-content {
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
        }

        .form-input:focus {
            border-color: #134376;
            box-shadow: 0 0 0 4px rgba(19, 67, 118, 0.1);
            outline: none;
        }

        .signin-button {
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
        }

        .signin-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(19, 67, 118, 0.4);
        }

        .logo-section {
            display: flex;
            justify-content: center;
            width: 100%;
        }
            gap: 10px;
            padding-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.2);
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
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .signup-link:hover {
            background: white;
            box-shadow: 0 4px 12px rgba(19, 67, 118, 0.2);
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

            .form-input {
                padding: 12px 16px;
                font-size: 16px;
            }

            .signin-button {
                padding: 12px 24px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 16px;
                min-height: auto;
            }

            .login-content {
                gap: 16px;
                max-width: 100%;
                width: 100%;
            }

            .logo-section img {
                width: 120px !important;
                height: 120px !important;
            }

            .form-input {
                padding: 12px 14px;
                font-size: 16px;
                border-radius: 10px;
            }

            .signin-button {
                padding: 12px 20px;
                font-size: 13px;
                border-radius: 10px;
            }

            .signup-section {
                gap: 8px;
                flex-direction: column;
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
</head>

<body>
    <div class="login-container">
        <div class="login-content">
            <!-- Logo -->
            <div class="logo-section">
                <img src="{{ asset('credovalogo.png') }}"
                     alt="Credova"
                     style="width: 160px; height: 160px; object-fit: contain;">
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-4">
                @csrf

                <!-- Email Input -->
                <input type="email"
                       name="email"
                       required
                       class="form-input"
                       placeholder="Email Address">

                <!-- Password Input -->
                <input type="password"
                       name="password"
                       required
                       class="form-input"
                       placeholder="Password">

                <!-- Remember Checkbox -->
                <div class="flex items-center gap-2">
                    <input id="remember"
                           type="checkbox"
                           class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary cursor-pointer"
                           style="accent-color: #134376;">
                    <label for="remember" class="text-white text-sm cursor-pointer">Remember this device</label>
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
