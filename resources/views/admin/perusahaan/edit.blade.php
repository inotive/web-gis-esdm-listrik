@extends('admin.layouts.app')

@section('title', 'Edit Data Perusahaan')

@push('styles')
<style>
  .card{border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow-1);}
  .form-group{margin-bottom:16px;}
  .label{display:block;font-size:14px;color:#374151;margin:6px 0 8px;font-weight:600;}
  .input{width:100%;height:44px;padding:0 12px;border:1px solid #E2E8F0;border-radius:10px;background:#FCFCFD;outline:none;font:inherit;color:#111827;}
  .input textarea{min-height:80px;padding:12px;resize:vertical;}
  .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;border-radius:10px;border:1px solid var(--line);cursor:pointer;background:#fff;}
  .btn-primary{background:var(--accent-2);color:#fff;border:none;}
</style>
@endpush

@section('content')
<div class="page-head">
  <div>
    <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="page-title">Edit Data Perusahaan</div>
  </div>
</div>

<section class="card" style="padding:18px;margin-top:18px;">
  <form method="POST" action="{{ route('admin.perusahaan.update', $perusahaan) }}">
    @csrf @method('PUT')

    <div class="row">
      <div class="col-md-6">
        {{-- Kabupaten --}}
        <div class="form-group">
          <label class="label">Kabupaten/Kota</label>
          <select name="regency_id" id="eRegency" class="input" required>
            @foreach($regencies as $r)
              <option value="{{ $r->id }}" @selected($perusahaan->village->district->regency_id===$r->id)>{{ $r->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Kecamatan --}}
        <div class="form-group">
          <label class="label">Kecamatan</label>
          <select name="district_id" id="eDistrict" class="input" required>
            @foreach($districts as $d)
              <option value="{{ $d->id }}" @selected($perusahaan->village->district_id===$d->id)>{{ $d->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Desa --}}
        <div class="form-group">
          <label class="label">Desa/Kelurahan</label>
          <select name="village_id" id="eVillage" class="input" required>
            @foreach($villages as $v)
              <option value="{{ $v->id }}" @selected($perusahaan->village_id===$v->id)>{{ $v->name }}</option>
            @endforeach
          </select>
        </div>

        {{-- Nama Perusahaan --}}
        <div class="form-group">
          <label class="label">Nama Perusahaan</label>
          <input type="text" name="nama" id="eNama" class="input" value="{{ $perusahaan->nama }}" placeholder="Masukkan nama perusahaan" required>
        </div>

        {{-- Alamat --}}
        <div class="form-group">
          <label class="label">Alamat</label>
          <textarea name="alamat" id="eAlamat" class="input" rows="3" placeholder="Masukkan alamat perusahaan">{{ $perusahaan->alamat }}</textarea>
        </div>
      </div>
    </div>

    <div style="margin-top:16px; display:flex; gap:10px;">
      <a href="{{ route('admin.perusahaan.index') }}" class="btn"><i class="ri-arrow-go-back-line"></i> Kembali</a>
      <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i> Simpan Perubahan</button>
    </div>
  </form>
</section>
@endsection

@push('scripts')
<script>
  (function(){
    // cascading
    const selReg = document.getElementById('eRegency');
    const selDis = document.getElementById('eDistrict');
    const selVil = document.getElementById('eVillage');

    selReg?.addEventListener('change', async () => {
      const rid = selReg.value;
      selDis.innerHTML = ''; addOpt(selDis,'','Pilih Kecamatan');
      selVil.innerHTML = ''; addOpt(selVil,'','Pilih Desa/Kelurahan');
      if (!rid) return;
      const res = await fetch(`{{ route('admin.perusahaan.options.districts') }}?regency_id=${encodeURIComponent(rid)}`);
      const rows = await res.json();
      rows.forEach(r=>addOpt(selDis, r.id, r.name));
    });

    selDis?.addEventListener('change', async () => {
      const did = selDis.value;
      selVil.innerHTML = ''; addOpt(selVil,'','Pilih Desa/Kelurahan');
      if (!did) return;
      const res = await fetch(`{{ route('admin.perusahaan.options.villages') }}?district_id=${encodeURIComponent(did)}`);
      const rows = await res.json();
      rows.forEach(r=>addOpt(selVil, r.id, r.name));
    });

    function addOpt(el,v,t){ const o=document.createElement('option'); o.value=v; o.textContent=t; el.appendChild(o); }
  })();
</script>
@endpush
