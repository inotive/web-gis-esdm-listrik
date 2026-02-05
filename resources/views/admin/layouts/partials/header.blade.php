<header class="topbar" aria-label="Navigasi utama atas">
    <div class="topbar-left">
        <div class="search" role="search">
            <i class="ri-search-line"></i>
            <input type="text" placeholder="Cari" aria-label="Pencarian" />
            <button class="search-btn" aria-label="Cari"><i class="ri-search-2-line"></i></button>
        </div>
    </div>

    <div class="actions">
        <!-- Notification Dropdown -->
        <div class="notification-dropdown" id="notificationDropdown">
            <button class="btn-icon" id="notificationBtn" title="Notifikasi">
                <i class="ri-notification-3-line"></i>
                <span class="badge" id="notificationCount" style="display: none;">0</span>
            </button>

            <div class="notification-menu" id="notificationMenu">
                <div class="notification-header">
                    <h3>Notifikasi</h3>
                    <a href="#" id="markAllRead" class="mark-read">Tandai semua dibaca</a>
                </div>
                <div class="notification-list" id="notificationList">
                    <!-- List will be populated by JS -->
                    <div class="notification-empty">
                        <p>Tidak ada notifikasi</p>
                    </div>
                </div>
                <div class="notification-footer">
                    <a href="{{ route('admin.notifications.list') }}">Lihat Semua</a>
                </div>
            </div>
        </div>

        @php
            $authUser = Auth::user();
            $profileImage = $authUser?->image
                ? asset('storage/profile/' . $authUser->image)
                : asset('assets/media/svg/avatars/blank.svg');
        @endphp

        {{-- Avatar = Trigger Modal Quick Profile --}}
        <div class="user-avatar" id="userProfileBtn" title="{{ $authUser?->name ?? 'User' }}">
            <i class="ri-user-line"></i>
        </div>
    </div>
</header>

{{-- ===================== MODAL QUICK PROFILE (Menu) ===================== --}}
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
                <a href="#" class="profile-menu-item danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form-modal').submit();">
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

{{-- ===================== MODAL EDIT PROFILE ===================== --}}
<div id="modalEditProfile" class="pm-modal" role="dialog" aria-modal="true" aria-labelledby="pmTitle">
    <div class="pm-backdrop" data-close></div>

    <div class="pm-card">
        <div class="pm-head">
            <div class="pm-title" id="pmTitle">Edit Profil Saya</div>
            <button class="pm-ghost" type="button" data-close aria-label="Tutup"><i class="ri-close-line"></i></button>
        </div>

        <div class="pm-body">
            <form id="formQuickProfile" method="POST"
                action="{{ route('admin.profile.profile-update', auth()->id()) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                {{-- Avatar --}}
                <div class="pm-row" style="grid-column:1 / -1;">
                    <label class="pm-label">Foto Profil</label>
                    <div class="pm-avatar-wrap">
                        <img id="pmPreview"
                            src="{{ auth()->user()?->image ? asset('storage/profile/' . auth()->user()->image) : asset('assets/media/svg/avatars/blank.svg') }}"
                            alt="Avatar" class="pm-avatar">
                        <label for="pmImage" class="pm-ghost"><i class="ri-upload-2-line"></i> Ganti</label>
                        <input id="pmImage" type="file" name="image" accept="image/*" class="pm-file">
                        <div class="pm-hint">JPG/PNG rasio 1:1, maks 2MB.</div>
                    </div>
                </div>

                {{-- Grid 2 kolom --}}
                <div class="pm-grid">
                    <div class="pm-row">
                        <label class="pm-label" for="pmUsername">Username</label>
                        <input id="pmUsername" class="pm-input" type="text" name="username" required
                            value="{{ auth()->user()?->username }}" placeholder="Masukkan username">
                    </div>

                    <div class="pm-row">
                        <label class="pm-label" for="pmEmail">Email</label>
                        <input id="pmEmail" class="pm-input" type="email" name="email" required
                            value="{{ auth()->user()?->email }}" placeholder="nama@email.com">
                    </div>

                    <div class="pm-row">
                        <label class="pm-label" for="pmName">Nama Lengkap</label>
                        <input id="pmName" class="pm-input" type="text" name="name" required
                            value="{{ auth()->user()?->name }}" placeholder="Masukkan nama lengkap">
                    </div>

                    <div class="pm-row">
                        <label class="pm-label" for="pmPassword">Password <span
                                class="pm-soft">(opsional)</span></label>
                        <div class="pm-input-wrap">
                            <input id="pmPassword" class="pm-input" type="password" name="password"
                                placeholder="Min. 6 karakter">
                            <button type="button" class="pm-ghost pm-append" id="pmTogglePwd"
                                aria-label="Tampil/sembunyi">
                                <i class="ri-eye-line" id="pmIconEye"></i>
                            </button>
                        </div>
                        <div class="pm-hint">Kosongkan jika tidak ingin mengubah password.</div>
                    </div>

                    <div class="pm-row">
                        <label class="pm-label">Role</label>
                        <input class="pm-input" type="text"
                            value="{{ auth()->user()?->role_select->name ?? $role->firstWhere('id', auth()->user()?->role_id)?->name }}"
                            readonly>
                    </div>
                </div>
            </form>
        </div>

        <div class="pm-actions">
            <button class="pm-btn pm-primary" form="formQuickProfile" type="submit">
                <i class="ri-save-3-line"></i> Simpan
            </button>


        </div>
    </div>
