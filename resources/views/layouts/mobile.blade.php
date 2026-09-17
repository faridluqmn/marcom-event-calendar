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
            padding: 0 16px;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .mobile-container {
            padding: 20px 16px;
            max-width: 640px;
            margin: 0 auto;
        }

        /* Ensure hamburger is visible on this mobile layout */
        .mobile-topbar .mobile-hamburger-btn {
            display: flex;
        }
    </style>
</head>
<body>
    <!-- Sidebar Backdrop for Mobile -->
    <div id="sidebarBackdrop" class="sidebar-backdrop" onclick="closeMobileSidebar()"></div>

    <!-- Mobile Drawer Sidebar -->
    <aside id="appSidebar" class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon"></div>
                <span class="logo-text">Marcom <span class="logo-accent">EJ</span></span>
            </div>
            <button type="button" class="sidebar-close-btn" onclick="closeMobileSidebar()" aria-label="Close menu">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
        </div>

        <div style="padding: 0 20px 16px; display: flex; align-items: center; gap: 12px; border-bottom: 1px solid var(--border-color); margin-bottom: 14px;">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=E7007F&color=fff" alt="Profile" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover;">
            <div>
                <div style="font-weight: 600; font-size: 0.95rem; color: var(--text-primary);">{{ Auth::user()->name }}</div>
                <div style="font-size: 0.8rem; color: var(--text-secondary); text-transform: capitalize;">{{ Auth::user()->role ?? 'User' }}</div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="{{ route('user.dashboard') }}" class="nav-item active">
                <span class="nav-icon">📝</span>
                Submit Event
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                @csrf
                <button type="submit" class="nav-item btn-logout">
                    <span class="nav-icon">🚪</span>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <header class="mobile-topbar">
        <div style="display: flex; align-items: center; gap: 12px;">
            <button type="button" class="mobile-hamburger-btn" onclick="toggleMobileSidebar()" aria-label="Open Navigation">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
            <div class="logo">
                <div class="logo-icon" style="width: 24px; height: 24px;"></div>
                <span class="logo-text" style="font-size: 1.15rem;">Marcom <span class="logo-accent">EJ</span></span>
            </div>
        </div>
        
        <div style="display: flex; align-items: center; gap: 10px;">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=E7007F&color=fff" alt="Profile" style="width: 34px; height: 34px; border-radius: 50%; object-fit: cover;">
        </div>
    </header>

    <main class="mobile-container">
        @yield('content')
    </main>

    @yield('scripts')

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('appSidebar');
            if (sidebar && sidebar.classList.contains('mobile-open')) {
                closeMobileSidebar();
            } else {
                openMobileSidebar();
            }
        }

        function openMobileSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.add('mobile-open');
            if (backdrop) backdrop.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('appSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar) sidebar.classList.remove('mobile-open');
            if (backdrop) backdrop.classList.remove('active');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileSidebar();
            }
        });
    </script>
</body>
</html>
