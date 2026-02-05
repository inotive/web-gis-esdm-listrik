<style>
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        background-color: white;
        border-bottom: 1px solid #e5e7eb;
        position: sticky;
        top: 0;
        z-index: 99;
    }

    .header-left {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
    }

    .sidebar-toggle {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .sidebar-toggle:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }

    .sidebar-toggle:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(112, 18, 41, 0.2);
    }

    .sidebar-toggle .hamburger {
        position: relative;
        width: 18px;
        height: 14px;
    }

    .sidebar-toggle .hamburger span {
        position: absolute;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: #071324;
        border-radius: 999px;
        transition: transform 0.2s ease, opacity 0.2s ease;
    }

    .sidebar-toggle .hamburger span:nth-child(1) {
        top: 0;
    }

    .sidebar-toggle .hamburger span:nth-child(2) {
        top: 6px;
    }

    .sidebar-toggle .hamburger span:nth-child(3) {
        top: 12px;
    }

    body:not(.sidebar-collapsed) .sidebar-toggle {
        background-color: #701229;
        border-color: #701229;
    }

    body:not(.sidebar-collapsed) .sidebar-toggle .hamburger span {
        background-color: #ffffff;
    }

    .search-bar {
        display: flex;
        align-items: center;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 10px 16px;
        width: 320px;
        transition: all 0.2s;
    }

    .search-bar:focus-within {
        border-color: #701229;
        box-shadow: 0 0 0 3px rgba(112, 18, 41, 0.1);
    }

    .search-bar input {
        border: none;
        background: transparent;
        flex: 1;
        margin-left: 8px;
        font-size: 14px;
        color: #1f2937;
    }

    .search-bar input:focus {
        outline: none;
    }

    .search-bar input::placeholder {
        color: #9ca3af;
    }

    .user-actions {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .icon-button {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        position: relative;
    }

    .icon-button:hover {
        background-color: #f3f4f6;
        border-color: #d1d5db;
    }

    .notification-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 8px;
        height: 8px;
        background-color: #ef4444;
        border-radius: 50%;
        border: 2px solid white;
    }

    .user-profile-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid #10b981;
        transition: all 0.2s;
        position: relative;
    }

    .user-profile-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .user-profile-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Modal Styles */
    .profile-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        display: none;
        animation: fadeIn 0.2s ease-in-out;
    }

    .profile-modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .profile-modal {
        background-color: white;
        border-radius: 16px;
        width: 90%;
        max-width: 400px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        animation: slideUp 0.3s ease-out;
        overflow: hidden;
    }

    .profile-modal-header {
        padding: 24px;
        border-bottom: 1px solid #e5e7eb;
        background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
    }

    .profile-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .profile-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid white;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-details h3 {
        font-size: 18px;
        font-weight: 700;
        color: white;
        margin-bottom: 4px;
    }

    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background-color: rgba(16, 185, 129, 0.2);
        color: #10b981;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .profile-email {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.8);
    }

    .profile-modal-body {
        padding: 0;
    }

    .profile-menu-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 24px;
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid #f3f4f6;
        text-decoration: none;
        color: #1f2937;
    }

    .profile-menu-item:hover {
        background-color: #f9fafb;
    }

    .profile-menu-item:last-child {
        border-bottom: none;
    }

    .profile-menu-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
    }

    .profile-menu-item.danger .profile-menu-icon {
        background-color: #fee2e2;
        color: #ef4444;
    }

    .profile-menu-text {
        flex: 1;
    }

    .profile-menu-text h4 {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 2px;
    }

    .profile-menu-text p {
        font-size: 12px;
        color: #6b7280;
    }

    .profile-menu-item.danger .profile-menu-text h4 {
        color: #ef4444;
    }

    .profile-modal-close {
        position: absolute;
        top: 24px;
        right: 24px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        color: white;
    }

    .profile-modal-close:hover {
        background-color: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .header {
            padding: 12px 16px;
        }

        .header-left {
            gap: 12px;
        }

        .sidebar-toggle {
            width: 38px;
            height: 38px;
        }
        
        .search-bar {
            width: 100%;
            max-width: 200px;
        }

        .profile-modal {
            width: 95%;
            margin: 0 10px;
        }
    }

    @media (max-width: 576px) {
        .search-bar {
            display: none;
        }
    }