</div>

{{-- ===================== STYLE KHUSUS MODAL ===================== --}}
<style>
    /* Profile Modal Quick Menu Styles (from welcome.blade.php) */
    /* Profile Modal Quick Menu Styles (Matched with Landing Page) */
    .user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.95);
        color: #0b2a63;
        cursor: pointer;
        display: grid;
        place-items: center;
        font-size: 20px;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .user-avatar:hover {
        background: #fff;
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    /* .user-profile-btn removed */
    /* .user-profile-btn img removed */

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

    /* Edit Profile Modal Styles */
    .pm-modal {
        position: fixed;
        inset: 0;
        display: none;
        z-index: 10000;
        align-items: center;
        justify-content: center
    }

    .pm-modal.show {
        display: flex;
        align-items: center;
        justify-content: center
    }

    .pm-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, .45)
    }

    .pm-card {
        position: relative;
        z-index: 1;
        background: #fff;
        border: 1px solid #E5E7EB;
        border-radius: 16px;
        width: min(96vw, 880px);
        max-height: 92vh;
        box-shadow: 0 18px 44px -18px rgba(2, 6, 23, .45);
        display: flex;
        flex-direction: column;
        margin: 0 auto
    }

    .pm-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        border-bottom: 1px solid #EDF2F7
    }

    .pm-title {
        font-weight: 800;
        font-size: 18px
    }

    .pm-body {
        padding: 16px;
        overflow: auto
    }

    .pm-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: flex-end;
        padding: 14px 16px;
        border-top: 1px solid #EDF2F7;
        background: #fff
    }

    .pm-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px
    }

    @media (max-width:720px) {
        .pm-grid {
            grid-template-columns: 1fr
        }
    }

    .pm-row {
        display: flex;
        flex-direction: column
    }

    .pm-label {
        font-weight: 600;
        font-size: .925rem;
        color: #334155;
        margin-bottom: 6px
    }

    .pm-soft {
        font-weight: 400;
        color: #94a3b8
    }

    .pm-input {
        height: 44px;
        border: 1px solid #E5E7EB;
        border-radius: 10px;
        background: #FCFCFD;
        padding: 0 12px;
        outline: none;
        font: inherit;
        color: #0f172a
    }

    .pm-input:focus {
        border-color: #CBD5E1;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, .12)
    }

    .pm-input-wrap {
        position: relative;
        display: flex;
        align-items: center
    }

    .pm-input-wrap .pm-input {
        padding-right: 44px
    }

    .pm-append {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%)
    }

    .pm-ghost {
        height: 36px;
        padding: 0 10px;
        border: 1px solid #E5E7EB;
        background: #fff;
        border-radius: 8px;
        cursor: pointer
    }

    .pm-ghost:hover {
        background: #F8FAFC
    }

    .pm-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: none;
        border-radius: 10px;
        padding: 10px 14px;
        font-weight: 600;
        cursor: pointer
    }

    .pm-primary {
        background: #22C55E;
        color: #fff;
        box-shadow: 0 6px 14px rgba(34, 197, 94, .22)
    }

    .pm-danger {
        background: #ef4444;
        color: #fff
    }

    .pm-hint {
        font-size: .82rem;
        color: #64748b;
        margin-top: 6px
    }

    .pm-avatar-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap
    }

    .pm-avatar {
        width: 84px;
        height: 84px;
        border-radius: 999px;
        object-fit: cover;
        border: 1px solid #E5E7EB;
        background: #fff
    }

    .pm-file {
        display: none
    }

    @media (max-width: 768px) {
        .profile-modal {
            width: 95%;
            margin: 0 10px;
        }
    }
