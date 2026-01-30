@extends('admin.layouts.app')

@section('title', 'Edit Perizinan')

@push('styles')
<style>
  .card {
    border: 1px solid var(--line);
    border-radius: 16px;
    box-shadow: var(--shadow-1);
    padding: 24px;
    margin-top: 18px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .label {
    display: block;
    font-size: 14px;
    color: #374151;
    margin-bottom: 8px;
    font-weight: 600;
  }

  .input {
    width: 100%;
    height: 44px;
    padding: 0 12px;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    background: #FCFCFD;
    outline: none;
    font: inherit;
    color: #111827;
  }

  textarea.input {
    min-height: 80px;
    padding: 12px;
    resize: vertical;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 20px;
    border-radius: 10px;
    border: 1px solid var(--line);
    cursor: pointer;
    background: #fff;
    text-decoration: none;
    font-size: 14px;
  }

  .btn-primary {
    background: var(--accent-2);
    color: #fff;
    border: none;
  }

  .btn-secondary {
    background: #6b7280;
    color: #fff;
    border: none;
  }

  .form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  @media (max-width: 768px) {
    .form-row {
      grid-template-columns: 1fr;
    }
  }
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="page-title">Edit Perizinan</div>
  </div>
  <div class="page-actions">
    <a href="{{ route('admin.perizinan.index') }}" class="btn btn-secondary">
      <i class="ri-arrow-left-line"></i>
      Kembali
    </a>
  </div>
</div>

<section class="card">
  <form method="POST" action="{{ route('admin.perizinan.update', $perizinan->id) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label class="label">Nama <span style="color:#DC2626">*</span></label>
      <input type="text" name="nama" class="input" value="{{ old('nama', $perizinan->nama) }}" placeholder="Masukkan nama" required>
      @error('nama')
        <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label class="label">Perusahaan <span style="color:#DC2626">*</span></label>
      <select name="perusahaan_id" class="input" required>
        <option value="" disabled>Pilih Perusahaan</option>
        @foreach($perusahaans as $perusahaan)
          <option value="{{ $perusahaan->id }}" {{ old('perusahaan_id', $perizinan->perusahaan_id) == $perusahaan->id ? 'selected' : '' }}>
            {{ $perusahaan->nama }}
          </option>
        @endforeach
      </select>
      @error('perusahaan_id')
        <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="label">Kontak</label>
        <input type="text" name="kontak" class="input" value="{{ old('kontak', $perizinan->kontak) }}" placeholder="Masukkan kontak">
        @error('kontak')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="label">Jenis <span style="color:#DC2626">*</span></label>
        <input type="text" name="jenis" class="input" value="{{ old('jenis', $perizinan->jenis) }}" placeholder="Contoh: IUJPTL, IUPTLS, SLO, SKTP" required>
        @error('jenis')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>
    </div>



    <div class="form-row">
      <div class="form-group">
        <label class="label">No. Pengajuan</label>
        <input type="text" name="no_pengajuan" class="input" value="{{ old('no_pengajuan', $perizinan->no_pengajuan) }}" placeholder="Masukkan nomor pengajuan">
        @error('no_pengajuan')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="label">No. Surat Keluar (Rekomtek/Pertek)</label>
        <input type="text" name="no_surat_keluar" class="input" value="{{ old('no_surat_keluar', $perizinan->no_surat_keluar) }}" placeholder="Masukkan nomor surat keluar">
        @error('no_surat_keluar')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="label">Tanggal Terbit</label>
        <input type="date" name="tanggal" class="input" value="{{ old('tanggal', $perizinan->tanggal ? $perizinan->tanggal->format('Y-m-d') : '') }}">
        @error('tanggal')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>
      <div class="form-group">
        <label class="label">Tanggal Berakhir</label>
        <input type="date" name="tanggal_akhir" class="input" value="{{ old('tanggal_akhir', $perizinan->tanggal_akhir ? $perizinan->tanggal_akhir->format('Y-m-d') : '') }}">
        @error('tanggal_akhir')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>
    </div>

    <div class="form-group">
      <label class="label">Lokasi</label>
      <textarea name="lokasi" class="input" rows="3" placeholder="Masukkan lokasi">{{ old('lokasi', $perizinan->lokasi) }}</textarea>
      @error('lokasi')
        <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label class="label">Titik Koordinat</label>
      <input type="text" name="titik_koordinat" class="input" value="{{ old('titik_koordinat', $perizinan->titik_koordinat) }}" placeholder="Contoh: -1.234567, 116.123456">
      @error('titik_koordinat')
        <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label class="label">Status Kelistrikan Desa <span style="color:#DC2626">*</span></label>
      <select name="status_kelistrikan" class="input" required>
        <option value="" disabled>Pilih Status Kelistrikan</option>
        <option value="berlistrik_pln" {{ old('status_kelistrikan', $perizinan->status_kelistrikan) == 'berlistrik_pln' ? 'selected' : '' }}>
          Berlistrik PLN
        </option>
        <option value="berlistrik_non_pln" {{ old('status_kelistrikan', $perizinan->status_kelistrikan) == 'berlistrik_non_pln' ? 'selected' : '' }}>
          Berlistrik Non-PLN
        </option>
        <option value="tidak_berlistrik" {{ old('status_kelistrikan', $perizinan->status_kelistrikan) == 'tidak_berlistrik' ? 'selected' : '' }}>
          Tidak Berlistrik
        </option>
      </select>
      @error('status_kelistrikan')
        <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
      @enderror
    </div>



    <div class="form-row">
      <div class="form-group">
        <label class="label">Jumlah Kapasitas</label>
        <input type="number" name="jumlah_kapasitas" class="input" value="{{ old('jumlah_kapasitas', $perizinan->jumlah_kapasitas) }}" placeholder="Masukkan jumlah kapasitas" min="0">
        @error('jumlah_kapasitas')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="label">Total Kapasitas (kVA)</label>
        <input type="number" name="total_kapasitas_kva" class="input" value="{{ old('total_kapasitas_kva', $perizinan->total_kapasitas_kva) }}" placeholder="Masukkan total kapasitas" step="0.01" min="0">
        @error('total_kapasitas_kva')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

    <div class="form-row">
      <div class="form-group">
        <label class="label">Jenis Penggunaan</label>
        <input type="text" name="jenis_penggunaan" class="input" value="{{ old('jenis_penggunaan', $perizinan->jenis_penggunaan) }}" placeholder="Masukkan jenis penggunaan">
        @error('jenis_penggunaan')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="label">Sifat Penggunaan</label>
        <input type="text" name="sifat_penggunaan" class="input" value="{{ old('sifat_penggunaan', $perizinan->sifat_penggunaan) }}" placeholder="Masukkan sifat penggunaan">
        @error('sifat_penggunaan')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>
    </div>

    <div class="form-group">
      <label class="label">Catatan</label>
      <textarea name="catatan" class="input" rows="3" placeholder="Masukkan catatan">{{ old('catatan', $perizinan->catatan) }}</textarea>
      @error('catatan')
        <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
      @enderror
    </div>

    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px;">
      <a href="{{ route('admin.perizinan.index') }}" class="btn btn-secondary">
        Batal
      </a>
      <button type="submit" class="btn btn-primary">
        <i class="ri-save-line"></i>
        Simpan Perubahan
      </button>
    </div>
  </form>
</section>
@endsection

@push('scripts')
<script>
  @if(session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '{{ session('success') }}',
      timer: 3000,
      timerProgressBar: true,
      showConfirmButton: false,
      toast: true,
      position: 'top-end',
    });
  @endif

  @if($errors->any())
    Swal.fire({
      icon: 'error',
      title: 'Terjadi Kesalahan!',
      html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
      confirmButtonText: 'OK'
    });
  @endif
</script>
@endpush

