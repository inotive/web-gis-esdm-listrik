{{-- resources/views/admin/user/component/modal.blade.php --}}

<!-- Modal Edit User -->
<div id="modalEditUser_{{ $value->id }}" class="custom-modal" role="dialog" aria-modal="true" aria-labelledby="modalEditUserTitle_{{ $value->id }}">
  <div class="custom-modal-backdrop" data-close></div>

  <div class="custom-modal-card">
    <div class="custom-modal-head">
      <div class="custom-modal-title" id="modalEditUserTitle_{{ $value->id }}">Ubah Data User</div>
      <button class="btn-ghost" data-close aria-label="Tutup"><i class="ri-close-line"></i></button>
    </div>

    <div class="custom-modal-body">
      <form id="formEditUser_{{ $value->id }}" method="POST" action="{{ route('admin.hak-akses.user.update', $value->id) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="grid-2">
          {{-- Username --}}
          <div class="form-row">
            <label class="label" for="username_{{ $value->id }}">Username</label>
            <input class="input" type="text" id="username_{{ $value->id }}" name="username" value="{{ $value->username }}" placeholder="Masukkan Username" required>
          </div>

          {{-- Email --}}
          <div class="form-row">
            <label class="label" for="email_{{ $value->id }}">Email</label>
            <input class="input" type="email" id="email_{{ $value->id }}" name="email" value="{{ $value->email }}" placeholder="nama@email.com" required>
          </div>

          {{-- Role --}}
          <div class="form-row">
            <label class="label" for="role_{{ $value->id }}">Role</label>
            @php
              $currentRole = $value->getRoleNames()->first() ?? '';
            @endphp
            <select class="select" name="role" id="role_{{ $value->id }}" required>
              @foreach ($role as $item)
                <option value="{{ $item->name }}" {{ $currentRole === $item->name ? 'selected' : '' }}>{{ $item->name }}</option>
              @endforeach
            </select>
          </div>

          {{-- Nama Lengkap --}}
          <div class="form-row">
            <label class="label" for="inputNama_{{ $value->id }}">Nama Lengkap</label>
            <input class="input" type="text" id="inputNama_{{ $value->id }}" name="name" value="{{ $value->name }}" placeholder="Masukkan Nama Lengkap" required>
          </div>

          {{-- Password + toggle --}}
          <div class="form-row">
            <label class="label" for="password_{{ $value->id }}">Password</label>
            <div class="input-wrap">
              <input class="input" type="password" id="password_{{ $value->id }}" name="password" placeholder="Kosongkan jika tidak diubah" aria-describedby="toggle-password-{{ $value->id }}">
              <button type="button" class="btn-ghost input-append" id="toggle-password-{{ $value->id }}" aria-label="Tampil/Sembunyi">
                <i class="fa-solid fa-eye" id="icon-password-{{ $value->id }}"></i>
              </button>
            </div>
            <div class="muted" style="font-size:.85rem;margin-top:6px">Kosongkan jika tidak ingin mengubah password.</div>
          </div>

          {{-- Avatar --}}
          <!-- <div class="form-row">
            <label class="label">Foto Profil (opsional)</label>
            @if($value->image)
              <div class="mb-2">
                <img src="{{ asset('storage/profile/' . $value->image) }}" alt="Current avatar" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0;">
              </div>
            @endif
            <input class="input" type="file" name="image" accept="image/*" id="imageInput_{{ $value->id }}"/>
            <div class="muted" style="font-size:.85rem;margin-top:6px">Format JPG/PNG, rasio 1:1, maks 2MB.</div>
          </div> -->
        </div>
      </form>
    </div>

    <div class="custom-modal-actions">
      <button class="btn btn-primary" form="formEditUser_{{ $value->id }}" type="submit">
        <i class="ri-save-3-line"></i> Simpan Perubahan
      </button>
      <button class="btn btn-ghost" data-close>Batalkan</button>
    </div>
  </div>
</div>

{{-- Toggle script per modal instance --}}
@push('scripts')
<script>
(function() {
  const modalId = '{{ $value->id }}';
  const pwd = document.getElementById('password_' + modalId);
  const btn = document.getElementById('toggle-password-' + modalId);
  const ico = document.getElementById('icon-password-' + modalId);
  
  if (pwd && btn && ico) {
    btn.addEventListener('click', function() {
      const isPassword = pwd.type === 'password';
      pwd.type = isPassword ? 'text' : 'password';
      ico.classList.toggle('fa-eye', !isPassword);
      ico.classList.toggle('fa-eye-slash', isPassword);
    });
  }
})();
</script>
@endpush
{{-- end modal --}}