</style>

{{-- ===================== SCRIPT: Modal Quick Profile & Edit Profile ===================== --}}
<script>
    (function() {
        // Profile Quick Modal Functions
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
        userProfileBtn?.addEventListener('click', openProfileModal);

        // Close modal when clicking close button
        closeProfileModal?.addEventListener('click', closeProfileModalFunc);

        // Close modal when clicking overlay
        profileModalOverlay?.addEventListener('click', function(e) {
            if (e.target === profileModalOverlay) {
                closeProfileModalFunc();
            }
        });

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && profileModalOverlay?.classList.contains('active')) {
                closeProfileModalFunc();
            }
        });

        // Make closeProfileModalFunc globally accessible
        window.closeProfileModalFunc = closeProfileModalFunc;

        let editProfileModalOpenFunction = null;
        let editProfileModalCloseFunction = null;

        // Edit Profile Modal Functions
        function initializeEditProfileModal() {
            const modal = document.getElementById('modalEditProfile');
            if (!modal) return;

            const open = () => {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            };
            const close = () => {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            };

            // Store references to open and close functions
            editProfileModalOpenFunction = open;
            editProfileModalCloseFunction = close;

            // Close by [data-close] buttons
            const closeButtons = modal.querySelectorAll('[data-close]');
            closeButtons.forEach(btn => {
                // Remove any existing listeners to prevent duplicates
                btn.removeEventListener('click', closeModalHandler);

                // Define handler function
                function closeModalHandler(ev) {
                    ev.preventDefault();
                    ev.stopPropagation();
                    close();
                }

                // Add event listener
                btn.addEventListener('click', closeModalHandler);
            });

            // Close by clicking backdrop
            modal.addEventListener('click', function(e) {
                if (e.target.classList.contains('pm-backdrop')) {
                    close();
                }
            });
        }

        // Initialize after DOM is loaded
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeEditProfileModal);
        } else {
            initializeEditProfileModal();
        }

        // Open edit profile modal when menu item clicked
        document.querySelector('[data-bs-target="#modalEditProfile"]')?.addEventListener('click', function(e) {
            e.preventDefault();
            closeProfileModalFunc();
            if (editProfileModalOpenFunction) {
                editProfileModalOpenFunction();
            }
        });

        // Handle Escape key for edit modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const editModal = document.getElementById('modalEditProfile');
                if (editModal && editModal.classList.contains('show') && editProfileModalCloseFunction) {
                    editProfileModalCloseFunction();
                }
            }
        });

        // Preview avatar
        const file = document.getElementById('pmImage');
        const prev = document.getElementById('pmPreview');
        file?.addEventListener('change', () => {
            if (file.files?.[0]) prev.src = URL.createObjectURL(file.files[0]);
        });

        // Toggle password
        const pwd = document.getElementById('pmPassword');
        const btn = document.getElementById('pmTogglePwd');
        const ico = document.getElementById('pmIconEye');
        btn?.addEventListener('click', () => {
            const show = pwd.type === 'password';
            pwd.type = show ? 'text' : 'password';
            ico.classList.toggle('ri-eye-line');
            ico.classList.toggle('ri-eye-off-line');
        });

        // Sanitasi input nama (opsional)
        const nama = document.getElementById('pmName');
        nama?.addEventListener('input', () => {
            const v = nama.value,
                clean = v.replace(/[!@#$%^&*="()_+{}\[\]:;<>,.?~\\|0-9/'-]/g, '');
            if (v !== clean) nama.value = clean;
        });
        const user = document.getElementById('pmUsername');
        user?.addEventListener('input', () => {
            const v = user.value,
                clean = v.replace(/[ !@#$%^&*="()_+{}\[\]:;<>,.?~\\|/'-]/g, '');
            if (v !== clean) user.value = clean;
        });
    })();
</script>

{{-- ===================== STYLE & SCRIPT NOTIFIKASI ===================== --}}
<style>
    /* Notification Styles */
    .notification-dropdown {
        position: relative;
    }

    .badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background-color: #ef4444;
        color: white;
        border-radius: 99px; /* Pill shape */
        font-size: 10px;
        font-weight: bold;
        min-width: 18px;
        height: 18px;
        padding: 0 4px; /* Breathing room */
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
    }

    .notification-menu {
        position: absolute;
        top: 100%;
        right: -80px;
        /* Adjust based on your layout */
        width: 350px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.2s ease;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        max-height: 500px;
        overflow-y: hidden;
    }

    /* Arrow for popup */
    .notification-menu::before {
        content: '';
        position: absolute;
        top: -6px;
        right: 92px;
        /* Adjust to align with icon */
        width: 12px;
        height: 12px;
        background-color: white;
        border-left: 1px solid #e5e7eb;
        border-top: 1px solid #e5e7eb;
        transform: rotate(45deg);
    }

    .notification-dropdown.active .notification-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(15px);
        /* Add some spacing */
    }

    .notification-header {
        padding: 16px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #fff;
        border-radius: 12px 12px 0 0;
    }

    .notification-header h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
    }

    .mark-read {
        font-size: 12px;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
    }

    .notification-list {
        overflow-y: auto;
        max-height: 350px;
    }

    /* Scrollbar styling */
    .notification-list::-webkit-scrollbar {
        width: 6px;
    }

    .notification-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .notification-list::-webkit-scrollbar-thumb {
        background: #d1d5db;
        border-radius: 3px;
    }

    .notification-item {
        padding: 12px 16px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        gap: 12px;
        transition: background-color 0.2s;
        text-decoration: none;
        color: inherit;
        position: relative;
    }

    .notification-item:hover {
        background-color: #f9fafb;
    }

    .notification-item.unread {
        background-color: #eff6ff;
    }

    /* Indicator dot for unread */
    .notification-item.unread::after {
        content: '';
        position: absolute;
        top: 16px;
        right: 16px;
        width: 8px;
        height: 8px;
        background-color: #3b82f6;
        border-radius: 50%;
    }

    .notif-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e0f2fe;
        color: #0284c7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 18px;
    }

    .notif-content {
        flex: 1;
        padding-right: 10px;
    }

    .notif-title {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 2px;
        display: block;
    }

    .notif-message {
        font-size: 12px;
        color: #6b7280;
        display: block;
        line-height: 1.4;
        margin-bottom: 4px;
    }

    .notif-time {
        font-size: 11px;
        color: #9ca3af;
        display: block;
    }

    .notification-empty {
        padding: 40px 20px;
        text-align: center;
        color: #9ca3af;
    }

    .notification-footer {
        padding: 12px;
        text-align: center;
        border-top: 1px solid #f3f4f6;
        background-color: #fff;
        border-radius: 0 0 12px 12px;
    }

    .notification-footer a {
        font-size: 13px;
        font-weight: 600;
        color: #4b5563;
        text-decoration: none;
    }

    /* Mobile Responsive */
    @media (max-width: 480px) {
        .notification-menu {
            position: fixed;
            top: 70px;
            left: 10px;
            right: 10px;
            width: auto;
            right: 0;
            max-width: none;
        }

        .notification-menu::before {
            display: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationMenu = document.getElementById('notificationMenu');
        const notificationCount = document.getElementById('notificationCount');
        const notificationList = document.getElementById('notificationList');
        const markAllReadBtn = document.getElementById('markAllRead');

        let isOpen = false;

        // Toggle dropdown
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            isOpen = !isOpen;

            if (isOpen) {
                notificationDropdown.classList.add('active');
                fetchNotifications(); // Refresh list on open
            } else {
                notificationDropdown.classList.remove('active');
            }
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (isOpen && !notificationDropdown.contains(e.target)) {
                isOpen = false;
                notificationDropdown.classList.remove('active');
            }
        });

        // Stop propagation on menu click
        notificationMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Initial Fetch
        fetchNotifications();

        // Polling every 60 seconds (optional)
        setInterval(fetchNotifications, 60000);

        // Mark All Read
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();

            fetch("{{ route('admin.notifications.mark-all-read') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchNotifications();
                    }
                });
        });

        function fetchNotifications() {
            fetch("{{ route('admin.notifications.index') }}")
                .then(response => response.json())
                .then(data => {
                    updateBadge(data.unread_count);
                    renderList(data.notifications);
                })
                .catch(error => console.error('Error fetching notifications:', error));
        }

        function updateBadge(count) {
            if (count > 0) {
                notificationCount.textContent = count > 9 ? '9+' : count;
                notificationCount.style.display = 'flex';
                notificationBtn.classList.add('has-unread');
            } else {
                notificationCount.style.display = 'none';
                notificationBtn.classList.remove('has-unread');
            }
        }

        function renderList(notifications) {
            if (notifications.length === 0) {
                notificationList.innerHTML = `
                    <div class="notification-empty">
                        <i class="ri-notification-off-line" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                        <p>Tidak ada notifikasi baru</p>
                    </div>
                `;
                return;
            }

            let html = '';
            notifications.forEach(notif => {
                const isUnread = notif.is_read ? '' : 'unread';
                const icon = getIconByType(notif.type);
                // Format relative time (simple version)
                const date = new Date(notif.created_at);
                const timeString = date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short'
                    }) + ' ' +
                    date.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                // Construct URL for marking as read then redirecting
                // We'll handle the click by calling markRead API then navigating

                html += `
                    <a href="${notif.action_url || '#'}" class="notification-item ${isUnread}" data-id="${notif.id}">
                        <div class="notif-icon">
                            <i class="${icon}"></i>
                        </div>
                        <div class="notif-content">
                            <span class="notif-title">${notif.title}</span>
                            <span class="notif-message">${notif.message}</span>
                            <span class="notif-time">${timeString}</span>
                        </div>
                    </a>
                `;
            });

            notificationList.innerHTML = html;

            // Add click listeners to items
            document.querySelectorAll('.notification-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    // If it has a URL, we want to mark read first
                    const id = this.getAttribute('data-id');

                    // Optimistic update
                    this.classList.remove('unread');

                    // Call API to mark read (don't wait for response to navigate)
                    fetch(`{{ url('admin/notifications') }}/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Content-Type': 'application/json'
                        }
                    });
                });
            });
        }

        function getIconByType(type) {
            switch (type) {
                case 'info':
                    return 'ri-information-line';
                case 'success':
                    return 'ri-checkbox-circle-line';
                case 'warning':
                    return 'ri-alert-line';
                case 'error':
                    return 'ri-error-warning-line';
                default:
                    return 'ri-notification-3-line';
            }
        }
    });
</script>
