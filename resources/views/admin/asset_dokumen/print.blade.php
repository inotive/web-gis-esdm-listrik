@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Print Summary Dokumen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        @page { size: A4; margin: 12mm; }
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color:#111827; font-size:12px; }
        h1,h2,h3 { margin:0; }
        .no-print { display: block; margin-bottom: 10px; }
        .btn { display:inline-block; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; background:#fff; color:#111827; text-decoration:none; font-size:12px; }
        .btn + .btn { margin-left:6px; }
        .btn-primary { background:#2563eb; color:#fff; border-color:#2563eb; }
        .btn-success { background:#16a34a; color:#fff; border-color:#16a34a; }
        .header { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:12px; }
        .meta { color:#6b7280; font-size:11px; }
        .filters { margin-top:6px; color:#374151; }
        .stats { display:flex; flex-wrap:wrap; gap:8px; margin:10px 0 16px; }
        .stat { border:1px solid #e5e7eb; border-radius:8px; padding:8px 12px; background:#fafafa; }
        .grid { display:grid; grid-template-columns:1fr; gap:12px; }
        .card { border:1px solid #e5e7eb; border-radius:12px; padding:14px; }
        .card h3 { font-size:14px; margin-bottom:6px; }
        .muted { color:#6b7280; font-size:11px; }
        .row { display:flex; flex-wrap:wrap; gap:10px; }
        .col { flex:1 1 220px; }
        .field { margin-bottom:6px; }
        .label { display:block; font-size:11px; color:#6b7280; }
        .value { font-size:12px; color:#111827; }
        .badge { display:inline-block; padding:2px 8px; border-radius:999px; font-size:10px; font-weight:700; margin-left:6px; }
        .bg-green { background:#d1fae5; color:#065f46; }
        .bg-cyan  { background:#cffafe; color:#155e75; }
        .bg-red   { background:#fee2e2; color:#991b1b; }
        .bg-blue  { background:#dbeafe; color:#1e40af; }
        .sep { border-top:1px dashed #e5e7eb; margin:10px 0; }
        .link { color:#2563eb; text-decoration:none; }
        .footer { text-align:right; color:#6b7280; font-size:11px; margin-top:8px; }
        .page-break { page-break-after: always; }

        @media print {
            .no-print { display:none !important; }
            .card { page-break-inside: avoid; }
            a.link { color: #111 !important; text-decoration: none; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <a href="{{ url()->previous() }}" class="btn">← Kembali</a>
    <button onclick="window.print()" class="btn btn-primary">🖨 Cetak</button>
</div>

<header class="header">
    <div>
        <h1>📄 Ringkasan Dokumen Aset</h1>
        <div class="meta">Dicetak: {{ $printedAt }}</div>
        <div class="filters">
            <strong>Filter:</strong>
            @php
                $labels = [];
                if (!empty($filters['search'])) $labels[] = "Cari: \"{$filters['search']}\"";
                if (!empty($filters['status'])) $labels[] = "Status: {$filters['status']}";
                if (!empty($filters['unit_kerja'])) $labels[] = "Unit Kerja ID: {$filters['unit_kerja']}";
                if (!empty($filters['tgl_from']) || !empty($filters['tgl_to'])) {
                    $labels[] = "Periode: " . ($filters['tgl_from'] ?? '—') . " s/d " . ($filters['tgl_to'] ?? '—');
                }
            @endphp
            {{ count($labels) ? implode(' • ', $labels) : '— Tidak ada filter —' }}
        </div>
    </div>
</header>

<section class="stats">
    <div class="stat"><strong>Total:</strong> {{ $stats['total'] }}</div>
    <div class="stat"><strong>File:</strong> {{ $stats['file'] }}</div>
    <div class="stat"><strong>Link:</strong> {{ $stats['link'] }}</div>
    <div class="stat"><strong>Tanpa Bukti:</strong> {{ $stats['none'] }}</div>
    <div class="stat"><strong>Terkonfirmasi:</strong> {{ $stats['confirmed'] }}</div>
    <div class="stat"><strong>Belum Konfirmasi:</strong> {{ $stats['unconfirmed'] }}</div>
</section>

<main class="grid">
    @forelse($items as $i => $d)
        @php
            $jenis = $d->file_sertif ? 'file' : ($d->link_sertif ? 'link' : 'none');
            $badgeClass = $jenis === 'file' ? 'bg-green' : ($jenis === 'link' ? 'bg-cyan' : 'bg-red');
            $buktiLabel = $jenis === 'file' ? 'File' : ($jenis === 'link' ? 'Link' : 'Tidak Ada');
            $buktiUrl = $d->file_sertif ? Storage::disk('public')->url($d->file_sertif) : ($d->link_sertif ?? '');
        @endphp

        <section class="card">
            <h3>
                {{ $d->asset->nama_asset ?? '-' }}
                <span class="muted">({{ $d->asset->kode_asset ?? '-' }})</span>
                <span class="badge {{ $badgeClass }}">{{ $buktiLabel }}</span>
                @if($d->has_konfir)
                    <span class="badge bg-blue">Terkonfirmasi</span>
                @endif
            </h3>

            <div class="row" style="margin-top:8px;">
                <div class="col">
                    <div class="field"><span class="label">Unit Kerja</span><span class="value">{{ $d->asset->unitKerja->nama_unit ?? '-' }}</span></div>
                    <div class="field"><span class="label">No. Sertifikat</span><span class="value">{{ $d->no_sertif ?? '-' }}</span></div>
                    <div class="field"><span class="label">Tgl. Sertifikat</span><span class="value">{{ $d->tgl_sertif?->format('d M Y') ?? '-' }}</span></div>
                    <div class="field"><span class="label">Nama Sertifikat</span><span class="value">{{ $d->nama_sertifikat ?? '-' }}</span></div>
                </div>
                <div class="col">
                    <div class="field"><span class="label">No. Dokumen</span><span class="value">{{ $d->no_dokumen ?? '-' }}</span></div>
                    <div class="field"><span class="label">Tgl. Dokumen</span><span class="value">{{ optional($d->tanggal_dokumen)->format('d M Y') ?? '-' }}</span></div>
                    <div class="field"><span class="label">Tgl. Perolehan</span><span class="value">{{ optional($d->tanggal_oleh)->format('d M Y') ?? '-' }}</span></div>
                    <div class="field"><span class="label">Tgl. Buku</span><span class="value">{{ optional($d->tanggal_buku)->format('d M Y') ?? '-' }}</span></div>
                </div>
                <div class="col">
                    <div class="field"><span class="label">Status Sertifikat</span><span class="value">{{ $d->sts_sertif ?? '-' }}</span></div>
                    <div class="field"><span class="label">Keterangan</span><span class="value">{{ $d->ket_sertif ?? '-' }}</span></div>
                    <div class="field">
                        <span class="label">Bukti</span>
                        <span class="value">
                            {{ $buktiLabel }}
                            @if($buktiUrl)
                                — <a href="{{ $buktiUrl }}" class="link" target="_blank" rel="noopener">{{ $jenis === 'file' ? 'Lihat File' : 'Buka Link' }}</a>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            @if($i < count($items)-1)
                <div class="sep"></div>
            @endif
            <div class="footer">#{{ $i+1 }}</div>
        </section>
    @empty
        <section class="card">
            <h3>Tidak ada data</h3>
            <p class="muted">Coba ubah filter atau tambahkan dokumen terlebih dahulu.</p>
        </section>
    @endforelse
</main>

</body>
</html>
