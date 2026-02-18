<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'ESDM')</title>

    {{-- ArcGIS CSS --}}
    <link rel="stylesheet" href="https://js.arcgis.com/4.29/esri/themes/light/main.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        :root {
            --nav-h: 64px;
            /* tinggi navbar */
            --brand: #0b2a63;
            --ink: #0f172a;
            --muted: #64748b;
            --card: #f8fafc;
            --bd: #e5e7eb;
            --panel-gap: 12px;
            /* jarak panel dari navbar */
            --panel-w: 300px;
            /* lebar panel detail (kecil) */
            --sidebar-w: 270px;
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%;
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Ubuntu, "Helvetica Neue", Arial
        }

        /* NAVBAR */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 90;
            height: var(--nav-h);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
            background: #0b2a63;
            color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .15)
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 12px
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff1a
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain
        }

        .brand-title {
            font-weight: 800;
            letter-spacing: .3px;
            font-size: 18px;
            white-space: nowrap
        }

        .nav-burger {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, .3);
            background: rgba(255, 255, 255, .08);
            color: #fff;
            cursor: pointer;
            display: grid;
            place-items: center;
            transition: background .2s, border-color .2s, transform .1s;
        }

        .nav-burger:hover {
            background: rgba(255, 255, 255, .15);
            border-color: rgba(255, 255, 255, .45);
            transform: translateY(-1px)
        }

        .nav-right {
            position: relative;
        }

        .nav-right .btn-login {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 38px;
            padding: 0 18px;
            border-radius: 10px;
            text-decoration: none;
            color: #0b2a63;
            background: #fff;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .15);
            transition: all .2s;
        }

        .nav-right .btn-login:hover {
            filter: brightness(.96);
            transform: translateY(-1px)
        }

        /* User Menu */
        .user-menu {
            position: relative;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, .3);
            background: rgba(255, 255, 255, .95);
            color: #0b2a63;
            cursor: pointer;
            display: grid;
            place-items: center;
            font-size: 20px;
            transition: all .2s;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
        }

        .user-avatar:hover {
            background: #fff;
            border-color: rgba(255, 255, 255, .5);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 280px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all .25s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            overflow: hidden;
        }

        .user-dropdown.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .user-info {
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #0b2a63 0%, #1a4d8f 100%);
            color: #fff;
        }

        .user-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .2);
            display: grid;
            place-items: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .user-details {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-email {
            font-size: 12px;
            opacity: .85;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dropdown-divider {
            height: 1px;
            background: #e5e7eb;
            margin: 0;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #374151;
            text-decoration: none;
            transition: background .2s;
            font-weight: 600;
            font-size: 14px;
        }

        .dropdown-item:hover {
            background: #f3f4f6;
        }

        .dropdown-item i {
            font-size: 18px;
            color: #6b7280;
        }

        .nav-user {
            font-weight: 700;
            font-size: 14px
        }

        /* MAP WRAP */
        #mapWrap {
            height: calc(100vh - var(--nav-h));
            width: 100%;
            background: #f3f4f6;
            flex: 1;
        }

        #viewDiv {
            height: 100%;
            width: 100%
        }

        /* LAYOUT dengan sidebar (auth) */
        .landing-shell {
            display: flex;
            min-height: 100vh;
            background: #f5f7fa;
        }

        .landing-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
            transition: margin-left .2s ease;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-w);
            flex: 0 0 var(--sidebar-w);
            background: #fff;
            border-right: 1px solid #E5E7EB;
            overflow: auto;
            display: flex;
            flex-direction: column;
            box-shadow: 0 6px 18px rgba(2, 6, 23, .06);
            z-index: 100;
            transition: transform .2s ease;
            transform: translateX(-100%);
        }

        body.sidebar-open .sidebar {
            transform: translateX(0);
        }

        body.sidebar-open .landing-main {
            margin-left: var(--sidebar-w);
        }

        .sidebar-topbar {
            position: sticky;
            top: 0;
            z-index: 5;
            height: var(--nav-h);
            background: #fff;
            border-bottom: 1px solid #E5E7EB;
            padding: 0 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .logo {
            width: 40px;
            height: 50px;
        }

        .brand-text strong {
            display: block;
            font-size: 14px;
            line-height: 1.1
        }

        .brand-text span {
            display: block;
            font-size: 12px;
            color: #6B7280
        }

        .menu-section {
            padding: 6px 12px 10px;
            display: block;
        }

        .menu-title {
            font-size: 12px;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 8px 10px 6px;
            font-weight: 600;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            margin: 6px 0;
            border-radius: 10px;
            color: #374151;
            text-decoration: none;
            transition: background .18s, transform .12s, color .12s;
        }

        .menu-item:hover {
            background: #F5F7FA;
            transform: translateX(2px);
            color: #0F172A;
        }

        .menu-icon {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: #F6F7F9;
            color: #0F766E;
            flex-shrink: 0;
            font-size: 18px;
        }

        .menu-item .menu-label {
            font-weight: 600;
            font-size: 14px;
        }

        .menu-item.active {
            background: #ECFDF5;
            color: #0F5132;
            font-weight: 700;
            position: relative;
            padding-left: 14px;
        }

        .menu-item.active::before {
            content: "";
            position: absolute;
            left: 8px;
            top: 8px;
            bottom: 8px;
            width: 4px;
            border-radius: 6px;
            background: linear-gradient(180deg, #10B981, #22C55E);
        }

        .menu-item.active .menu-icon {
            background: linear-gradient(180deg, #E8FFF4, #ECFDF5);
            color: #10B981;
        }

        .menu-sep {
            border: none;
            height: 1px;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.03), rgba(0, 0, 0, 0));
            margin: 6px 12px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .4);
            z-index: 95;
        }

        @media (max-width:1024px) {
            body.sidebar-open .sidebar-overlay {
                display: block;
            }

            body.sidebar-open .landing-main {
                margin-left: 0;
            }
        }

        @media (max-width:640px) {
            :root {
                --panel-w: calc(100vw - 28px);
            }

            .detail-panel {
                left: 14px;
                right: 14px;
            }

            .dp-grid {
                grid-template-columns: 1fr
            }
        }

        /* DETAIL PANEL – kecil & tidak menembus header */
        .detail-panel {
            position: fixed;
            right: 14px;
            top: calc(var(--nav-h) + var(--panel-gap));
            /* bawah navbar */
            bottom: 14px;
            width: var(--panel-w);
            max-height: calc(100vh - var(--nav-h) - (var(--panel-gap) + 14px));
            background: #fff;
            border: 1px solid var(--bd);
            border-radius: 14px;
            box-shadow: 0 12px 28px rgba(0, 0, 0, .18);
            padding: 12px;
            display: none;
            z-index: 40;
            overflow: auto
        }

        .detail-panel.show {
            display: block
        }

        .dp-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px
        }

        .dp-title {
            font-weight: 800;
            color: var(--ink);
            font-size: 16px
        }

        .dp-close {
            border: none;
            background: #f1f5f9;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            cursor: pointer
        }

        .dp-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px
        }

        .dp-item {
            background: var(--card);
            border: 1px solid var(--bd);
            border-radius: 10px;
            padding: 8px
        }

        .dp-item.full {
            grid-column: 1 / -1
        }

        .dp-label {
            font-size: 11px;
            color: var(--muted);
            margin-bottom: 3px
        }

        .dp-value {
            font-size: 13px;
            font-weight: 700;
            color: var(--ink);
            word-break: break-word
        }

        .dp-link {
            font-size: 13px;
            font-weight: 700
        }

        /* ArcGIS widgets */
        .esri-ui.bottom-right>.esri-component {
            box-shadow: 0 8px 20px rgba(0, 0, 0, .18);
            border-radius: 12px;
            overflow: hidden
        }

        /* Mobile */
        @media (max-width:640px) {
            :root {
                --panel-w: calc(100vw - 28px);
            }

            .detail-panel {
                left: 14px;
                right: 14px;
            }

            .dp-grid {
                grid-template-columns: 1fr
            }
        }
    </style>

    @stack('styles')
