<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcom EJ - User Dashboard</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Link to our custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <style>
        .mobile-topbar {
            height: 60px;
            background-color: var(--sidebar-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .mobile-container {
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        .btn-logout-mobile {
            background: none;
            border: 1px solid var(--border-color);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            color: var(--text-secondary);
            cursor: pointer;
            font-weight: 500;
        }

        .btn-logout-mobile:hover {
            background-color: #f1f5f9;
        }
    </style>
</head>
<body>
    <header class="mobile-topbar">
        <div class="logo">
            <div class="logo-icon" style="width: 20px; height: 20px;"></div>
            <span class="logo-text" style="font-size: 1.1rem;">Marcom EJ</span>
        </div>
        
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="font-size: 0.85rem; font-weight: 500; color: var(--text-primary);">
                {{ Auth::user()->name }}
            </div>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout-mobile">Logout</button>
            </form>
        </div>
    </header>

    <main class="mobile-container">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
