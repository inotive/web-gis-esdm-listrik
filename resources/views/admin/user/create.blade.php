{{-- resources/views/admin/user/create.blade.php --}}

<!-- Modal Create User -->
<div id="modalCreateUser" class="custom-modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateUserTitle">
  <div class="custom-modal-backdrop" data-close></div>

  <div class="custom-modal-card">
    <div class="custom-modal-head">
      <div class="custom-modal-title" id="modalCreateUserTitle">Tambah Data User</div>
      <button class="btn-ghost" data-close aria-label="Tutup"><i class="ri-close-line"></i></button>
    </div>

    <div class="custom-modal-body">
      <form id="formCreateUser" method="POST" action="{{ route('admin.hak-akses.user.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid-2">
          {{-- Username --}}
          <div class="form-row">
            <label class="label" for="username">Username</label>
            <input class="input @error('username') is-invalid @enderror" type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Masukkan Username" required>
            @error('username')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          {{-- Email --}}
          <div class="form-row">
            <label class="label" for="email">Email</label>
            <input class="input @error('email') is-invalid @enderror" type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          {{-- Role --}}
          <div class="form-row">
            <label class="label" for="role">Role</label>
            @php
              $defaultRoleName = old('role');
              if (!$defaultRoleName) {
                  $adminRole = $role->first(fn($r) => strtolower($r->name) === 'admin');
                  $defaultRoleName = $adminRole->name ?? ($role->first()->name ?? '');
              }
            @endphp
            <select class="select @error('role') is-invalid @enderror" name="role" id="role" data-control="select2" required>
              @foreach ($role as $item)
                <option value="{{ $item->name }}" {{ $defaultRoleName === $item->name ? 'selected' : '' }}>{{ $item->name }}</option>
              @endforeach
            </select>
            @error('role')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          {{-- Nama Lengkap --}}
          <div class="form-row">
            <label class="label" for="inputNama">Nama Lengkap</label>
            <input class="input @error('name') is-invalid @enderror" type="text" id="inputNama" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap" required>
            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
          </div>

          {{-- Password + toggle --}}
          <div class="form-row">
            <label class="label" for="password">Password</label>
            <div class="input-wrap">
              <input class="input @error('password') is-invalid @enderror" type="password" id="password" name="password" placeholder="Min. 8 karakter" required aria-describedby="toggle-password">
              <button type="button" class="btn-ghost input-append" id="toggle-password" aria-label="Tampil/Sembunyi">
                <i class="fa-solid fa-eye" id="icon-password"></i>
              </button>
            </div>
            @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            <div class="muted" style="font-size:.85rem;margin-top:6px">Gunakan kombinasi huruf & angka agar lebih aman.</div>
          </div>

          {{-- Avatar (moved to right side of Role) --}}
          <!-- <div class="form-row">
            <label class="label">Foto Profil (opsional)</label>
            <input class="input" type="file" name="image" accept="image/*" id="imageInput"/>
            <div class="muted" style="font-size:.85rem;margin-top:6px">Format JPG/PNG, rasio 1:1, maks 2MB.</div>
          </div> -->
        </div>
      </form>
    </div>

    <div class="custom-modal-actions">
      <button class="btn btn-primary" form="formCreateUser" type="submit">
        <i class="ri-save-3-line"></i> Simpan
      </button>
      <button class="btn btn-ghost" data-close>Batalkan</button>
    </div>
  </div>
</div>

@push('styles')
<style>
  /* ====== Custom Modal styles (avoid Bootstrap conflicts) ====== */
  .custom-modal{position:fixed;inset:0;display:none;z-index:1055}
  .custom-modal.show{display:flex;align-items:center;justify-content:center}
  .custom-modal-backdrop{position:absolute;inset:0;background:rgba(15,23,42,.45);z-index:1}
  .custom-modal-card{position:relative;z-index:10;background:#fff;border:1px solid var(--line);border-radius:16px;width:min(96vw,860px);box-shadow:0 25px 50px -12px rgba(0,0,0,.25);display:flex;flex-direction:column;max-height:90vh}
  .custom-modal-head{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:16px 20px;border-bottom:1px solid var(--line)}
  .custom-modal-title{font-weight:700;font-size:18px;color:#1e293b}
  .custom-modal-body{padding:20px;overflow:auto}
  .custom-modal-actions{display:flex;align-items:center;justify-content:flex-end;gap:10px;padding:16px 20px;border-top:1px solid var(--line);background:#f8fafc;border-bottom-left-radius:16px;border-bottom-right-radius:16px}

  .grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
  @media (max-width:720px){ .grid-2{grid-template-columns:1fr} }

  .form-row{display:flex;flex-direction:column}
  .label{font-weight:600;font-size:.925rem;color:#334155;margin-bottom:6px}

  .input,.select,.textarea{
    width:100%;height:44px;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc;padding:0 12px;outline:none;font:inherit;color:#1e293b
  }
  .input:focus,.select:focus,.textarea:focus{border-color:#94a3b8;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .textarea{height:auto;min-height:110px;padding:10px 12px;resize:vertical}

  .input-wrap{position:relative;display:flex;align-items:center}
  .input-wrap .input{padding-right:44px}
  .input-append{position:absolute;right:6px;top:50%;transform:translateY(-50%)}

  .invalid-feedback{color:#dc2626;font-size:.85rem;margin-top:6px}

  /* kecilkan tombol di image-input agar serasi */
  .image-input .btn.btn-ghost{height:32px}
</style>
@endpush

@push('scripts')
<script>
// Password toggle for create form
document.addEventListener('DOMContentLoaded', function() {
  const pwdInput = document.getElementById('password');
  const toggleBtn = document.getElementById('toggle-password');
  const toggleIcon = document.getElementById('icon-password');
  
  if (pwdInput && toggleBtn && toggleIcon) {
    toggleBtn.addEventListener('click', function() {
      const isPassword = pwdInput.type === 'password';
      pwdInput.type = isPassword ? 'text' : 'password';
      toggleIcon.classList.toggle('fa-eye', !isPassword);
      toggleIcon.classList.toggle('fa-eye-slash', isPassword);
    });
  }

  // Form reset after successful submission
  const form = document.getElementById('formCreateUser');
  if (form) {
    form.addEventListener('submit', function(e) {
      // Don't prevent default; let form submit normally
      // Form will be reset after page reload when modal closes
    });
  }
});
</script>
@endpush
