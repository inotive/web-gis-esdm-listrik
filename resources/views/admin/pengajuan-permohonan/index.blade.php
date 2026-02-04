@extends('admin.layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        /* Status Badge - Match Reference */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-aktif { background: #ECFDF5; color: #059669; }     /* Selesai/Aktif - Green */
        .status-menunggu { background: #FEF3C7; color: #D97706; }  /* Pending - Orange */
        .status-proses { background: #E0F2FE; color: #0284C7; }    /* Proses - Blue */
        .status-expired { background: #FEE2E2; color: #DC2626; }   /* Ditolak/Expired - Red */
        .status-ditolak { background: #F1F5F9; color: #64748B; }   /* Ditolak - Gray */
        
        /* Action Buttons - Match Reference */
        .btn-ico {
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: transparent;
            cursor: pointer;
            transition: transform 0.2s;
            padding: 0;
            margin: 0 4px;
            font-size: 16px; /* FontAwesome size */
        }
        
        .btn-ico:hover { transform: scale(1.1); }
        .btn-ico.view { color: #0077B6; } /* Blue */
        .btn-ico.edit { color: #f59e0b; } /* Orange */
        .btn-ico.danger { color: #ef4444; } /* Red */
        
        .col-aksi {
            text-align: left !important;
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
                                    @php
                                        $statusClass = 'status-ditolak';
                                        $statusLabel = $permohonanUser->status;
                                        
                                        if ($permohonanUser->status === 'pending') {
                                            $statusClass = 'status-menunggu';
                                            $statusLabel = 'Pending';
                                        } elseif ($permohonanUser->status === 'proses') {
                                            $statusClass = 'status-proses';
                                            $statusLabel = 'Proses';
                                        } elseif ($permohonanUser->status === 'selesai') {
                                            $statusClass = 'status-aktif';
                                            $statusLabel = 'Aktif';
                                        } elseif ($permohonanUser->status === 'ditolak') {
                                            $statusClass = 'status-expired';
                                            $statusLabel = 'Ditolak';
                                        } elseif ($permohonanUser->status === 'dibatalkan') {
                                            $statusClass = 'status-expired';
                                            $statusLabel = 'Dibatalkan';
                                        } else {
                                             $statusLabel = ucfirst($permohonanUser->status);
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td>{{ $permohonanUser->created_at->translatedFormat('d F Y') }}</td>
                                <td>{{ $permohonanUser->keterangan ?? '-' }}</td>
                                <td class="col-aksi">
                                    {{-- Cancel/Delete Button (Available only if pending) --}}
                                    @if ($permohonanUser->status === 'pending')
                                        <form action="{{ route('admin.pengajuan-permohonan.destroy', $permohonanUser) }}"
                                            method="POST" style="display:inline-block;margin:0;"
                                            class="form-cancel-permohonan"
                                            data-name="{{ $permohonanUser->permohonan->nama }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-ico danger btn-cancel-permohonan"
                                                title="Batalkan Permohonan">
                                                <i class="fa-solid fa-xmark"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Detail Button (Always available) --}}
                                    <a href="{{ route('admin.pengajuan-permohonan.show', $permohonanUser) }}"
                                        class="btn-ico view" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    {{-- Edit Button (Available only if pending) --}}
                                    @if ($permohonanUser->status === 'pending')
                                        <a href="{{ route('admin.pengajuan-permohonan.edit', $permohonanUser) }}"
                                            class="btn-ico edit" title="Edit">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
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
