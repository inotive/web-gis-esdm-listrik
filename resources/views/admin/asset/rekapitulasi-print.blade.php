@extends('admin.layouts.app')

@section('title', 'Cetak Ringkasan Rekapitulasi Aset')
@section('page-title', 'Cetak Ringkasan Rekapitulasi Aset')

@push('styles')
<style>
  /* ====== Layout umum (layar) ====== */
  .print-page {
    max-width: 980px;
    margin: 0 auto;
    background: #fff;
    color: #0f172a;
    padding: 24px 28px 40px;
  }
  .print-head {
    display: flex; align-items: center; justify-content: space-between; gap: 16px;
    border-bottom: 2px solid #e5e7eb; padding-bottom: 12px; margin-bottom: 18px;
  }
  .print-title { font-weight: 800; font-size: 22px; letter-spacing: .3px; }
  .muted { color:#64748b; font-size: 12px; }

  .no-print { display:flex; gap:10px; margin:10px 0 14px; }
  .btn-sm {
    border:1px solid #cbd5e1; background:#fff; padding:8px 12px;
    border-radius:10px; font-weight:700; cursor:pointer;
  }

  /* KPI cards */
  .cards { display:grid; grid-template-columns: repeat(4,1fr); gap:12px; margin: 16px 0 8px; }
  .card {
    border:1px solid rgba(148,163,184,.35); border-radius:14px; padding:14px;
    background:#fff;
  }
  .card .label { font-size:12px; color:#64748b; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
  .card .value { font-size:22px; font-weight:800; margin-top:6px; }

  /* Panel & tabel */
  .grid { display:grid; gap:14px; grid-template-columns:1fr 1fr; margin-top:20px; }
  .panel {
    border:1px solid rgba(148,163,184,.35); border-radius:14px; overflow:hidden; background:#fff;
  }
  .panel .head {
    font-weight:800; padding:12px 14px; background:#f8fafc;
    border-bottom:1px solid rgba(148,163,184,.35);
  }
  .panel .body { padding:0; }

  table { width:100%; border-collapse:collapse; }
  th, td { padding:10px 12px; border-bottom:1px solid #e5e7eb; font-size: 13px; }
  th { text-align:left; font-size:12px; color:#64748b; text-transform:uppercase; letter-spacing:.08em; }
  td.text-right { text-align:right; }

  .badge {
    display:inline-block; padding:.25rem .5rem; border-radius:999px;
    font-size:12px; background:#eef2ff;
  }

  /* ====== PRINT FIX: cegah tabel terpotong di batas halaman ====== */
  @media print {
    /* Gunakan lebar penuh halaman dan hilangkan padding agar maksimal muat */
    .print-page { max-width: none !important; width: 100% !important; padding: 0 !important; }

    /* Stack vertikal (hindari grid 2 kolom saat print) */
    .grid { display: block !important; }
    .grid > .panel { margin-bottom: 12px !important; }

    /* Izinkan konten dipotong antar halaman, jangan di-clip */
    .panel {
      overflow: visible !important;
      border-radius: 0 !important;
      page-break-inside: auto !important;
      break-inside: auto !important;
    }

    /* Tabel ramah print */
    table {
      width: 100% !important;
      border-collapse: collapse !important;
      page-break-inside: auto !important;
    }
    thead { display: table-header-group !important; }  /* ulangi header setiap halaman */
    tfoot { display: table-footer-group !important; }
    tr, th, td { page-break-inside: avoid !important; }

    /* Sembunyikan elemen non-print */
    .sidebar, .header, .footer, .breadcrumb, .no-print { display:none !important; }

    /* Set ukuran & margin halaman */
    @page { size: A4; margin: 14mm 12mm; }
  }
</style>
@endpush

@section('content')
<div class="print-page">
  {{-- Tombol aksi (hanya tampil di layar) --}}
  <div class="no-print">
    <button class="btn-sm" onclick="window.print()">Cetak</button>
    <button class="btn-sm" onclick="window.close()">Tutup</button>
  </div>

  {{-- Header --}}
  <div class="print-head">
    <div>
      <div class="print-title">Ringkasan Rekapitulasi Aset Tanah</div>
      <div class="muted">Dibuat: {{ $printedAt->translatedFormat('l, d F Y H:i') }}</div>
    </div>
    <div class="muted" style="text-align:right">
      {{-- Tampilkan filter aktif --}}
      @if(!empty($filters['search'])) <div><span class="badge">Search</span> {{ $filters['search'] }}</div> @endif
      @if(!empty($filters['unit_kerja'])) <div><span class="badge">Unit</span> {{ $filters['unit_kerja'] }}</div> @endif
      @if(!empty($filters['kabupaten'])) <div><span class="badge">Kabupaten</span> {{ $filters['kabupaten'] }}</div> @endif
      @if(!empty($filters['kecamatan'])) <div><span class="badge">Kecamatan</span> {{ $filters['kecamatan'] }}</div> @endif
      @if(empty($filters['search']) && empty($filters['unit_kerja']) && empty($filters['kabupaten']) && empty($filters['kecamatan']))
        <div>Tidak ada filter aktif</div>
      @endif
    </div>
  </div>

  {{-- KPI Cards --}}
  <div class="cards">
    <div class="card">
      <div class="label">Total Asset</div>
      <div class="value">{{ number_format($summary['total_asset'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="card">
      <div class="label">Total Luas (m²)</div>
      <div class="value">{{ number_format($summary['total_luas'] ?? 0, 2, ',', '.') }}</div>
    </div>
    <div class="card">
      <div class="label">Kabupaten Unik</div>
      <div class="value">{{ number_format($summary['total_kabupaten'] ?? 0, 0, ',', '.') }}</div>
    </div>
    <div class="card">
      <div class="label">Kecamatan Unik</div>
      <div class="value">{{ number_format($summary['total_kecamatan'] ?? 0, 0, ',', '.') }}</div>
    </div>
  </div>

  {{-- Dua panel utama (layar: 2 kolom, print: menumpuk) --}}
  <div class="grid">
    <div class="panel">
      <div class="head">Rekap per Kategori</div>
      <div class="body">
        <table>
          <thead>
            <tr>
              <th style="width:56px">No</th>
              <th>Kategori</th>
              <th style="width:120px" class="text-right">Jumlah</th>
              <th style="width:160px" class="text-right">Total Luas (m²)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($perKategori as $i => $row)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->kategori }}</td>
                <td class="text-right">{{ number_format($row->total, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->luas, 2, ',', '.') }}</td>
              </tr>
            @empty
              <tr><td colspan="4">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="panel">
      <div class="head">Rekap per Kabupaten</div>
      <div class="body">
        <table>
          <thead>
            <tr>
              <th style="width:56px">No</th>
              <th>Kabupaten</th>
              <th style="width:120px" class="text-right">Jumlah</th>
              <th style="width:160px" class="text-right">Total Luas (m²)</th>
            </tr>
          </thead>
          <tbody>
            @forelse($perKabupaten as $i => $row)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->kabupaten }}</td>
                <td class="text-right">{{ number_format($row->total, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($row->luas, 2, ',', '.') }}</td>
              </tr>
            @empty
              <tr><td colspan="4">Tidak ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Panel Unit Kerja --}}
  <div class="panel" style="margin-top:16px">
    <div class="head">Rekap per Unit Kerja</div>
    <div class="body">
      <table>
        <thead>
          <tr>
            <th style="width:56px">No</th>
            <th>Unit Kerja</th>
            <th style="width:120px" class="text-right">Jumlah</th>
            <th style="width:160px" class="text-right">Total Luas (m²)</th>
          </tr>
        </thead>
        <tbody>
          @forelse($perUnitKerja as $i => $row)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $row->unit_kerja }}</td>
              <td class="text-right">{{ number_format($row->total, 0, ',', '.') }}</td>
              <td class="text-right">{{ number_format($row->luas, 2, ',', '.') }}</td>
            </tr>
          @empty
            <tr><td colspan="4">Tidak ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Panel Top 10 Aset Terluas --}}
  <div class="panel" style="margin-top:16px">
    <div class="head">Top 10 Aset Terluas</div>
    <div class="body">
      <table>
        <thead>
          <tr>
            <th style="width:56px">No</th>
            <th>Nama Aset</th>
            <th style="width:120px" class="text-right">Titik</th>
            <th style="width:160px" class="text-right">Total Luas (m²)</th>
          </tr>
        </thead>
        <tbody>
          @forelse($topAset as $i => $row)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $row->nama_asset }}</td>
              <td class="text-right">{{ number_format($row->titik, 0, ',', '.') }}</td>
              <td class="text-right">{{ number_format($row->luas, 2, ',', '.') }}</td>
            </tr>
          @empty
            <tr><td colspan="4">Tidak ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-print (opsional). Hapus kalau tidak ingin otomatis.
setTimeout(() => { window.print(); }, 300);
</script>
@endpush
