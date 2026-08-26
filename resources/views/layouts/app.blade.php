<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcom EJ - Calendar</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Link to our custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <!-- SweetAlert2 for beautiful popups -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <!-- Icon Placeholder -->
                    <div class="logo-icon"></div>
                    <span class="logo-text">Marcom EJ</span>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('events.analytics') }}" class="nav-item {{ request()->routeIs('events.analytics') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span>
                    Dashboard
                </a>
                <a href="{{ route('events.index') }}" class="nav-item {{ request()->routeIs('events.index') ? 'active' : '' }}">
                    <span class="nav-icon">📅</span>
                    Calendar
                </a>
                <a href="{{ route('events.regional.index') }}" class="nav-item {{ request()->routeIs('events.regional.index') ? 'active' : '' }}">
                    <span class="nav-icon">🌍</span>
                    Regional
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="#" class="nav-item">
                    <span class="nav-icon">⚙️</span>
                    Settings
                </a>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="nav-item btn-logout">
                        <span class="nav-icon">🚪</span>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="search-bar">
                    <span class="search-icon">🔍</span>
                    <input type="text" placeholder="Search" class="search-input">
                </div>
                <div class="topbar-right">
                    <button class="notification-btn">🔔</button>
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=random" alt="Profile" class="profile-img">
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                @if(session('success'))
                    <div id="toast-notification" style="position: fixed; top: 20px; right: 20px; background-color: #10b981; color: white; padding: 12px 24px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 9999; font-weight: 500; transition: opacity 0.5s ease-in-out;">
                        {{ session('success') }}
                    </div>
                    <script>
                        setTimeout(function() {
                            const toast = document.getElementById('toast-notification');
                            if(toast) {
                                toast.style.opacity = '0';
                                setTimeout(() => toast.remove(), 500);
                            }
                        }, 2000);
                    </script>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>
    
    @yield('scripts')
</body>
</html>
