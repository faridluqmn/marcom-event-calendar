<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcom EJ - Authentication</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Link to our custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <style>
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: var(--bg-color);
            background-image: radial-gradient(circle at 50% 50%, rgba(59, 130, 246, 0.05) 0%, transparent 100%);
        }
        
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            border: 1px solid var(--border-color);
            transform: translateY(20px);
            opacity: 0;
            animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 32px;
        }

        .auth-logo-icon {
            width: 32px;
            height: 32px;
            background-color: var(--primary-color);
            border-radius: 8px;
            animation: pulse 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
        }

        .auth-logo-text {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .auth-title {
            text-align: center;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 24px;
            color: var(--text-primary);
        }

        .auth-link {
            display: block;
            text-align: center;
            margin-top: 24px;
            color: var(--primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: var(--primary-color-hover);
            text-decoration: underline;
        }
        
        .form-control {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            background-color: white;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
            padding: 12px;
            font-size: 1rem;
            border-radius: 8px;
            margin-top: 16px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }
        
        .btn-primary:active {
            transform: scale(0.98);
        }

        @keyframes slideUpFade {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        @yield('content')
    </div>
</body>
</html>
