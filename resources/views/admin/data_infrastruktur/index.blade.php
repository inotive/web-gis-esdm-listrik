@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Data Infrastruktur')

@push('styles')
    <style>
        /* Tab Navigation */
        .tab-navigation {
            display: flex;
            gap: 4px;
            background: #F1F5F9;
            padding: 6px;
            border-radius: 12px;
            margin-bottom: 20px;
            width: fit-content;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #64748B;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tab-btn:hover {
            color: #334155;
            background: rgba(255, 255, 255, 0.5);
        }

        .tab-btn.active {
            background: white;
            color: #0077B6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .tab-btn i {
            font-size: 16px;
        }

        .tab-btn .badge {
            background: #E0F2FE;
            color: #0077B6;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .tab-btn.active .badge {
            background: #0077B6;
            color: white;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Card Header / Filter */
        .card-header {
            background: white;
            border-bottom: 1px solid #F1F1F4;
            padding: 12px 20px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .w-search {
            width: 250px;
        }

        .w-filter {
            width: 160px;
        }

        .input-group {
            display: flex;
            align-items: center;
            background: #FCFCFC;
            border: 1px solid #DBDFE9;
            border-radius: 6px;
            overflow: hidden;
            height: 32px;
        }

        .input-group-text {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 100%;
            color: #99A1B7;
            background: transparent;
            border: none;
            padding: 0;
        }

        .form-control {
            height: 100%;
            border: none;
            background: transparent;
            padding: 0 10px;
            font-size: 12px;
            color: #78829D;
            outline: none;
            width: 100%;
        }

        .input-group.has-select {
            position: relative;
        }

        .form-select {
            height: 100%;
            border: none;
            background: transparent;
            padding: 0 28px 0 10px;
            font-size: 12px;
            color: #7c7c7c;
            outline: none;
            width: 100%;
            appearance: none;
            cursor: pointer;
        }

        .input-group.has-select::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            background-image: url("data:image/svg+xml,%3Csvg width='14' height='14' viewBox='0 0 14 14' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 5L7 9L11 5' stroke='%237c7c7c' stroke-width='1.5' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            pointer-events: none;
        }

        .btn-ghost {
            height: 32px;
            padding: 0 10px;
            border: 1px solid #F1F1F4;
            background: #fff;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: #4B5675;
            transition: all 0.2s;
        }

        .btn-ghost:hover {
            background: #F8FAFC;
        }

        /* Table */
        .table-shell {
            background: white;
            overflow: hidden;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
        }

        .table-data thead {
            background: #FCFCFC;
        }

        .table-data thead th {
            background: #FCFCFC;
            color: #4B5675;
            font-weight: 500;
            font-size: 13px;
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #F1F1F4;
            white-space: nowrap;
        }

        .table-data tbody td {
            padding: 16px 20px;
            border-bottom: 1px solid #F1F1F4;
            color: #252F4A;
            font-size: 14px;
            vertical-align: middle;
        }

        .table-data tbody tr:last-child td {
            border-bottom: none;
        }

        .table-data tbody tr:hover {
            background: #FCFCFC;
        }

        .col-no {
            width: 48px;
            text-align: center;
            color: #071437;
        }

        .col-aksi {
            width: 120px;
            text-align: center;
            vertical-align: middle;
        }

        /* Action buttons */
        .btn-ico {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: all 0.2s;
            padding: 0;
            margin: 0 4px;
            border-radius: 6px;
        }

        .btn-ico:hover {
            background: #F1F5F9;
            transform: scale(1.05);
        }

        .btn-ico i {
            font-size: 16px;
        }

        .btn-ico.edit i {
            color: #D97706;
        }

        .btn-ico.danger i {
            color: #DC2626;
        }

        /* Footer */
        .table-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 14px 20px;
            border-top: 1px solid #F1F1F4;
            background: #fff;
        }

        .summary {
            color: #4B5675;
            font-size: 13px;
            white-space: nowrap;
        }

        .show-wrap {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #4B5675;
            font-size: 13px;
            white-space: nowrap;
        }

        .show-wrap .form-select {
            width: 70px;
            height: 30px;
            background: #FCFCFC;
            border: 1px solid #DBDFE9;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 11px;
            color: #252F4A;
            cursor: pointer;
            text-align: center;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6B7280;
        }

        .empty-state i {
            font-size: 48px;
            color: #D1D5DB;
            margin-bottom: 12px;
        }

        .empty-state p {
            font-size: 14px;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .w-search,
            .w-filter {
                width: 100%;
            }

            .table-data thead th,
            .table-data tbody td {
                padding: 12px 10px;
            }

            .table-footer {
                flex-direction: column;
                align-items: flex-start;
            }

            .tab-navigation {
                width: 100%;
                overflow-x: auto;
            }

            .tab-btn {
                padding: 8px 14px;
                font-size: 12px;
                white-space: nowrap;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Data Infrastruktur</div>
        </div>
        <div class="page-actions">
            <div class="date-pill">
                <i class="ri-calendar-line"></i>
                <span>{{ now()->translatedFormat('F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="tab-navigation">
        <button class="tab-btn {{ $tab === 'jaringan' ? 'active' : '' }}" data-tab="jaringan">
            <i class="ri-git-branch-line"></i>
            <span>Infrastruktur Jaringan</span>
            <span class="badge">{{ $jaringanItems->total() }}</span>
        </button>
        <button class="tab-btn {{ $tab === 'gardu' ? 'active' : '' }}" data-tab="gardu">
            <i class="ri-building-4-line"></i>
            <span>Data Gardu</span>
            <span class="badge">{{ $garduItems->total() }}</span>
        </button>
        <button class="tab-btn {{ $tab === 'pembangkit' ? 'active' : '' }}" data-tab="pembangkit">
            <i class="ri-flashlight-line"></i>
            <span>Pembangkit Lokal</span>
            <span class="badge">{{ $pembangkitItems->total() }}</span>
        </button>
    </div>

    <!-- ===================== TAB 1: INFRASTRUKTUR JARINGAN ===================== -->
    <div class="tab-content {{ $tab === 'jaringan' ? 'active' : '' }}" id="tab-jaringan">
        <section class="card">
            <div class="card-header">
                <div class="toolbar">
                    <form id="filterFormJaringan" method="GET" action="{{ route('admin.data-infrastruktur.index') }}"
                        style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <input type="hidden" name="tab" value="jaringan">
                        <div class="input-group w-search">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" name="q_jaringan" value="{{ $qJaringan }}" class="form-control"
                                placeholder="Cari Jenis/Jaringan..." autocomplete="off">
                        </div>
                        <div class="input-group w-filter has-select">
                            <select class="form-select" name="jaringan" onchange="this.form.submit()">
                                <option value="">Semua Jaringan</option>
                                @foreach($jaringanOptions as $val => $label)
                                    <option value="{{ $val }}" {{ $jaringanFilter === $val ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn-ghost"
                            onclick="window.location.href='{{ route('admin.data-infrastruktur.index') }}?tab=jaringan'">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                    </form>
                    <a href="{{ route('admin.infrastruktur.index') }}" class="btn btn-primary" style="margin-left:auto;">
                        <i class="ri-add-line"></i> Tambah Infrastruktur
                    </a>
                </div>
            </div>

            <div class="card-body" style="padding:0;">
                <div class="table-responsive table-shell">
                    <table class="table-data">
                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th>Jaringan</th>
                                <th>Jenis</th>
                                <th>Panjang (km)</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jaringanItems as $i => $it)
                                <tr>
                                    <td class="col-no">
                                        {{ ($jaringanItems->currentPage() - 1) * $jaringanItems->perPage() + $i + 1 }}</td>
                                    <td>{{ ucfirst($it->jaringan) }}</td>
                                    <td><strong>{{ $it->jenis }}</strong></td>
                                    <td>{{ number_format($it->panjang_jaringan, 2) }}</td>
                                    <td class="col-aksi">
                                        <a href="{{ route('admin.infrastruktur.index') }}" class="btn-ico edit" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.infrastruktur.destroy', $it) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-ico danger" title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="ri-git-branch-line"></i>
                                            <p>Belum ada data infrastruktur jaringan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="table-footer">
                        <div class="summary">
                            Menampilkan
                            <strong>{{ $jaringanItems->firstItem() ?? 0 }}–{{ $jaringanItems->lastItem() ?? 0 }}</strong>
                            dari <strong>{{ $jaringanItems->total() }}</strong> data
                        </div>
                        {{ $jaringanItems->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ===================== TAB 2: DATA GARDU ===================== -->
    <div class="tab-content {{ $tab === 'gardu' ? 'active' : '' }}" id="tab-gardu">
        <section class="card">
            <div class="card-header">
                <div class="toolbar">
                    <form id="filterFormGardu" method="GET" action="{{ route('admin.data-infrastruktur.index') }}"
                        style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <input type="hidden" name="tab" value="gardu">
                        <div class="input-group w-search">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" name="q_gardu" value="{{ $qGardu }}" class="form-control"
                                placeholder="Cari Nama/Lokasi..." autocomplete="off">
                        </div>
                        <div class="input-group w-filter has-select">
                            <select class="form-select" name="jenis_gardu" onchange="this.form.submit()">
                                <option value="">Semua Jenis</option>
                                @foreach($jenisGarduOptions as $opt)
                                    <option value="{{ $opt }}" {{ $jenisGarduFilter === $opt ? 'selected' : '' }}>{{ $opt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" class="btn-ghost"
                            onclick="window.location.href='{{ route('admin.data-infrastruktur.index') }}?tab=gardu'">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                    </form>
                    <a href="{{ route('admin.gardu.index') }}" class="btn btn-primary" style="margin-left:auto;">
                        <i class="ri-add-line"></i> Tambah Gardu
                    </a>
                </div>
            </div>

            <div class="card-body" style="padding:0;">
                <div class="table-responsive table-shell">
                    <table class="table-data">
                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th>Nama Gardu</th>
                                <th>Jenis</th>
                                <th>Lokasi</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($garduItems as $i => $g)
                                <tr>
                                    <td class="col-no">{{ ($garduItems->currentPage() - 1) * $garduItems->perPage() + $i + 1 }}</td>
                                    <td><strong>{{ $g->nama }}</strong></td>
                                    <td>{{ $g->jenis_gardu_distribusi }}</td>
                                    <td>{{ $g->lokasi_lengkap }}</td>
                                    <td class="col-aksi">
                                        <a href="{{ route('admin.gardu.index') }}" class="btn-ico edit" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.gardu.destroy', $g) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-ico danger" title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <i class="ri-building-4-line"></i>
                                            <p>Belum ada data gardu</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="table-footer">
                        <div class="summary">
                            Menampilkan
                            <strong>{{ $garduItems->firstItem() ?? 0 }}–{{ $garduItems->lastItem() ?? 0 }}</strong> dari
                            <strong>{{ $garduItems->total() }}</strong> data
                        </div>
                        {{ $garduItems->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- ===================== TAB 3: PEMBANGKIT LOKAL ===================== -->
    <div class="tab-content {{ $tab === 'pembangkit' ? 'active' : '' }}" id="tab-pembangkit">
        <section class="card">
            <div class="card-header">
                <div class="toolbar">
                    <form id="filterFormPembangkit" method="GET" action="{{ route('admin.data-infrastruktur.index') }}"
                        style="display:flex; gap:12px; align-items:center; flex-wrap:wrap;">
                        <input type="hidden" name="tab" value="pembangkit">
                        <div class="input-group w-search">
                            <span class="input-group-text"><i class="ri-search-line"></i></span>
                            <input type="text" name="q_pembangkit" value="{{ $qPembangkit }}" class="form-control"
                                placeholder="Cari Lokasi/Kapasitas..." autocomplete="off">
                        </div>
                        <button type="button" class="btn-ghost"
                            onclick="window.location.href='{{ route('admin.data-infrastruktur.index') }}?tab=pembangkit'">
                            <i class="ri-refresh-line"></i> Reset
                        </button>
                    </form>
                    <a href="{{ route('admin.pembangkit.index') }}" class="btn btn-primary" style="margin-left:auto;">
                        <i class="ri-add-line"></i> Tambah Pembangkit
                    </a>
                </div>
            </div>

            <div class="card-body" style="padding:0;">
                <div class="table-responsive table-shell">
                    <table class="table-data">
                        <thead>
                            <tr>
                                <th class="col-no">No</th>
                                <th>Lokasi</th>
                                <th>Kapasitas Gardu</th>
                                <th class="col-aksi">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembangkitItems as $i => $p)
                                <tr>
                                    <td class="col-no">
                                        {{ ($pembangkitItems->currentPage() - 1) * $pembangkitItems->perPage() + $i + 1 }}</td>
                                    <td><strong>{{ $p->lokasi_lengkap }}</strong></td>
                                    <td>{{ $p->kapasitas_gardu }}</td>
                                    <td class="col-aksi">
                                        <a href="{{ route('admin.pembangkit.index') }}" class="btn-ico edit" title="Edit">
                                            <i class="ri-edit-line"></i>
                                        </a>
                                        <form action="{{ route('admin.pembangkit.destroy', $p) }}" method="POST"
                                            style="display:inline;" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-ico danger" title="Hapus">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="empty-state">
                                            <i class="ri-flashlight-line"></i>
                                            <p>Belum ada data pembangkit lokal</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="table-footer">
                        <div class="summary">
                            Menampilkan
                            <strong>{{ $pembangkitItems->firstItem() ?? 0 }}–{{ $pembangkitItems->lastItem() ?? 0 }}</strong>
                            dari <strong>{{ $pembangkitItems->total() }}</strong> data
                        </div>
                        {{ $pembangkitItems->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Tab Navigation
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const tabId = this.getAttribute('data-tab');

                    // Update URL without reload
                    const url = new URL(window.location);
                    url.searchParams.set('tab', tabId);
                    window.history.pushState({}, '', url);

                    // Toggle tabs
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('tab-' + tabId).classList.add('active');
                });
            });

            // Auto submit search on enter
            document.querySelectorAll('input[type="text"]').forEach(input => {
                input.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        this.form.submit();
                    }
                });
            });

            // Success notification
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
      });
    </script>
@endpush