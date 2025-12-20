<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomyGo - Emergency Landing</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .container {
            text-align: center;
            max-width: 600px;
            padding: 2rem;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .status {
            background: rgba(255, 255, 255, 0.2);
            padding: 1rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            margin: 0.5rem;
            transition: transform 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">H</div>
        <h1>HomyGo</h1>
        <p>Your property rental platform is starting up...</p>
        
        <div class="status">
            <strong>🚀 System Status:</strong> Initializing<br>
            <strong>⏰ Started:</strong> {{ date('Y-m-d H:i:s T') }}<br>
            <strong>🌐 Environment:</strong> {{ app()->environment() }}<br>
            <strong>📍 Server:</strong> Render.com
        </div>
        
        <div style="margin-top: 2rem;">
            <a href="/health" class="btn">Health Check</a>
            <a href="/debug/env" class="btn">Debug Info</a>
        </div>
    </div>
</body>
</html>
