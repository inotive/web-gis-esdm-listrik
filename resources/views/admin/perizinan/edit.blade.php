@extends('admin.layouts.app')

@section('title', 'Edit Perizinan')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  /* Select2 Custom Styling */
  .select2-container .select2-selection--single {
    height: 44px;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    display: flex;
    align-items: center;
  }
  
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px;
    top: 1px;
    right: 10px;
  }
  
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-left: 12px;
    color: #111827;
    font-size: 14px;
  }

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

    .form-row {
      grid-template-columns: 1fr;
    }
  }

  .section-title {
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin-top: 24px;
    margin-bottom: 16px;
    border-bottom: 1px solid #E5E7EB;
    padding-bottom: 8px;
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
    {{-- Button Removed --}}
  </div>
</div>

<section class="card">
  <form method="POST" action="{{ route('admin.perizinan.update', $perizinan->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="section-title">Data Pemohon/Pelaku Usaha</div>

    <div class="form-row">
      <div class="form-group">
        <label class="label">Nama Perusahaan <span style="color:#DC2626">*</span></label>
        <select name="nama_perusahaan" class="select2-perusahaan" style="width: 100%;" required>
            <option></option>
            @foreach($perusahaans as $perusahaan)
              <option value="{{ $perusahaan->id }}" {{ (old('nama_perusahaan') ?? $perizinan->perusahaan_id) == $perusahaan->id ? 'selected' : '' }}>
                  {{ $perusahaan->nama }}
              </option>
            @endforeach
        </select>
        @error('nama_perusahaan')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="label">Kontak</label>
        <input type="text" name="kontak" class="input" value="{{ old('kontak', $perizinan->kontak) }}" placeholder="Masukkan kontak">
        @error('kontak')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>
    </div>

    <div class="section-title">Data Perizinan/Non Perizinan</div>

    <div class="form-row">
      <div class="form-group">
        <label class="label">Jenis Perizinan <span style="color:#DC2626">*</span></label>
        <select name="jenis" class="select2-jenis" style="width: 100%;" required>
            <option></option>
            @foreach($jenisPerizinan as $j)
                <option value="{{ $j }}" {{ (old('jenis') ?? $perizinan->jenis) == $j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
        </select>
        @error('jenis')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="label">No. Pengajuan</label>
        <input type="text" name="no_pengajuan" class="input" value="{{ old('no_pengajuan', $perizinan->no_pengajuan) }}" placeholder="Masukkan nomor pengajuan">
        @error('no_pengajuan')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="label">No. Surat Keluar (Rekomtek/Pertek)</label>
        <input type="text" name="no_surat_keluar" class="input" value="{{ old('no_surat_keluar', $perizinan->no_surat_keluar) }}" placeholder="Masukkan nomor surat keluar">
        @error('no_surat_keluar')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="label">No. Surat Izin Terbit</label>
        <input type="text" name="no_surat_izin_terbit" class="input" value="{{ old('no_surat_izin_terbit', $perizinan->no_surat_izin_terbit) }}" placeholder="Masukkan nomor surat izin terbit">
        @error('no_surat_izin_terbit')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>
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

    <div class="section-title">Data Pembangkit Listrik</div>

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



    <div class="form-row">
      <div class="form-group">
        <label class="label">Jumlah Unit</label>
        <input type="number" name="jumlah" class="input" value="{{ old('jumlah', $perizinan->jumlah) }}" placeholder="Masukkan jumlah unit" min="0">
        @error('jumlah')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="label">Kapasitas (per unit)</label>
        <input type="number" name="kapasitas" class="input" value="{{ old('kapasitas', $perizinan->kapasitas) }}" placeholder="Masukkan kapasitas" step="0.01" min="0">
        @error('kapasitas')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>
    </div>

    <div class="form-row">
      <div class="form-group">
        <label class="label">Total Kapasitas (kVA)</label>
        <input type="number" name="total_kapasitas_kva" class="input" value="{{ old('total_kapasitas_kva', $perizinan->total_kapasitas_kva) }}" placeholder="Masukkan total kapasitas" step="0.01" min="0">
        @error('total_kapasitas_kva')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label class="label">Jenis Pembangkit Listrik</label>
        <input type="text" name="jenis_penggunaan" class="input" value="{{ old('jenis_penggunaan', $perizinan->jenis_penggunaan) }}" placeholder="Contoh: PLTS, PLTD">
        @error('jenis_penggunaan')
          <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
        @enderror
      </div>
    </div>

    <div class="form-group">
      <label class="label">Sifat Penggunaan</label>
      <input type="text" name="sifat_penggunaan" class="input" value="{{ old('sifat_penggunaan', $perizinan->sifat_penggunaan) }}" placeholder="Masukkan sifat penggunaan">
      @error('sifat_penggunaan')
        <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
      @enderror
    </div>

    <div class="section-title">Catatan</div>

    <div class="form-group">
      <textarea name="catatan" class="input" rows="3" placeholder="Masukkan catatan">{{ old('catatan', $perizinan->catatan) }}</textarea>
      @error('catatan')
        <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
      @enderror
    </div>

    <div class="form-group">
      <label class="label">Upload File Izin (Opsional)</label>
      <input type="file" name="file_izin" class="input" accept="application/pdf">
      <small style="color: #6B7280; font-size: 12px; margin-top: 4px; display: block;">Format: PDF. Maksimal Ukuran: 10MB.</small>
      @error('file_izin')
        <span style="color: #DC2626; font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>
      @enderror
    </div>

    <div style="display: flex; gap: 12px; justify-content: flex-end; height: 50px;">
      <a href="{{ route('admin.permohonan.index', ['tab' => 'perizinan']) }}" class="btn btn-secondary">
        <i class="ri-arrow-go-back-line"></i>
        Kembali
      </a>
      @can('perizinan.edit')
      <button type="submit" class="btn btn-primary" style="padding: 8px 16px; height: 50px;">
        <i class="ri-save-line"></i>
        Simpan Perubahan
      </button>
      @endcan
    </div>
  </form>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('.select2-perusahaan').select2({
      tags: true,
      placeholder: "Pilih atau Ketik Nama Perusahaan Baru",
      allowClear: true
    });

    $('.select2-jenis').select2({
      tags: true,
      placeholder: "Pilih atau Ketik Jenis Perizinan",
      allowClear: true
    });
  });

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

