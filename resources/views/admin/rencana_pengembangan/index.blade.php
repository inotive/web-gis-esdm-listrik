@extends('admin.layouts.app')

@section('title', 'Rencana Pengembangan Bantuan Ketenagalistrikan')

@push('styles')
    <style>
        /* Table styling */
        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
            -webkit-overflow-scrolling: touch;
        }

        .table-rencana {
            width: max-content;
            min-width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .table-rencana thead {
            background: #F8FAFC;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .table-rencana thead th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            color: #4B5675;
            border-bottom: 2px solid #E2E8F0;
            white-space: nowrap;
            min-width: 120px;
        }

        /* Filter row styling */
        .filter-row {
            background: #FFFFFF;
            position: sticky;
            top: 45px;
            z-index: 9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .filter-row td {
            padding: 8px !important;
            border-bottom: 2px solid #E2E8F0 !important;
        }

        .filter-select {
            width: 100%;
            min-width: 100px;
            max-width: 250px;
            /* Limit maximum width */
            padding: 6px 8px;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            font-size: 12px;
            background: #F8FAFC;
            cursor: pointer;
            transition: all 0.2s;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .filter-select option {
            white-space: normal;
            padding: 4px;
        }

        .filter-select:hover {
            border-color: #CBD5E1;
            background: #fff;
        }

        .filter-select:focus {
            outline: none;
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            background: #fff;
        }

        .filter-input {
            width: 100%;
            min-width: 80px;
            padding: 6px 8px;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            font-size: 12px;
            background: #F8FAFC;
        }

        .filter-input:focus {
            outline: none;
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
            background: #fff;
        }

        .btn-clear-filter {
            padding: 6px 10px;
            background: #EF4444;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 11px;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .btn-clear-filter:hover {
            background: #DC2626;
        }

        .table-rencana tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: middle;
            white-space: nowrap;
        }

        .table-rencana tbody tr:hover {
            background: #F8FAFC;
        }

        /* Editable dropdown styling */
        .editable-select {
            min-width: 200px;
            max-width: 400px;
            padding: 6px 8px;
            border: 1px solid #E2E8F0;
            border-radius: 6px;
            font-size: 12px;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s;
        }

        .editable-select:hover {
            border-color: #CBD5E1;
        }

        .editable-select:focus {
            outline: none;
            border-color: #10B981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        /* Score badges */
        .score-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            text-align: center;
            min-width: 40px;
        }

        .score-1 {
            background: #FEE2E2;
            color: #991B1B;
        }

        .score-2 {
            background: #FED7AA;
            color: #9A3412;
        }

        .score-3 {
            background: #FEF3C7;
            color: #92400E;
        }

        .score-4 {
            background: #D1FAE5;
            color: #065F46;
        }

        .score-5 {
            background: #A7F3D0;
            color: #065F46;
        }

        /* Total score */
        .total-score {
            font-weight: 700;
            font-size: 14px;
            color: #10B981;
            padding: 6px 12px;
            background: #ECFDF5;
            border-radius: 8px;
        }

        /* Prioritas badge */
        .prioritas-badge {
            padding: 4px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .prioritas-rkts {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .prioritas-sjtm {
            background: #FEF3C7;
            color: #92400E;
        }

        /* Filter section */
        .filter-bar {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            padding: 16px;
            background: #F8FAFC;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .filter-item {
            flex: 1;
            min-width: 200px;
        }

        .filter-item label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            margin-bottom: 6px;
        }

        .filter-item select,
        .filter-item input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            font-size: 13px;
            background: #fff;
        }

        /* Action buttons */
        .btn-primary {
            padding: 10px 20px;
            background: #10B981;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #059669;
        }

        .btn-danger {
            padding: 6px 12px;
            background: #EF4444;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-danger:hover {
            background: #DC2626;
        }

        /* Loading state */
        .loading {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Rencana Pengembangan Bantuan Ketenagalistrikan</div>
        </div>
        <div class="page-actions">
            <div class="date-pill"><i class="ri-calendar-line"></i><span>{{ now()->translatedFormat('F Y') }}</span></div>
            @can('rencana_pengembangan.create')
            <button class="btn btn-primary" onclick="openAddModal()"><i class="ri-add-line"></i> Tambah Data</button>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @endif

    <!-- Filter Section - Hidden temporarily -->
    <form method="GET" action="{{ route('admin.rencana-pengembangan.index') }}" class="filter-bar" style="display: none;">
        <div class="filter-item">
            <label>Kabupaten/Kota</label>
            <select name="regency_id" class="auto-submit">
                <option value="">Semua Kabupaten</option>
                @foreach ($regencies as $reg)
                    <option value="{{ $reg->id }}" {{ request('regency_id') == $reg->id ? 'selected' : '' }}>
                        {{ $reg->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-item">
            <label>Kecamatan</label>
            <select name="district_id" class="auto-submit">
                <option value="">Semua Kecamatan</option>
                @foreach ($districts as $dist)
                    <option value="{{ $dist->id }}" {{ request('district_id') == $dist->id ? 'selected' : '' }}>
                        {{ $dist->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-item">
            <label>Prioritas</label>
            <select name="prioritas" class="auto-submit">
                <option value="">Semua Prioritas</option>
                @foreach ($prioritasOptions as $prio)
                    <option value="{{ $prio }}" {{ request('prioritas') == $prio ? 'selected' : '' }}>
                        {{ $prio }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-item">
            <label>Pencarian</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari desa...">
        </div>

        <div class="filter-item" style="display: flex; align-items: flex-end;">
            <button type="submit" class="btn-primary" style="width: 100%;">Filter</button>
        </div>
    </form>

    <!-- Table Section -->
    <div class="card">
        <div class="table-responsive">
            <table class="table-rencana">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kabupaten</th>
                        <th>Kecamatan</th>
                        <th>Desa</th>
                        <th>Jumlah Calon Pelanggan</th>
                        <th>Aksesibilitas</th>
                        <th>Skor</th>
                        <th>Radius Jaringan</th>
                        <th>Skor</th>
                        <th>Arah & Kebijakan</th>
                        <th>Skor</th>
                        <th>Potensi Kegiatan</th>
                        <th>Skor</th>
                        <th>Jumlah Pelanggan</th>
                        <th>Skor</th>
                        <th>Total Skor</th>
                        <th>Prioritas</th>
                        <th>Aksi</th>
                    </tr>
                    <!-- Filter Row -->
                    <tr class="filter-row">
                        <td></td>
                        <td>
                            <select class="filter-select table-filter" name="filter_regency_id" id="filter_regency_id">
                                <option value="">Semua</option>
                                @foreach ($regencies as $reg)
                                    <option value="{{ $reg->id }}"
                                        {{ request('filter_regency_id') == $reg->id ? 'selected' : '' }}>
                                        {{ $reg->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select class="filter-select table-filter" name="filter_district_id" id="filter_district_id">
                                <option value="">Semua</option>
                                @foreach ($districts as $dist)
                                    <option value="{{ $dist->id }}"
                                        {{ request('filter_district_id') == $dist->id ? 'selected' : '' }}>
                                        {{ $dist->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" class="filter-input table-filter" name="filter_village"
                                id="filter_village" placeholder="Cari desa..." value="{{ request('filter_village') }}">
                        </td>
                        <td>
                            <input type="number" class="filter-input table-filter" name="filter_min_pelanggan"
                                id="filter_min_pelanggan" placeholder="Min..."
                                value="{{ request('filter_min_pelanggan') }}">
                        </td>
                        <td>
                            <select class="filter-select table-filter" name="filter_aksesibilitas"
                                id="filter_aksesibilitas">
                                <option value="">Semua</option>
                                @foreach ($uniqueAksesibilitas as $aks)
                                    <option value="{{ $aks }}"
                                        {{ request('filter_aksesibilitas') == $aks ? 'selected' : '' }}>
                                        {{ $aks }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td></td>
                        <td>
                            <select class="filter-select table-filter" name="filter_radius_jaringan"
                                id="filter_radius_jaringan">
                                <option value="">Semua</option>
                                @foreach ($uniqueRadiusJaringan as $rad)
                                    <option value="{{ $rad }}"
                                        {{ request('filter_radius_jaringan') == $rad ? 'selected' : '' }}>
                                        {{ $rad }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td></td>
                        <td>
                            <select class="filter-select table-filter" name="filter_arah_kebijakan"
                                id="filter_arah_kebijakan">
                                <option value="">Semua</option>
                                @foreach ($uniqueArahKebijakan as $keb)
                                    <option value="{{ $keb }}"
                                        {{ request('filter_arah_kebijakan') == $keb ? 'selected' : '' }}>
                                        {{ $keb }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td></td>
                        <td>
                            <select class="filter-select table-filter" name="filter_potensi_kegiatan"
                                id="filter_potensi_kegiatan">
                                <option value="">Semua</option>
                                @foreach ($uniquePotensiKegiatan as $pot)
                                    <option value="{{ $pot }}"
                                        {{ request('filter_potensi_kegiatan') == $pot ? 'selected' : '' }}>
                                        {{ $pot }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td></td>
                        <td>
                            <select class="filter-select table-filter" name="filter_jumlah_pelanggan"
                                id="filter_jumlah_pelanggan">
                                <option value="">Semua</option>
                                @foreach ($uniqueJumlahPelanggan as $jml)
                                    <option value="{{ $jml }}"
                                        {{ request('filter_jumlah_pelanggan') == $jml ? 'selected' : '' }}>
                                        {{ $jml }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                            <select class="filter-select table-filter" name="filter_prioritas" id="filter_prioritas">
                                <option value="">Semua</option>
                                @foreach ($prioritasOptions as $prio)
                                    <option value="{{ $prio }}"
                                        {{ request('filter_prioritas') == $prio ? 'selected' : '' }}>
                                        {{ $prio }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn-clear-filter" onclick="clearAllTableFilters()">
                                <i class="ri-close-line"></i> Reset
                            </button>
                        </td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $index => $item)
                        <tr data-id="{{ $item->id }}">
                            <td>{{ $data->firstItem() + $index }}</td>
                            <td>{{ $item->regency->name ?? '-' }}</td>
                            <td>{{ $item->district->name ?? '-' }}</td>
                            <td>{{ $item->village->name ?? '-' }}</td>
                            <td>{{ $item->jumlah_calon_pelanggan ?? '-' }}</td>

                            <!-- Aksesibilitas -->
                            <td>
                                <select class="editable-select" data-field="aksesibilitas"
                                    data-id="{{ $item->id }}" @disabled(auth()->user()->cannot('rencana_pengembangan.edit'))>
                                    <option value="">Pilih...</option>
                                    @foreach ($aksesibilitasOptions as $text => $score)
                                        <option value="{{ $text }}"
                                            {{ $item->aksesibilitas && $item->aksesibilitas == $text ? 'selected' : '' }}
                                            data-score="{{ $score }}">
                                            {{ $text }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><span class="score-badge score-{{ $item->skor_aksesibilitas ?? 0 }}"
                                    data-field-score="skor_aksesibilitas">{{ $item->skor_aksesibilitas ?? 0 }}</span>
                            </td>

                            <!-- Radius Jaringan -->
                            <td>
                                <select class="editable-select" data-field="radius_jaringan"
                                    data-id="{{ $item->id }}" @disabled(auth()->user()->cannot('rencana_pengembangan.edit'))>
                                    <option value="">Pilih...</option>
                                    @foreach ($radiusOptions as $text => $score)
                                        <option value="{{ $text }}"
                                            {{ $item->radius_jaringan && $item->radius_jaringan == $text ? 'selected' : '' }}
                                            data-score="{{ $score }}">
                                            {{ $text }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><span class="score-badge score-{{ $item->skor_radius ?? 0 }}"
                                    data-field-score="skor_radius">{{ $item->skor_radius ?? 0 }}</span>
                            </td>

                            <!-- Arah Kebijakan -->
                            <td>
                                <select class="editable-select" data-field="arah_kebijakan"
                                    data-id="{{ $item->id }}" @disabled(auth()->user()->cannot('rencana_pengembangan.edit'))>
                                    <option value="">Pilih...</option>
                                    @foreach ($kebijakanOptions as $text => $score)
                                        <option value="{{ $text }}"
                                            {{ $item->arah_kebijakan && $item->arah_kebijakan == $text ? 'selected' : '' }}
                                            data-score="{{ $score }}">
                                            {{ $text }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><span class="score-badge score-{{ $item->skor_arah_kebijakan ?? 0 }}"
                                    data-field-score="skor_arah_kebijakan">{{ $item->skor_arah_kebijakan ?? 0 }}</span>
                            </td>

                            <!-- Potensi Kegiatan -->
                            <td>
                                <select class="editable-select" data-field="potensi_kegiatan"
                                    data-id="{{ $item->id }}" @disabled(auth()->user()->cannot('rencana_pengembangan.edit'))>
                                    <option value="">Pilih...</option>
                                    @foreach ($potensiOptions as $text => $score)
                                        <option value="{{ $text }}"
                                            {{ $item->potensi_kegiatan && $item->potensi_kegiatan == $text ? 'selected' : '' }}
                                            data-score="{{ $score }}">
                                            {{ $text }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><span class="score-badge score-{{ $item->skor_potensi_kegiatan ?? 0 }}"
                                    data-field-score="skor_potensi_kegiatan">{{ $item->skor_potensi_kegiatan ?? 0 }}</span>
                            </td>

                            <!-- Jumlah Pelanggan -->
                            <td>
                                <select class="editable-select" data-field="jumlah_pelanggan"
                                    data-id="{{ $item->id }}" @disabled(auth()->user()->cannot('rencana_pengembangan.edit'))>
                                    <option value="">Pilih...</option>
                                    @foreach ($pelangganOptions as $text => $score)
                                        <option value="{{ $text }}"
                                            {{ $item->jumlah_pelanggan && $item->jumlah_pelanggan == $text ? 'selected' : '' }}
                                            data-score="{{ $score }}">
                                            {{ $text }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><span class="score-badge score-{{ $item->skor_jumlah_pelanggan ?? 0 }}"
                                    data-field-score="skor_jumlah_pelanggan">{{ $item->skor_jumlah_pelanggan ?? 0 }}</span>
                            </td>

                            <!-- Total & Prioritas -->
                            <td><span class="total-score">{{ $item->total_skor ?? 0 }}</span></td>
                            <td>
                                @if ($item->prioritas)
                                    <span
                                        class="prioritas-badge {{ Str::contains($item->prioritas, 'RKTS') ? 'prioritas-rkts' : 'prioritas-sjtm' }}">
                                        {{ $item->prioritas }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>

                            <!-- Actions -->
                            <td>
                                @can('rencana_pengembangan.delete')
                                <form action="{{ route('admin.rencana-pengembangan.destroy', $item->id) }}"
                                    method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger"
                                        onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="18" style="text-align: center; padding: 40px; color: #94A3B8;">
                                <i class="ri-inbox-line" style="font-size: 48px; display: block; margin-bottom: 8px;"></i>
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($data->hasPages())
            <div style="padding: 16px; border-top: 1px solid #F1F5F9;">
                {{ $data->links() }}
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-submit filters
            document.querySelectorAll('.auto-submit').forEach(el => {
                el.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });

            // Inline editing
            document.querySelectorAll('.editable-select').forEach(select => {
                select.addEventListener('change', function() {
                    const id = this.dataset.id;
                    const field = this.dataset.field;
                    const value = this.value;
                    const row = this.closest('tr');

                    // Show loading state
                    row.classList.add('loading');

                    // Send AJAX request
                    fetch(`/admin/rencana-pengembangan/${id}/update-field`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                field,
                                value
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                console.log('Update successful:', data);

                                // Update score badges
                                updateScoreBadge(row, 'skor_aksesibilitas', data
                                    .skor_aksesibilitas);
                                updateScoreBadge(row, 'skor_radius', data.skor_radius);
                                updateScoreBadge(row, 'skor_arah_kebijakan', data
                                    .skor_arah_kebijakan);
                                updateScoreBadge(row, 'skor_potensi_kegiatan', data
                                    .skor_potensi_kegiatan);
                                updateScoreBadge(row, 'skor_jumlah_pelanggan', data
                                    .skor_jumlah_pelanggan);

                                // Update total score with animation
                                const totalScoreEl = row.querySelector('.total-score');
                                if (totalScoreEl) {
                                    totalScoreEl.textContent = data.total_skor;
                                    totalScoreEl.style.transform = 'scale(1.2)';
                                    totalScoreEl.style.background = '#10B981';
                                    totalScoreEl.style.color = '#fff';
                                    setTimeout(() => {
                                        totalScoreEl.style.transform = 'scale(1)';
                                        totalScoreEl.style.background = '#ECFDF5';
                                        totalScoreEl.style.color = '#10B981';
                                    }, 300);
                                }

                                // Show success toast
                                showToast('✓ Data berhasil diperbarui', 'success');
                            } else {
                                showToast('✗ Gagal memperbarui data', 'error');
                            }
                            row.classList.remove('loading');
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('✗ Terjadi kesalahan saat menyimpan data', 'error');
                            row.classList.remove('loading');
                        });
                });
            });

            function updateScoreBadge(row, field, value) {
                // Find badge by data-field-score attribute
                const badge = row.querySelector(`[data-field-score="${field}"]`);
                if (badge) {
                    badge.textContent = value;
                    badge.className = `score-badge score-${value}`;
                }
            }

            // Toast notification function
            function showToast(message, type = 'success') {
                const toast = document.createElement('div');
                toast.className = `toast toast-${type}`;
                toast.innerHTML = message;
                toast.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 14px 20px;
                    background: ${type === 'success' ? '#10B981' : '#EF4444'};
                    color: white;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                    z-index: 9999;
                    animation: slideIn 0.3s ease-out;
                    font-size: 14px;
                    font-weight: 500;
                    min-width: 250px;
                `;

                document.body.appendChild(toast);

                setTimeout(() => {
                    toast.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            }

            // Add CSS animations
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                    to {
                        transform: translateX(0);
                        opacity: 1;
                    }
                }
                @keyframes slideOut {
                    from {
                        transform: translateX(0);
                        opacity: 1;
                    }
                    to {
                        transform: translateX(100%);
                        opacity: 0;
                    }
                }
                .total-score {
                    transition: all 0.3s ease-out;
                }
            `;
            document.head.appendChild(style);

            // ========== BACKEND TABLE FILTERING ==========
            let filterTimeout;

            // Add event listeners to all table filters
            document.querySelectorAll('.table-filter').forEach(filter => {
                // For select dropdowns - immediate submit
                if (filter.tagName === 'SELECT') {
                    filter.addEventListener('change', function() {
                        submitTableFilters();
                    });
                }
                // For text/number inputs - debounced submit
                else {
                    filter.addEventListener('input', function() {
                        clearTimeout(filterTimeout);
                        filterTimeout = setTimeout(() => {
                            submitTableFilters();
                        }, 800); // Wait 800ms after user stops typing
                    });
                }
            });

            function submitTableFilters() {
                const url = new URL(window.location.href);
                const params = new URLSearchParams(url.search);

                // Get all filter values
                const filters = {
                    filter_regency_id: document.getElementById('filter_regency_id')?.value || '',
                    filter_district_id: document.getElementById('filter_district_id')?.value || '',
                    filter_village: document.getElementById('filter_village')?.value || '',
                    filter_min_pelanggan: document.getElementById('filter_min_pelanggan')?.value || '',
                    filter_aksesibilitas: document.getElementById('filter_aksesibilitas')?.value || '',
                    filter_radius_jaringan: document.getElementById('filter_radius_jaringan')?.value || '',
                    filter_arah_kebijakan: document.getElementById('filter_arah_kebijakan')?.value || '',
                    filter_potensi_kegiatan: document.getElementById('filter_potensi_kegiatan')?.value || '',
                    filter_jumlah_pelanggan: document.getElementById('filter_jumlah_pelanggan')?.value || '',
                    filter_prioritas: document.getElementById('filter_prioritas')?.value || ''
                };

                // Clear existing filter params
                Object.keys(filters).forEach(key => params.delete(key));

                // Add non-empty filters to URL
                Object.entries(filters).forEach(([key, value]) => {
                    if (value) {
                        params.set(key, value);
                    }
                });

                // Keep existing params (like regency_id, district_id from top filter bar)
                // Reset to page 1 when filtering
                params.delete('page');

                // Redirect with new filters
                window.location.href = `${url.pathname}?${params.toString()}`;
            }

            // Clear all table filters function
            window.clearAllTableFilters = function() {
                const url = new URL(window.location.href);
                const params = new URLSearchParams(url.search);

                // Remove all filter parameters
                params.delete('filter_regency_id');
                params.delete('filter_district_id');
                params.delete('filter_village');
                params.delete('filter_min_pelanggan');
                params.delete('filter_aksesibilitas');
                params.delete('filter_radius_jaringan');
                params.delete('filter_arah_kebijakan');
                params.delete('filter_potensi_kegiatan');
                params.delete('filter_jumlah_pelanggan');
                params.delete('filter_prioritas');
                params.delete('page');

                // Redirect without filters
                window.location.href = `${url.pathname}?${params.toString()}`;
            };
        });
    </script>
@endpush
