@extends('admin.layouts.app')

@section('title', 'Pengajuan Permohonan Saya')

@push('styles')
    <style>
        .card-header {
            background: white;
            border-bottom: 1px solid #F1F1F4;
            padding: 8px 20px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .w-search {
            width: 250px;
        }

        .w-filter {
            width: 200px;
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

        .input-group .form-control {
            height: 100%;
            border: none;
            background: transparent;
            padding: 0 10px;
            font-size: 11px;
            color: #78829D;
            outline: none;
            width: 100%;
        }

        .form-select {
            height: 32px;
            border: 1px solid #DBDFE9;
            border-radius: 6px;
            background: #FCFCFC;
            padding: 0 32px 0 10px;
            font-size: 11px;
            color: #78829D;
            outline: none;
            cursor: pointer;
        }

        .form-select:focus {
            border-color: #6366F1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .status-tabs {
            display: flex;
            gap: 8px;
            padding: 16px 20px 0;
            border-bottom: 1px solid #F1F1F4;
            background: white;
        }

        .status-tab {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border: none;
            background: transparent;
            color: #64748B;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            text-decoration: none;
        }

        .status-tab:hover {
            color: #6366F1;
            background: #F8F9FF;
        }

        .status-tab.active {
            color: #6366F1;
            border-bottom-color: #6366F1;
        }

        .status-tab .count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            background: #E2E8F0;
            color: #64748B;
            font-size: 11px;
            font-weight: 600;
            border-radius: 10px;
        }

        .status-tab.active .count {
            background: #6366F1;
            color: white;
        }

        .table-permohonan {
            width: 100%;
            border-collapse: collapse;
        }

        .table-permohonan thead {
            background: #FCFCFC;
        }

        .table-permohonan thead th {
            background: #FCFCFC;
            color: #4B5675;
            font-weight: 400;
            font-size: 13px;
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #F1F1F4;
        }

        .table-permohonan tbody td {
            padding: 23px 20px;
            border-bottom: 1px solid #F1F1F4;
            color: #252F4A;
            font-size: 14px;
            vertical-align: middle;
        }

        .table-permohonan tbody tr:hover {
            background: #FCFCFC;
        }

        .col-aksi {
            width: 180px;
            text-align: center;
        }

        .btn-ico {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            cursor: pointer;
            margin: 0 6px;
        }

        .btn-ico.detail svg path {
            stroke: #6366F1;
        }

        .btn-ico.danger svg path {
            stroke: #F8285A;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-pending {
            background: #FEF3C7;
            color: #92400E;
        }

        .badge-proses {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .badge-selesai {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-ditolak {
            background: #FEE2E2;
            color: #991B1B;
        }

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
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Pengajuan Permohonan Saya</div>
        </div>
        <div class="page-actions">
            <div class="date-pill">
                <i class="ri-calendar-line"></i>
                <span>{{ now()->translatedFormat('F Y') }}</span>
            </div>

            <a href="{{ route('admin.pengajuan-permohonan.select-type') }}" class="btn btn-primary">
                <i class="ri-add-line"></i>
                Buat Permohonan Baru
            </a>
        </div>
    </div>

    <section class="card" style="margin-top:18px;">
        <!-- Status Tabs -->
        <div class="status-tabs">
            <a href="{{ route('admin.pengajuan-permohonan.index', array_merge(request()->except('status'), ['status' => 'semua'])) }}"
                class="status-tab {{ $currentStatus === 'semua' ? 'active' : '' }}">
                <span>Semua</span>
                <span class="count">{{ $statusCounts['semua'] }}</span>
            </a>
            <a href="{{ route('admin.pengajuan-permohonan.index', array_merge(request()->except('status'), ['status' => 'pending'])) }}"
                class="status-tab {{ $currentStatus === 'pending' ? 'active' : '' }}">
                <span>Pending</span>
                <span class="count">{{ $statusCounts['pending'] }}</span>
            </a>
            <a href="{{ route('admin.pengajuan-permohonan.index', array_merge(request()->except('status'), ['status' => 'proses'])) }}"
                class="status-tab {{ $currentStatus === 'proses' ? 'active' : '' }}">
                <span>Proses</span>
                <span class="count">{{ $statusCounts['proses'] }}</span>
            </a>
            <a href="{{ route('admin.pengajuan-permohonan.index', array_merge(request()->except('status'), ['status' => 'selesai'])) }}"
                class="status-tab {{ $currentStatus === 'selesai' ? 'active' : '' }}">
                <span>Selesai</span>
                <span class="count">{{ $statusCounts['selesai'] }}</span>
            </a>
            <a href="{{ route('admin.pengajuan-permohonan.index', array_merge(request()->except('status'), ['status' => 'ditolak'])) }}"
                class="status-tab {{ $currentStatus === 'ditolak' ? 'active' : '' }}">
                <span>Ditolak</span>
                <span class="count">{{ $statusCounts['ditolak'] }}</span>
            </a>
        </div>

        <div class="card-header">
            <form id="filterForm" class="toolbar" method="GET" action="{{ route('admin.pengajuan-permohonan.index') }}">
                <!-- Preserve status filter -->
                <input type="hidden" name="status" value="{{ $currentStatus }}">

                <div class="input-group w-search">
                    <span class="input-group-text"><i class="ri-search-line"></i></span>
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="Cari Nama atau Keterangan" autocomplete="off">
                </div>

                <select name="permohonan_id" class="form-select w-filter" onchange="this.form.submit()">
                    <option value="">Semua Jenis Permohonan</option>
                    @foreach ($permohonans as $permohonan)
                        <option value="{{ $permohonan->id }}"
                            {{ $currentPermohonanId == $permohonan->id ? 'selected' : '' }}>
                            {{ $permohonan->nama }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="card-body" style="padding:0;">
            <div class="table-responsive table-shell">
                <table class="table-permohonan">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th>Nama Permohonan</th>
                            <th>Status</th>
                            <th>Tanggal Dibuat</th>
                            <th>Keterangan</th>
                            <th class="col-aksi">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($permohonanUsers as $i => $permohonanUser)
                            <tr>
                                <td class="col-no">{{ $permohonanUsers->firstItem() + $i }}</td>
                                <td><strong>{{ $permohonanUser->permohonan->nama }}</strong></td>
                                <td>
                                    <span class="badge badge-{{ $permohonanUser->status }}">
                                        @if ($permohonanUser->status === 'pending')
                                            Pending
                                        @elseif($permohonanUser->status === 'proses')
                                            Proses
                                        @elseif($permohonanUser->status === 'selesai')
                                            Selesai
                                        @elseif($permohonanUser->status === 'ditolak')
                                            Ditolak
                                        @else
                                            {{ $permohonanUser->status }}
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $permohonanUser->created_at->translatedFormat('d F Y') }}</td>
                                <td>{{ $permohonanUser->keterangan ?? '-' }}</td>
                                <td class="col-aksi">
                                    <a href="{{ route('admin.pengajuan-permohonan.show', $permohonanUser) }}"
                                        class="btn-ico detail" title="Detail">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z"
                                                stroke="#6366F1" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M2.45801 12C3.73201 7.943 7.52301 5 12 5C16.478 5 20.268 7.943 21.542 12C20.268 16.057 16.478 19 12 19C7.52301 19 3.73201 16.057 2.45801 12Z"
                                                stroke="#6366F1" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>

                                    @if ($permohonanUser->status === 'pending')
                                        <a href="{{ route('admin.pengajuan-permohonan.edit', $permohonanUser) }}"
                                            class="btn-ico detail" title="Edit">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11 4H4C3.46957 4 2.96086 4.21071 2.58579 4.58579C2.21071 4.96086 2 5.46957 2 6V20C2 20.5304 2.21071 21.0391 2.58579 21.4142C2.96086 21.7893 3.46957 22 4 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V13"
                                                    stroke="#6366F1" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M18.5 2.50001C18.8978 2.10219 19.4374 1.87869 20 1.87869C20.5626 1.87869 21.1022 2.10219 21.5 2.50001C21.8978 2.89784 22.1213 3.4374 22.1213 4.00001C22.1213 4.56262 21.8978 5.10219 21.5 5.50001L12 15L8 16L9 12L18.5 2.50001Z"
                                                    stroke="#6366F1" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('admin.pengajuan-permohonan.destroy', $permohonanUser) }}"
                                            method="POST" style="display:inline-block;margin:0;"
                                            class="form-cancel-permohonan"
                                            data-name="{{ $permohonanUser->permohonan->nama }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-ico danger btn-cancel-permohonan"
                                                title="Batalkan">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M18 6L6 18M6 6L18 18" stroke="#F8285A" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center"
                                    style="text-align:center;color:#64748B;padding:40px;">Belum ada data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="table-footer">
                    <div class="summary">Menampilkan
                        <strong>{{ $permohonanUsers->firstItem() ?? 0 }}–{{ $permohonanUsers->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $permohonanUsers->total() }}</strong> data
                    </div>
                    <nav aria-label="Pagination">
                        {{ $permohonanUsers->links('pagination::bootstrap-4') }}
                    </nav>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                });
            @endif

            // Auto submit on search
            const searchInput = document.querySelector('input[name="q"]');
            if (searchInput) {
                let timeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        document.getElementById('filterForm').submit();
                    }, 500);
                });
            }

            // Cancel confirmation
            document.querySelectorAll('.btn-cancel-permohonan').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.form-cancel-permohonan');
                    const name = form.dataset.name;

                    Swal.fire({
                        title: 'Konfirmasi Pembatalan',
                        html: `Apakah Anda yakin ingin membatalkan permohonan <strong>${name}</strong>?<br><small class="text-muted">Permohonan yang dibatalkan tidak dapat dikembalikan.</small>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: '<i class="ri-close-line"></i> Ya, Batalkan!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
