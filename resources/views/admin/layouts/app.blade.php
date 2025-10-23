<!DOCTYPE html>
<html lang="id">
<head>
    <title>@yield('title', 'Dashboard - BPKAD')</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/logo.png') }}" />
    
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" />
    <!--end::Fonts-->
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      referrerpolicy="no-referrer" />
    
    <!--begin::Font Awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!--end::Font Awesome-->
    
    <!--begin::Global Stylesheets Bundle-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    
    @stack('styles')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #333;
            overflow-x: hidden;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }

        body.sidebar-collapsed .main-content {
            margin-left: 0;
        }

        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }
        }

        /* Custom Toast Styles - POSISI KANAN ATAS */
        #toast-container {
            position: fixed !important;
            z-index: 999999 !important;
            pointer-events: none;
        }

        #toast-container.toast-top-right,
        #toast-container.toast-bottom-right,
        #toast-container.toast-top-end,
        #toast-container.toast-bottom-end {
            top: 24px !important;
            right: 24px !important;
            left: auto !important;
            bottom: auto !important;
        }

        #toast-container > div {
            opacity: 1 !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
            border-radius: 12px !important;
            padding: 18px 22px !important;
            min-width: 350px !important;
            max-width: 400px !important;
            backdrop-filter: blur(10px);
            pointer-events: auto;
            margin-bottom: 12px;
            position: relative !important;
        }

        #toast-container > .toast-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
            border-left: 4px solid #047857 !important;
        }

        #toast-container > .toast-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
            border-left: 4px solid #b91c1c !important;
        }

        #toast-container > .toast-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
            border-left: 4px solid #1d4ed8 !important;
        }

        #toast-container > .toast-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%) !important;
            border-left: 4px solid #b45309 !important;
        }

        #toast-container > div .toast-title {
            font-weight: 700 !important;
            font-size: 15px !important;
            margin-bottom: 6px !important;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #toast-container > div .toast-message {
            font-weight: 500 !important;
            font-size: 14px !important;
            color: rgba(255, 255, 255, 0.95) !important;
            line-height: 1.5 !important;
        }

        #toast-container > div .toast-close-button {
            color: white !important;
            opacity: 0.8 !important;
            font-weight: 400 !important;
            text-shadow: none !important;
            font-size: 20px !important;
            right: 8px !important;
            top: 8px !important;
            position: absolute !important;
        }

        #toast-container > div .toast-close-button:hover {
            opacity: 1 !important;
        }

        #toast-container .toast-progress {
            background-color: rgba(255, 255, 255, 0.7) !important;
            height: 3px !important;
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        /* Toast Animation */
        @keyframes toastSlideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes toastSlideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        #toast-container > div {
            animation: toastSlideInRight 0.3s ease-out;
        }

        /* Success Icon */
        .toast-icon-success {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            font-weight: bold;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            #toast-container.toast-top-right {
                top: 16px !important;
                right: 16px !important;
                left: auto !important;
            }

            #toast-container > div {
                min-width: 300px !important;
                max-width: calc(100vw - 32px) !important;
            }
        }
    </style>
    
    <script>
        // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) 
        if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
    </script>
</head>

<body>
    <!--begin::Theme mode setup on page load-->
    <script>
        var defaultThemeMode = "light"; 
        var themeMode; 
        if (document.documentElement) { 
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) { 
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); 
            } else { 
                if (localStorage.getItem("data-bs-theme") !== null) { 
                    themeMode = localStorage.getItem("data-bs-theme"); 
                } else { 
                    themeMode = defaultThemeMode; 
                } 
            } 
            if (themeMode === "system") { 
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; 
            } 
            document.documentElement.setAttribute("data-bs-theme", themeMode); 
        }
    </script>
    <!--end::Theme mode setup on page load-->
    
    <div class="dashboard-container">
        <!--begin::Sidebar-->
        @include('admin.layouts.partials.sidebar')
        <!--end::Sidebar-->
        
        <div class="main-content">
            <!--begin::Header-->
            @include('admin.layouts.partials.header')
            <!--end::Header-->
            
            <!--begin::Content-->
            <div class="content" style="padding: 24px; flex: 1; overflow-y: auto;">
                @yield('content')
            </div>
            <!--end::Content-->
            
            <!--begin::Footer-->
            @include('admin.layouts.partials.footer')
            <!--end::Footer-->
        </div>
    </div>
    
    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
        <i class="fas fa-arrow-up"></i>
    </div>
    <!--end::Scrolltop-->
    
    <!--begin::Javascript-->
    <script>var hostUrl = "{{ asset('assets/') }}/";</script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->

    @if(session('login_success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const existingContainers = document.querySelectorAll('#toast-container');
            existingContainers.forEach(container => container.remove());
            
            if (typeof toastr !== 'undefined') {
                toastr.clear();
                
                toastr.options = {
                    closeButton: true,
                    debug: false,
                    newestOnTop: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    preventDuplicates: true,
                    onclick: null,
                    showDuration: 300,
                    hideDuration: 1000,
                    timeOut: 5000,
                    extendedTimeOut: 1000,
                    showEasing: 'swing',
                    hideEasing: 'linear',
                    showMethod: 'slideDown',
                    hideMethod: 'slideUp',
                    rtl: false
                };
                
                setTimeout(function() {
                    toastr.success(
                        '<div style="display: flex; align-items: center; gap: 12px;">' +
                        '<span class="toast-icon-success"><i class="fas fa-check"></i></span>' +
                        '<div>' +
                        '<div style="font-weight: 700; margin-bottom: 4px;">Berhasil Login</div>' +
                        '<div style="font-size: 13px;">{{ session('login_success') }}</div>' +
                        '</div>' +
                        '</div>'
                    );
                }, 100);
            }
        });
    </script>
    @endif

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: 4000,
                    extendedTimeOut: 1000,
                    newestOnTop: true,
                    preventDuplicates: true
                };
                
                setTimeout(function() {
                    toastr.success('{{ session('success') }}', 'Berhasil');
                }, 100);
            }
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: 5000,
                    extendedTimeOut: 1000,
                    newestOnTop: true,
                    preventDuplicates: true
                };
                
                setTimeout(function() {
                    toastr.error('{{ session('error') }}', 'Gagal');
                }, 100);
            }
        });
    </script>
    @endif

    @if(session('warning'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: 4000,
                    extendedTimeOut: 1000,
                    newestOnTop: true,
                    preventDuplicates: true
                };
                
                setTimeout(function() {
                    toastr.warning('{{ session('warning') }}', 'Perhatian');
                }, 100);
            }
        });
    </script>
    @endif

    @if(session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof toastr !== 'undefined') {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    positionClass: 'toast-top-right',
                    timeOut: 4000,
                    extendedTimeOut: 1000,
                    newestOnTop: true,
                    preventDuplicates: true
                };
                
                setTimeout(function() {
                    toastr.info('{{ session('info') }}', 'Informasi');
                }, 100);
            }
        });
    </script>
    @endif
    
    @stack('scripts')
    @include('admin.layouts.partials.modals.profile')
    <!--end::Javascript-->
</body>
</html>
