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

    {{-- Avatar = Trigger Modal --}}
    <img
      class="avatar"
      src="{{ auth()->user()?->image ? asset('storage/profile/'.auth()->user()->image) : 'https://i.pravatar.cc/80?img=22' }}"
      alt="Profil {{ auth()->user()?->name ?? 'Pengguna' }}"
      id="avatarTrigger"
      style="cursor:pointer"
    >
  </div>
</header>

{{-- ===================== MODAL QUICK PROFILE ===================== --}}
<div id="modalProfile" class="pm-modal" role="dialog" aria-modal="true" aria-labelledby="pmTitle">
  <div class="pm-backdrop" data-close></div>

  <div class="pm-card">
    <div class="pm-head">
      <div class="pm-title" id="pmTitle">Profil Saya</div>
      <button class="pm-ghost" data-close aria-label="Tutup"><i class="ri-close-line"></i></button>
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

      <form id="logoutForm" action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="pm-btn pm-danger">
          <i class="ri-logout-box-r-line"></i> Keluar
        </button>
      </form>

      <button class="pm-btn pm-ghost" data-close>Batalkan</button>
    </div>
  </div>
</div>

{{-- ===================== STYLE KHUSUS MODAL ===================== --}}
<style>
  .pm-modal{position:fixed;inset:0;display:none;z-index:1050}
  .pm-modal.show{display:flex;align-items:center;justify-content:center}
  .pm-backdrop{position:absolute;inset:0;background:rgba(15,23,42,.45)}
  .pm-card{position:relative;z-index:1;background:#fff;border:1px solid #E5E7EB;border-radius:16px;width:min(96vw,880px);max-height:92vh;box-shadow:0 18px 44px -18px rgba(2,6,23,.45);display:flex;flex-direction:column}
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
</style>

{{-- ===================== SCRIPT: buka/preview/toggle pwd ===================== --}}
<script>
  (function(){
    const modal  = document.getElementById('modalProfile');
    const open   = () => modal?.classList.add('show');
    const close  = () => modal?.classList.remove('show');

    // Open by avatar
    document.getElementById('avatarTrigger')?.addEventListener('click', open);

    // Close by [data-close] or backdrop
    modal?.addEventListener('click', e=>{
      if (e.target.hasAttribute('data-close') || e.target.classList.contains('pm-backdrop')) close();
    });
    document.addEventListener('keydown', e=>{ if(e.key==='Escape') close(); });

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
