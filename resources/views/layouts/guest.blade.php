<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcom East Java - Authentication</title>
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
            background-color: #f8fafc;
            background-image: 
                radial-gradient(circle at 15% 20%, rgba(255, 212, 0, 0.2) 0%, transparent 45%),
                radial-gradient(circle at 85% 80%, rgba(231, 0, 127, 0.14) 0%, transparent 45%);
        }
        
        .auth-card {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px -15px rgba(231, 0, 127, 0.1), 0 0 0 1px rgba(231, 0, 127, 0.08);
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
            width: 60px;
            height: 42px;
            background-image: url('/images/logo-marcomej.png');
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
            transition: transform 0.3s ease;
        }

        .auth-logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.5px;
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
            color: #E7007F;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .auth-link:hover {
            color: #cf0071;
            text-decoration: underline;
        }
        
        .form-control {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px 16px;
            border-radius: 10px;
            transition: all 0.2s;
        }
        
        .form-control:focus {
            background-color: white;
            border-color: #E7007F;
            box-shadow: 0 0 0 3px rgba(231, 0, 127, 0.15);
        }

        .btn-primary {
            width: 100%;
            justify-content: center;
            padding: 12px;
            font-size: 1rem;
            border-radius: 10px;
            margin-top: 16px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #E7007F 0%, #d80072 100%);
            box-shadow: 0 4px 14px rgba(231, 0, 127, 0.3);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #cf0071 0%, #bd0065 100%);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(231, 0, 127, 0.4);
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
