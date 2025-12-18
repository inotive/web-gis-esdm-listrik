<header class="topbar" aria-label="Navigasi utama atas">
  <div class="topbar-left">
    <div class="search" role="search">
      <i class="ri-search-line"></i>
      <input type="text" placeholder="Cari" aria-label="Pencarian" />
      <button class="search-btn" aria-label="Cari"><i class="ri-search-2-line"></i></button>
    </div>
  </div>

  <div class="actions">
    <button class="btn-icon" title="Pesan Masuk"><i class="ri-message-2-line"></i></button>
    <button class="btn-icon" title="Notifikasi"><i class="ri-notification-3-line"></i></button>
    <button class="btn-icon" title="Bantuan"><i class="ri-question-line"></i></button>

    @php
        $authUser = Auth::user();
        $profileImage = $authUser?->image
            ? asset('storage/profile/' . $authUser->image)
            : 'https://i.pravatar.cc/80?img=22';
    @endphp

    {{-- Avatar = Trigger Modal Quick Profile --}}
    <div class="user-profile-btn" id="userProfileBtn" title="{{ $authUser?->name ?? 'User' }}">
        <img src="{{ $profileImage }}" alt="user" class="avatar" />
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
            <a href="javascript:void(0)" class="profile-menu-item" data-bs-toggle="modal" data-bs-target="#modalEditProfile" onclick="closeProfileModalFunc()">
                <div class="profile-menu-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <div class="profile-menu-text">
                    <h4>Profil Saya</h4>
                    <p>Lihat dan edit profil Anda</p>
                </div>
            </a>

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
            action="{{ route('admin.profile.profile-update', auth()->id()) }}"
            enctype="multipart/form-data">
        @method('PUT')
        @csrf

        {{-- Avatar --}}
        <div class="pm-row" style="grid-column:1 / -1;">
          <label class="pm-label">Foto Profil</label>
          <div class="pm-avatar-wrap">
            <img id="pmPreview"
                 src="{{ auth()->user()?->image ? asset('storage/profile/'.auth()->user()->image) : asset('assets/media/svg/avatars/blank.svg') }}"
                 alt="Avatar"
                 class="pm-avatar">
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
            <label class="pm-label" for="pmPassword">Password <span class="pm-soft">(opsional)</span></label>
            <div class="pm-input-wrap">
              <input id="pmPassword" class="pm-input" type="password" name="password" placeholder="Min. 6 karakter">
              <button type="button" class="pm-ghost pm-append" id="pmTogglePwd" aria-label="Tampil/sembunyi">
                <i class="ri-eye-line" id="pmIconEye"></i>
              </button>
            </div>
            <div class="pm-hint">Kosongkan jika tidak ingin mengubah password.</div>
          </div>

          <div class="pm-row">
            <label class="pm-label">Role</label>
            <input class="pm-input" type="text"
                   value="{{ auth()->user()?->role_select->name ?? $role->firstWhere('id',auth()->user()?->role_id)?->name }}"
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
    .pm-modal{position:fixed;inset:0;display:none;z-index:10000;align-items:center;justify-content:center}
    .pm-modal.show{display:flex;align-items:center;justify-content:center}
  .pm-backdrop{position:absolute;inset:0;background:rgba(15,23,42,.45)}
    .pm-card{position:relative;z-index:1;background:#fff;border:1px solid #E5E7EB;border-radius:16px;width:min(96vw,880px);max-height:92vh;box-shadow:0 18px 44px -18px rgba(2,6,23,.45);display:flex;flex-direction:column;margin:0 auto}
  .pm-head{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;border-bottom:1px solid #EDF2F7}
  .pm-title{font-weight:800;font-size:18px}
  .pm-body{padding:16px;overflow:auto}
  .pm-actions{display:flex;flex-wrap:wrap;gap:10px;justify-content:flex-end;padding:14px 16px;border-top:1px solid #EDF2F7;background:#fff}

  .pm-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
  @media (max-width:720px){ .pm-grid{grid-template-columns:1fr} }
  .pm-row{display:flex;flex-direction:column}
  .pm-label{font-weight:600;font-size:.925rem;color:#334155;margin-bottom:6px}
  .pm-soft{font-weight:400;color:#94a3b8}

  .pm-input{height:44px;border:1px solid #E5E7EB;border-radius:10px;background:#FCFCFD;padding:0 12px;outline:none;font:inherit;color:#0f172a}
  .pm-input:focus{border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .pm-input-wrap{position:relative;display:flex;align-items:center}
  .pm-input-wrap .pm-input{padding-right:44px}
  .pm-append{position:absolute;right:6px;top:50%;transform:translateY(-50%)}

  .pm-ghost{height:36px;padding:0 10px;border:1px solid #E5E7EB;background:#fff;border-radius:8px;cursor:pointer}
  .pm-ghost:hover{background:#F8FAFC}
  .pm-btn{display:inline-flex;align-items:center;gap:8px;border:none;border-radius:10px;padding:10px 14px;font-weight:600;cursor:pointer}
  .pm-primary{background:#22C55E;color:#fff;box-shadow:0 6px 14px rgba(34,197,94,.22)}
  .pm-danger{background:#ef4444;color:#fff}
  .pm-hint{font-size:.82rem;color:#64748b;margin-top:6px}

  .pm-avatar-wrap{display:flex;align-items:center;gap:12px;flex-wrap:wrap}
  .pm-avatar{width:84px;height:84px;border-radius:999px;object-fit:cover;border:1px solid #E5E7EB;background:#fff}
  .pm-file{display:none}

  @media (max-width: 768px) {
      .profile-modal {
          width: 95%;
          margin: 0 10px;
      }
  }
</style>

{{-- ===================== SCRIPT: Modal Quick Profile & Edit Profile ===================== --}}
<script>
  (function(){
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
            modal.addEventListener('click', function(e){
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
        document.querySelector('[data-bs-target="#modalEditProfile"]')?.addEventListener('click', function(e){
            e.preventDefault();
            closeProfileModalFunc();
            if (editProfileModalOpenFunction) {
                editProfileModalOpenFunction();
            }
        });

        // Handle Escape key for edit modal
        document.addEventListener('keydown', function(e) {
            if(e.key==='Escape') {
                const editModal = document.getElementById('modalEditProfile');
                if (editModal && editModal.classList.contains('show') && editProfileModalCloseFunction) {
                    editProfileModalCloseFunction();
                }
            }
        });

    // Preview avatar
    const file = document.getElementById('pmImage');
    const prev = document.getElementById('pmPreview');
    file?.addEventListener('change', ()=>{ if(file.files?.[0]) prev.src = URL.createObjectURL(file.files[0]); });

    // Toggle password
    const pwd = document.getElementById('pmPassword');
    const btn = document.getElementById('pmTogglePwd');
    const ico = document.getElementById('pmIconEye');
    btn?.addEventListener('click', ()=>{
      const show = pwd.type === 'password';
      pwd.type = show ? 'text' : 'password';
      ico.classList.toggle('ri-eye-line');
      ico.classList.toggle('ri-eye-off-line');
    });

    // Sanitasi input nama (opsional)
    const nama = document.getElementById('pmName');
    nama?.addEventListener('input', ()=>{
      const v = nama.value, clean = v.replace(/[!@#$%^&*="()_+{}\[\]:;<>,.?~\\|0-9/'-]/g,'');
      if(v !== clean) nama.value = clean;
    });
    const user = document.getElementById('pmUsername');
    user?.addEventListener('input', ()=>{
      const v = user.value, clean = v.replace(/[ !@#$%^&*="()_+{}\[\]:;<>,.?~\\|/'-]/g,'');
      if(v !== clean) user.value = clean;
    });
  })();
</script>
