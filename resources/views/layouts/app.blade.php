<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marcom East Java - Calendar</title>
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
        <!-- Sidebar Backdrop for Mobile -->
        <div id="sidebarBackdrop" class="sidebar-backdrop" onclick="closeMobileSidebar()"></div>

        <!-- Sidebar -->
        <aside id="appSidebar" class="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <!-- Icon Placeholder -->
                    <div class="logo-icon"></div>
                    <span class="logo-text">Marcom <span class="logo-accent">East Java</span></span>
                </div>
                <button type="button" class="sidebar-close-btn" onclick="closeMobileSidebar()" aria-label="Close menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('events.analytics') }}" class="nav-item {{ request()->routeIs('events.analytics') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span>
                    Dashboard
                </a>
                @php
                    $isCalendarActive = request()->routeIs('events.index', 'events.regional_calendar');
                @endphp
                <div class="nav-group">
                    <button class="nav-item" onclick="toggleSidebarMenu('calendarMenu', 'calendarArrow')" style="width: 100%; justify-content: space-between; display: flex;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span class="nav-icon">📅</span>
                            Calendars
                        </div>
                        <svg id="calendarArrow" style="transition: transform 0.3s ease; transform: rotate({{ $isCalendarActive ? '180deg' : '0deg' }});" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div id="calendarMenu" style="display: flex; flex-direction: column; padding-left: 36px; gap: 4px; overflow: hidden; transition: all 0.3s ease; max-height: {{ $isCalendarActive ? '200px' : '0' }}; opacity: {{ $isCalendarActive ? '1' : '0' }}; margin-top: {{ $isCalendarActive ? '4px' : '0' }};">
                        <a href="{{ route('events.index') }}" class="nav-item {{ request()->routeIs('events.index') ? 'active' : '' }}" style="padding: 8px 16px; font-size: 0.9rem;">
                            🏢 Branch Calendar
                        </a>
                        <a href="{{ route('events.regional_calendar') }}" class="nav-item {{ request()->routeIs('events.regional_calendar') ? 'active' : '' }}" style="padding: 8px 16px; font-size: 0.9rem;">
                            🌐 Regional Calendar
                        </a>
                    </div>
                </div>
                @php
                    $isListsActive = request()->routeIs('events.branch_list', 'events.regional.index');
                @endphp
                <div class="nav-group">
                    <button class="nav-item" onclick="toggleSidebarMenu('listsMenu', 'listsArrow')" style="width: 100%; justify-content: space-between; display: flex;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span class="nav-icon">📋</span>
                            Event Lists
                        </div>
                        <svg id="listsArrow" style="transition: transform 0.3s ease; transform: rotate({{ $isListsActive ? '180deg' : '0deg' }});" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                    </button>
                    <div id="listsMenu" style="display: flex; flex-direction: column; padding-left: 36px; gap: 4px; overflow: hidden; transition: all 0.3s ease; max-height: {{ $isListsActive ? '200px' : '0' }}; opacity: {{ $isListsActive ? '1' : '0' }}; margin-top: {{ $isListsActive ? '4px' : '0' }};">
                        <a href="{{ route('events.branch_list') }}" class="nav-item {{ request()->routeIs('events.branch_list') ? 'active' : '' }}" style="padding: 8px 16px; font-size: 0.9rem;">
                            🏢 Branch List
                        </a>
                        <a href="{{ route('events.regional.index') }}" class="nav-item {{ request()->routeIs('events.regional.index') ? 'active' : '' }}" style="padding: 8px 16px; font-size: 0.9rem;">
                            🌐 Regional List
                        </a>
                    </div>
                </div>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}" class="d-inline" style="width: 100%;">
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
                <div class="topbar-left">
                    <button type="button" class="mobile-hamburger-btn" id="mobileHamburgerBtn" onclick="toggleMobileSidebar()" aria-label="Open Navigation">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                    <div class="topbar-mobile-brand">
                        <div class="logo-icon" style="width: 36px; height: 26px;"></div>
                        <span class="logo-text" style="font-size: 1.15rem;">Marcom <span class="logo-accent">East Java</span></span>
                    </div>
                    <div class="search-bar">
                        <span class="search-icon">🔍</span>
                        <input type="text" placeholder="Search" class="search-input">
                    </div>
                </div>
                <div class="topbar-right">
                    <button class="notification-btn">🔔</button>
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=E7007F&color=fff" alt="Profile" class="profile-img">
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                @if(session('success'))
                    <div id="toast-notification" style="position: fixed; top: 20px; right: 20px; background: linear-gradient(135deg, #E7007F 0%, #d80072 100%); color: white; padding: 12px 24px; border-radius: 10px; box-shadow: 0 8px 24px rgba(231, 0, 127, 0.35); z-index: 9999; font-weight: 600; transition: opacity 0.5s ease-in-out;">
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
    
    <script>
        function toggleSidebarMenu(menuId, arrowId) {
            const menu = document.getElementById(menuId);
            const arrow = document.getElementById(arrowId);
            
            if (menu.style.maxHeight === '0px' || menu.style.maxHeight === '0' || menu.style.maxHeight === '') {
                menu.style.maxHeight = '200px';
                menu.style.opacity = '1';
                menu.style.marginTop = '4px';
                if (arrow) arrow.style.transform = 'rotate(180deg)';
            } else {
                menu.style.maxHeight = '0px';
                menu.style.opacity = '0';
                menu.style.marginTop = '0px';
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        }

        // Mobile Sidebar Drawer Functions
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

        // Auto-close on viewport resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeMobileSidebar();
            }
        });

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeMobileSidebar();
            }
        });
    </script>
</body>
</html>