</head>

<body class="{{ auth()->check() ? 'is-auth' : 'is-guest' }}">

    @auth
        <div class="landing-shell">
            @include('admin.layouts.partials.sidebar')
            <div class="landing-main">
                @include('landing.layout.header')
                <main id="mapWrap">
                    @yield('content')
                </main>
            </div>
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
        </div>
    @endauth

    @guest
        @include('landing.layout.header')
        <main id="mapWrap">
            @yield('content')
        </main>
    @endguest

    {{-- ArcGIS JS --}}
    <script src="https://js.arcgis.com/4.29/"></script>

    @auth
        <script>
            (function() {
                const body = document.body;
                const toggle = document.getElementById('sidebarToggle');
                const overlay = document.getElementById('sidebarOverlay');
                const close = () => body.classList.remove('sidebar-open');
                // buka default di desktop
                if (window.innerWidth > 1024) {
                    body.classList.add('sidebar-open');
                }
                toggle?.addEventListener('click', () => body.classList.toggle('sidebar-open'));
                overlay?.addEventListener('click', close);
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape') close();
                });

                // User dropdown menu
                const userMenuToggle = document.getElementById('userMenuToggle');
                const userDropdown = document.getElementById('userDropdown');

                if (userMenuToggle && userDropdown) {
                    userMenuToggle.addEventListener('click', (e) => {
                        e.stopPropagation();
                        userDropdown.classList.toggle('show');
                    });

                    // Close dropdown when clicking outside
                    document.addEventListener('click', (e) => {
                        if (!userMenuToggle.contains(e.target) && !userDropdown.contains(e.target)) {
                            userDropdown.classList.remove('show');
                        }
                    });

                    // Close dropdown on Escape key
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            userDropdown.classList.remove('show');
                        }
                    });
                }
            })
            ();
        </script>
    @endauth

    @stack('scripts')
</body>

</html>