</style>

<div class="header">
    <div class="header-left">
        <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka atau tutup menu navigasi" aria-expanded="true">
            <span class="hamburger" aria-hidden="true">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
    </div>
    
    <div class="user-actions">
       
        @php
            $authUser = Auth::user();
            $profileImage = $authUser?->image
                ? asset('storage/profile/' . $authUser->image)
                : asset('assets/media/avatars/300-3.jpg');
        @endphp
        
        <!--begin::User Profile-->
        <div class="user-profile-btn" id="userProfileBtn" title="{{ $authUser?->name ?? 'User' }}">
            <img src="{{ $profileImage }}" alt="user" />
        </div>
        <!--end::User Profile-->
    </div>
</div>

<!--begin::Profile Modal-->
<div class="profile-modal-overlay" id="profileModalOverlay">
    <div class="profile-modal" onclick="event.stopPropagation()">
        <div class="profile-modal-header">
            <button class="profile-modal-close" id="closeProfileModal">
                <i class="fas fa-times"></i>
            </button>
            <div class="profile-info">
                <div class="profile-avatar">
                    <img src="{{ $profileImage }}" alt="user" />
                </div>
                <div class="profile-details">
                   
                    <h3>{{ $authUser?->name ?? 'User' }}</h3>
                    <p class="profile-email">{{ $authUser?->email ?? 'user@example.com' }}</p>
                </div>
            </div>
        </div>
        
        <div class="profile-modal-body">


            

            <form action="{{ route('logout') }}" method="POST" id="logout-form-modal">
                @csrf
                <a href="#" class="profile-menu-item danger" onclick="event.preventDefault(); document.getElementById('logout-form-modal').submit();">
                    <div class="profile-menu-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </div>
                    <div class="profile-menu-text">
                        <h4>Keluar</h4>
                        <p>Keluar dari akun Anda</p>
                    </div>
                    
                </a>
            </form>
        </div>
    </div>
</div>
<!--end::Profile Modal-->

<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    const SIDEBAR_BREAKPOINT = 992;
    let sidebarManuallyCollapsed = false;

    function updateSidebarToggleAria() {
        const isCollapsed = document.body.classList.contains('sidebar-collapsed');
        if (sidebarToggle) {
            sidebarToggle.setAttribute('aria-expanded', (!isCollapsed).toString());
        }
    }

    function syncSidebarWithViewport() {
        if (window.innerWidth < SIDEBAR_BREAKPOINT) {
            document.body.classList.add('sidebar-collapsed');
            sidebarManuallyCollapsed = false;
        } else if (!sidebarManuallyCollapsed) {
            document.body.classList.remove('sidebar-collapsed');
        }

        updateSidebarToggleAria();
    }

    syncSidebarWithViewport();
    window.addEventListener('resize', syncSidebarWithViewport);

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            document.body.classList.toggle('sidebar-collapsed');

            if (window.innerWidth >= SIDEBAR_BREAKPOINT) {
                sidebarManuallyCollapsed = document.body.classList.contains('sidebar-collapsed');
            } else {
                sidebarManuallyCollapsed = false;
            }

            updateSidebarToggleAria();
        });
    }
    // Profile Modal Functions
    const userProfileBtn = document.getElementById('userProfileBtn');
    const profileModalOverlay = document.getElementById('profileModalOverlay');
    const closeProfileModal = document.getElementById('closeProfileModal');

    function openProfileModal() {
        profileModalOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeProfileModalFunc() {
        profileModalOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Open modal when clicking profile button
    userProfileBtn.addEventListener('click', openProfileModal);

    // Close modal when clicking close button
    closeProfileModal.addEventListener('click', closeProfileModalFunc);

    // Close modal when clicking overlay
    profileModalOverlay.addEventListener('click', function(e) {
        if (e.target === profileModalOverlay) {
            closeProfileModalFunc();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && profileModalOverlay.classList.contains('active')) {
            closeProfileModalFunc();
        }
    });
</script>
