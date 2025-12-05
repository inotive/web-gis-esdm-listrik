@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Variabel Skoring & Bobot')

@push('styles')
    <style>
        .toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px 10px
        }

        .toolbar .w-search {
            width: clamp(230px, 38vw, 340px)
        }

        .toolbar .w-filter {
            width: clamp(180px, 26vw, 230px)
        }

        .input-group {
            display: flex;
            align-items: center;
            background: #FCFCFD;
            border: 1px solid var(--line);
            border-radius: 10px;
            overflow: hidden;
            height: 36px
        }

        .input-group:focus-within {
            border-color: #CBD5E1;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .12)
        }

        .input-group-text {
            display: grid;
            place-items: center;
            width: 36px;
            height: 100%;
            color: #94A3B8;
            background: #F8FAFC;
            border-right: 1px solid var(--line)
        }

        .form-control,
        .form-select {
            height: 36px;
            border: none;
            background: transparent;
            padding: 0 10px;
            font: inherit;
            color: var(--text);
            outline: none;
            width: 100%
        }

        .btn-ghost {
            height: 32px;
            padding: 0 10px;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 8px;
            cursor: pointer
        }

        .btn-ghost:hover {
            background: #F8FAFC
        }

        .table-shell {
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-1)
        }

        table.data {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0
        }

        table.data thead th {
            background: #FCFCFD;
            color: #64748B;
            font-weight: 700;
            padding: 12px 18px;
            text-align: left;
            border-bottom: 1px solid var(--line);
            white-space: nowrap
        }

        table.data tbody td {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            color: #252F4A;
            vertical-align: middle
        }

        table.data tbody tr:hover {
            background: #FAFAFA
        }

        .col-no {
            width: 70px;
            text-align: center
        }

        .col-bbt {
            width: 140px
        }

        .col-aksi {
            width: 130px;
            text-align: center
        }

        .btn-ico {
            --size: 32px;
            width: var(--size);
            height: var(--size);
            display: inline-grid;
            place-items: center;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 8px;
            cursor: pointer
        }

        .btn-ico:hover {
            background: #F8FAFC
        }

        .btn-ico.danger {
            border-color: #FEE2E2;
            color: #DC2626
        }

        .btn-ico.danger:hover {
            background: #FFF5F5
        }

        .table-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 14px 18px;
            border-top: 1px solid var(--line);
            background: #fff;
            border-bottom-left-radius: 16px;
            border-bottom-right-radius: 16px
        }

        .summary {
            color: var(--text-dim)
        }

        .show-wrap {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-dim)
        }

        .show-wrap .form-select {
            width: 92px
        }

        .pagination {
            display: flex;
            gap: 6px;
            list-style: none;
            padding: 0;
            margin: 0
        }

        .page-link {
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--line);
            background: #fff;
            border-radius: 8px;
            text-decoration: none;
            color: var(--text)
        }

        .page-link:hover {
            background: #F8FAFC
        }

        .page-item.active .page-link {
            background: var(--active-soft);
            color: #0F5132;
            border-color: #B7F7CF;
            font-weight: 700
        }

        .page-item.disabled .page-link {
            opacity: .5;
            pointer-events: none
        }

        /* Progress stack */
        .stack-wrap {
            margin: 14px 0 10px;
            overflow: visible
        }

        .stack-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            color: #0F172A
        }

        .stack-bar {
            display: flex;
            overflow: visible;
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            background: #F8FAFC;
            height: 22px;
            position: relative
        }

        .stack-seg {
            height: 100%;
            position: relative;
            min-width: 6px;
            cursor: pointer
        }

        .stack-seg .tip {
            position: absolute;
            top: -64px;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 10px 14px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .16);
            min-width: 140px;
            text-align: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity .15s ease;
            z-index: 5;
        }

        .stack-seg .tip::after {
            content: '';
            position: absolute;
            bottom: -7px;
            left: 50%;
            transform: translateX(-50%) rotate(45deg);
            width: 12px;
            height: 12px;
            background: #fff;
            border-left: 1px solid #E2E8F0;
            border-bottom: 1px solid #E2E8F0;
        }

        .tip-val {
            font-weight: 800;
            font-size: 16px;
            color: #111827;
            line-height: 1.1
        }

        .tip-name {
            margin-top: 4px;
            font-size: 13px;
            color: #475569;
            white-space: nowrap;
            line-height: 1.2
        }

        .stack-seg:hover .tip,
        .stack-seg.active .tip {
            opacity: 1
        }

        /* Warning box */
        .warn-box {
            margin: 12px 0 6px;
            padding: 14px 16px;
            border: 1px solid #FECACA;
            background: #FEF2F2;
            border-radius: 12px;
            color: #991B1B;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .warn-ico {
            width: 32px;
            height: 32px;
            min-width: 32px;
            border-radius: 10px;
            background: #FEE2E2;
            display: grid;
            place-items: center;
            color: #DC2626;
            font-weight: 800;
        }

        .warn-text strong {
            display: block;
            font-size: 15px;
            margin-bottom: 3px
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .45);
            display: none;
            z-index: 1000;
            padding: 18px;
            overflow: auto;
        }

        .modal-overlay.show {
            display: block;
        }

        .modal {
            max-width: 620px;
            margin: 20px auto;
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: var(--shadow-2);
            overflow: hidden;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            border-bottom: 1px solid var(--line);
        }

        .modal-header h3 {
            margin: 0;
            font-weight: 800;
            font-size: 20px;
            letter-spacing: -.2px;
            color: #111827;
        }

        .btn-x {
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            border: 1px solid #E2E8F0;
            background: #fff;
            border-radius: 10px;
            cursor: pointer;
            transition: background .18s ease;
        }

        .btn-x:hover {
            background: #F8FAFC;
        }

        .btn-x i {
            font-size: 18px;
            color: #64748B;
        }

        .modal-body {
            padding: 18px 20px 6px;
        }

        .modal-footer {
            padding: 14px 20px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-save {
            flex: 1;
            height: 44px;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            color: #fff;
            background: var(--accent-2);
            box-shadow: 0 10px 22px rgba(34, 197, 94, .22);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: censter;
            gap: 8px;
            transition: filter .18s ease;
        }

        .btn-save:hover {
            filter: brightness(.95);
        }

        .btn-cancel {
            flex: 1;
            height: 44px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            font-weight: 600;
            color: #64748B;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .18s ease;
        }

        .btn-cancel:hover {
            background: #F8FAFC;
        }

        /* Form elements */
        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            color: #374151;
            margin: 0 0 8px;
            font-weight: 600;
        }

        .input,
        .textarea {
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            background: #FCFCFD;
            outline: none;
            font: inherit;
            color: #111827;
            transition: border-color .18s ease, box-shadow .18s ease;
        }

        .input:focus,
        .textarea:focus {
            border-color: #CBD5E1;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .12);
        }

        .textarea {
            height: auto;
            min-height: 110px;
            padding: 10px 12px;
            resize: vertical;
        }

        /* Alert Messages */
        .alert-message {
            margin-top: 18px;
            padding: 14px 18px;
            border-radius: 12px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border: 1px solid;
        }

        .alert-message.success {
            background: #F0FDF4;
            border-color: #86EFAC;
            color: #166534;
        }

        .alert-message.error {
            background: #FEF2F2;
            border-color: #FECACA;
            color: #991B1B;
        }

        .alert-icon {
            width: 24px;
            height: 24px;
            min-width: 24px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            font-weight: 800;
            font-size: 16px;
        }

        .alert-message.success .alert-icon {
            background: #D1FAE5;
            color: #059669;
        }

        .alert-message.error .alert-icon {
            background: #FEE2E2;
            color: #DC2626;
        }

        .alert-content {
            flex: 1;
        }

        .alert-content strong {
            display: block;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .alert-close {
            width: 28px;
            height: 28px;
            display: grid;
            place-items: center;
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: 6px;
            color: inherit;
            opacity: .6;
            transition: opacity .15s ease, background .15s ease;
        }

        .alert-close:hover {
            opacity: 1;
            background: rgba(0, 0, 0, .05);
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">Selasa, 22 September 2025</div>
            <div class="page-title">Variabel Skoring & Bobot</div>
        </div>
        <div class="page-actions">
            {{-- <div class="date-pill"><i class="ri-calendar-line"></i><span>September 2025</span></div> --}}

            {{-- Modal Create Skoring & Bobot --}}
            @include('admin.skoring_bobot.create')

            <button class="btn btn-primary btn-add"><i class="ri-add-line"></i> Tambah Variabel</button>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert-message success" id="successAlert">
            <div class="alert-icon">✓</div>
            <div class="alert-content">
                <strong>Berhasil!</strong>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert-message error" id="errorAlert">
            <div class="alert-icon">×</div>
            <div class="alert-content">
                <strong>Terjadi Kesalahan!</strong>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert-message error" id="validationAlert">
            <div class="alert-icon">×</div>
            <div class="alert-content">
                <strong>Terjadi Kesalahan Validasi!</strong>
                <ul style="margin:8px 0 0;padding-left:20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <section class="card" style="margin-top:18px;">
        <div class="card-header">


            <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.skoring.index') }}">
                <div class="input-group w-search">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Cari Variabel...">
                    @if (request('q'))
                        <button type="button" class="btn-ghost" id="btnClearSearch"><i class="ri-close-line"></i></button>
                    @endif
                </div>

                <button type="button" class="btn-ghost" id="btnReset"><i class="ri-refresh-line"></i><span
                        class="d-none d-sm-inline"> Reset</span></button>
            </form>
        </div>

        <div class="card-body" style="padding:0;">
            @php
                $palette = ['#10B981', '#22C55E', '#34D399', '#16A34A', '#0EA5E9', '#6366F1', '#F59E0B', '#EC4899'];
            @endphp

            <div style="padding:16px 18px 6px;">
                <div class="stack-label">
                    <span>Perhitungan {{ number_format($totalBobot, 0) }}%</span>
                </div>
                <div class="stack-wrap">
                    <div class="stack-bar" aria-label="Distribusi bobot">
                        @foreach ($allItems as $r)
                            <div class="stack-seg"
                                style="flex: {{ max($r->bobot, 1) }}; background: {{ $palette[$loop->index % count($palette)] }};">
                                <div class="tip">
                                    <div class="tip-val">{{ number_format($r->bobot, 0) }}%</div>
                                    <div class="tip-name">{{ $r->nama }}</div>
                                </div>
                            </div>
                        @endforeach
                        @php $remain = max(0, 100 - $totalBobot); @endphp
                        @if ($remain > 0)
                            <div class="stack-seg" style="flex: {{ $remain }}; background: #E5E7EB;">
                                <div class="tip">
                                    <div class="tip-val">{{ number_format($remain, 0) }}%</div>
                                    <div class="tip-name">Belum terisi</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @if ($totalBobot < 100)
                    <div class="warn-box">
                        <div class="warn-ico">×</div>
                        <div class="warn-text">
                            <strong>Kondisi Perhitungan Tidak Lengkap</strong>
                            <span>Segera lengkapi kebutuhan variabel skoring & bobot demi keselarasan data</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="table-responsive table-shell">
                <table class="data">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Tipe Bobot</th>
                            <th class="col-bbt">Prosentase</th>
                            <th>Keterangan</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $r)
                            <tr>
                                <td class="col-no">{{ $items->firstItem() + $loop->index }}</td>
                                <td><strong>{{ $r->nama }}</strong></td>
                                <td class="col-bbt">{{ number_format($r->bobot, 0) }}%</td>
                                <td>{{ $r->keterangan ?? '-' }}</td>
                                <td class="col-aksi">
                                    <button type="button" class="btn-ico btn-edit" data-id="{{ $r->id }}"
                                        data-nama="{{ $r->nama }}" data-bobot="{{ $r->bobot }}"
                                        data-keterangan="{{ $r->keterangan }}" title="Edit"><i
                                            class="ri-pencil-line"></i></button>
                                    <button type="button" class="btn-ico danger btn-delete" data-id="{{ $r->id }}"
                                        data-nama="{{ $r->nama }}" title="Hapus"><i
                                            class="ri-delete-bin-6-line"></i></button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;padding:40px;color:#94A3B8">Tidak ada data
                                    variabel</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="table-footer">
                    <div class="summary">Menampilkan
                        <strong>{{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }}</strong> dari
                        <strong>{{ $items->total() }}</strong> data</div>

                    <div class="show-wrap">
                        <span>Show</span>
                        <form id="perPageForm" method="GET" action="#">
                            <input type="hidden" name="q" value="{{ request('q') }}">
                            <input type="hidden" name="by" value="{{ request('by') }}">
                            <input type="hidden" name="val" value="{{ request('val') }}">
                            <select class="form-select auto-submit" name="per_page"
                                aria-label="Jumlah baris per halaman">
                                @foreach ([5, 10, 25, 50, 100] as $pp)
                                    <option value="{{ $pp }}"
                                        {{ (string) request('per_page', '10') === (string) $pp ? 'selected' : '' }}>
                                        {{ $pp }}</option>
                                @endforeach
                            </select>
                        </form>
                        <span>per page</span>
                    </div>

                    <nav aria-label="Pagination">
                        {{ $items->links('pagination::bootstrap-4') }}
                    </nav>
                </div>

            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ========== SweetAlert Notifications ==========
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    customClass: {
                        popup: 'swal-custom-toast'
                    }
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#22C55E',
                    confirmButtonText: 'OK'
                });
            @endif

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan Validasi!',
                    html: '<ul style="text-align:left; padding-left:20px; margin:0;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                    confirmButtonColor: '#22C55E',
                    confirmButtonText: 'OK'
                });
            @endif

            // ========== Form Handling ==========
            const filterForm = document.getElementById('filterForm');
            const perPageForm = document.getElementById('perPageForm');
            document.querySelectorAll('.auto-submit').forEach(el => {
                el.addEventListener('change', () => {
                    if (perPageForm && perPageForm.contains(el)) perPageForm.submit();
                    else if (filterForm) filterForm.submit();
                });
            });
            document.getElementById('btnClearSearch')?.addEventListener('click', () => {
                const i = filterForm.querySelector('input[name="q"]');
                if (i) i.value = '';
                filterForm.submit();
            });
            document.getElementById('btnReset')?.addEventListener('click', () => {
                filterForm.reset();
                const i = filterForm.querySelector('input[name="q"]');
                if (i) i.value = '';
                filterForm.submit();
            });

            // ========== Auto-hide Alert Messages ==========
            const alerts = document.querySelectorAll('.alert-message');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.3s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });

            // ========== Tooltip: klik segmen untuk tampil, klik luar untuk tutup ==========
            const segments = document.querySelectorAll('.stack-seg');
            const deactivateAll = () => segments.forEach(s => s.classList.remove('active'));
            segments.forEach(seg => {
                seg.addEventListener('click', (e) => {
                    e.stopPropagation();
                    deactivateAll();
                    seg.classList.add('active');
                });
            });
            document.addEventListener('click', (e) => {
                const bar = document.querySelector('.stack-bar');
                if (!bar || bar.contains(e.target)) return;
                deactivateAll();
            });
        });
    </script>
@endpush
