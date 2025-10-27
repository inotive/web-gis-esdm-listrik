{{-- resources/views/admin/user/create.blade.php --}}

<!-- Modal Create User -->
<div id="modalCreateUser" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateUserTitle">
  <div class="modal-backdrop" data-close></div>

  <div class="modal-card">
    <div class="modal-head">
      <div class="modal-title" id="modalCreateUserTitle">Tambah Data User</div>
      <button class="btn-ghost" data-close aria-label="Tutup"><i class="ri-close-line"></i></button>
    </div>

    <div class="modal-body">
      <form id="formCreateUser" method="POST" action="{{ route('admin.hak-akses.user.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid-2">
          {{-- Avatar --}}
          <div class="form-row" style="grid-column:1 / -1;">
            <label class="label">Foto Profil (opsional)</label>
            <div class="d-flex align-items-center gap-3">
              <div class="image-input image-input-circle" data-kt-image-input="true">
                <div class="image-input-wrapper w-125px h-125px" style="background-image:url('{{ asset('assets/media/svg/avatars/blank.svg') }}')"></div>

                <label class="btn btn-ghost" data-kt-image-input-action="change" title="Ganti avatar">
                  <i class="ri-upload-2-line"></i>
                  <input type="file" name="image" accept="image/*"/>
                </label>

                <button class="btn btn-ghost" type="button" data-kt-image-input-action="cancel" title="Batalkan">
                  <i class="ri-arrow-go-back-line"></i>
                </button>

                <button class="btn btn-ghost" type="button" data-kt-image-input-action="remove" title="Hapus">
                  <i class="ri-close-line"></i>
                </button>
              </div>
              <div class="muted" style="font-size:.85rem">Format JPG/PNG, rasio 1:1, maks 2MB.</div>
            </div>
          </div>

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
        </div>
      </form>
    </div>

    <div class="modal-actions">
      <button class="btn btn-primary" form="formCreateUser" type="submit">
        <i class="ri-save-3-line"></i> Simpan
      </button>
      <button class="btn btn-ghost" data-close>Batalkan</button>
    </div>
  </div>
</div>

@push('styles')
<style>
  /* ====== Modal styles (ringan) ====== */
  .modal{position:fixed;inset:0;display:none;z-index:60}
  .modal.show{display:flex;align-items:center;justify-content:center}
  .modal-backdrop{position:absolute;inset:0;background:rgba(15,23,42,.45)}
  .modal-card{position:relative;z-index:1;background:#fff;border:1px solid var(--line);border-radius:16px;width:min(96vw,860px);box-shadow:var(--shadow-2);display:flex;flex-direction:column;max-height:90vh}
  .modal-head{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:14px 16px;border-bottom:1px solid var(--line)}
  .modal-title{font-weight:800;font-size:18px}
  .modal-body{padding:16px;overflow:auto}
  .modal-actions{display:flex;align-items:center;justify-content:flex-end;gap:10px;padding:14px 16px;border-top:1px solid var(--line);background:#fff}

  .btn{display:inline-flex;align-items:center;gap:8px;border:none;border-radius:10px;padding:10px 14px;font-weight:600;cursor:pointer}
  .btn-primary{background:var(--accent-2);color:#fff;box-shadow:0 6px 14px rgba(34,197,94,.22)}
  .btn-ghost{border:1px solid var(--line);background:#fff;border-radius:8px;height:36px;padding:0 10px}
  .btn-ghost:hover{background:#F8FAFC}

  .grid-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px}
  @media (max-width:720px){ .grid-2{grid-template-columns:1fr} }

  .form-row{display:flex;flex-direction:column}
  .label{font-weight:600;font-size:.925rem;color:#334155;margin-bottom:6px}

  .input,.select,.textarea{
    width:100%;height:44px;border:1px solid var(--line);border-radius:10px;background:#FCFCFD;padding:0 12px;outline:none;font:inherit;color:var(--text)
  }
  .input:focus,.select:focus,.textarea:focus{border-color:#CBD5E1;box-shadow:0 0 0 3px rgba(16,185,129,.12)}
  .textarea{height:auto;min-height:110px;padding:10px 12px;resize:vertical}

  .input-wrap{position:relative;display:flex;align-items:center}
  .input-wrap .input{padding-right:44px}
  .input-append{position:absolute;right:6px;top:50%;transform:translateY(-50%)}

  .invalid-feedback{color:#dc2626;font-size:.85rem;margin-top:6px}

  /* kecilkan tombol di image-input agar serasi */
  .image-input .btn.btn-ghost{height:32px}
</style>
@endpush
