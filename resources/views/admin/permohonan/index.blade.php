@extends('admin.layouts.app')

@section('title', 'Data Permohonan Masuk')

@push('styles')
  <style>
    /* Status Badge */
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
    }

    .status-aktif { background: #ECFDF5; color: #059669; }
    .status-menunggu { background: #FEF3C7; color: #D97706; }
    .status-expired { background: #FEE2E2; color: #DC2626; }
    .status-ditolak { background: #F1F5F9; color: #64748B; }

    /* Action Buttons */
    .btn-ico {
      width: 28px; height: 28px;
      display: inline-flex; align-items: center; justify-content: center;
      border: none; background: transparent; cursor: pointer;
      border-radius: 6px; transition: all 0.2s;
    }
    .btn-ico:hover { background: #F1F5F9; }
    .btn-ico.view { color: #0077B6; }

    .date-text { font-size: 12px; color: #6B7280; }
  </style>
@endpush

@section('content')
<div class="page-head" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
  <div>
    <div class="page-meta" style="font-size: 14px; color: #6B7280;">{{ now()->translatedFormat('l, d F Y') }}</div>
    <div class="page-title" style="font-size: 24px; font-weight: 700; color: #111827;">Data Permohonan Masuk</div>
  </div>
  <div class="page-actions">
        <a href="{{ route('admin.permohonan.import') }}" class="btn btn-primary" style="background:var(--accent-1, #059669); border-color:var(--accent-1, #059669); margin-right:8px; display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 10px; color: white; text-decoration: none;">
            <i class="ri-file-excel-2-line"></i> Import Data
        </a>
        <a href="{{ route('admin.permohonan.create') }}" class="btn btn-primary" style="background: var(--accent-2, #2563EB); border-color: var(--accent-2, #2563EB); display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 10px; color: white; text-decoration: none;">
            <i class="ri-add-line"></i> Tambah Manual
        </a>
  </div>
</div>

<div class="card" style="border-radius: 16px; border: 1px solid #E2E8F0; box-shadow: 0 1px 2px rgba(0,0,0,0.05); background: white;">
    <div class="card-header bg-white py-3" style="border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center; padding: 20px;">
        <h4 class="card-title mb-0" style="font-size: 18px; font-weight: 700; color: #111827;">Daftar Permohonan</h4>
        
        <form action="{{ route('admin.permohonan.index') }}" method="GET" style="display: flex; gap: 10px;">
            <select name="status" class="form-control" style="width: 150px; border-radius: 8px; border: 1px solid #DBDFE9; padding: 0 10px; height: 38px;" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <div class="input-group" style="display: flex;">
                <input type="text" name="q" class="form-control" placeholder="Cari pemohon..." value="{{ request('q') }}" style="border-radius: 8px 0 0 8px; border: 1px solid #DBDFE9; padding: 0 10px; height: 38px; width: 200px;">
                <button type="submit" class="btn btn-primary" style="border-radius: 0 8px 8px 0; background: #2563EB; color: white; border: none; padding: 0 12px;">
                    <i class="ri-search-line"></i>
                </button>
            </div>
        </form>
    </div>
    
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #F8FAFC;">
                    <tr>
                        <th style="padding: 12px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #64748B; border-bottom: 1px solid #E2E8F0;">No</th>
                        <th style="padding: 12px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #64748B; border-bottom: 1px solid #E2E8F0;">Pengguna</th>
                        <th style="padding: 12px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #64748B; border-bottom: 1px solid #E2E8F0;">Kategori Permohonan</th>
                        <th style="padding: 12px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #64748B; border-bottom: 1px solid #E2E8F0;">Status</th>
                        <th style="padding: 12px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #64748B; border-bottom: 1px solid #E2E8F0;">Tanggal</th>
                        <th style="padding: 12px 20px; text-align: center; font-size: 13px; font-weight: 600; color: #64748B; border-bottom: 1px solid #E2E8F0;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permohonanUsers as $i => $item)
                    @php
                        $statusClass = 'status-aktif';
                        $statusText = ucfirst($item->status);
                        $statusMap = [
                            'pending' => ['text' => 'Menunggu Verifikasi', 'class' => 'status-menunggu'],
                            'diproses' => ['text' => 'Sedang Diproses', 'class' => 'status-menunggu'],
                            'selesai' => ['text' => 'Aktif', 'class' => 'status-aktif'],
                            'ditolak' => ['text' => 'Ditolak', 'class' => 'status-ditolak'],
                            'expired' => ['text' => 'Expired', 'class' => 'status-expired'],
                        ];
                        if (isset($statusMap[$item->status])) {
                            $statusText = $statusMap[$item->status]['text'];
                            $statusClass = $statusMap[$item->status]['class'];
                        }
                    @endphp
                    <tr>
                        <td style="padding: 14px 20px; border-bottom: 1px solid #F1F1F4; color: #334155; font-size: 13px;">{{ $permohonanUsers->firstItem() + $i }}</td>
                        <td style="padding: 14px 20px; border-bottom: 1px solid #F1F1F4; color: #334155; font-size: 13px;">
                            <strong>{{ $item->user->name ?? '-' }}</strong><br>
                            <span style="font-size: 11px; color: #64748B;">{{ $item->user->email ?? '' }}</span>
                        </td>
                        <td style="padding: 14px 20px; border-bottom: 1px solid #F1F1F4; color: #334155; font-size: 13px;">{{ $item->permohonan->nama ?? '-' }}</td>
                        <td style="padding: 14px 20px; border-bottom: 1px solid #F1F1F4; color: #334155; font-size: 13px;">
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td style="padding: 14px 20px; border-bottom: 1px solid #F1F1F4; color: #334155; font-size: 13px;">{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                        <td style="padding: 14px 20px; border-bottom: 1px solid #F1F1F4; text-align: center;">
                             <a href="{{ route('admin.permohonan-user.show', [$item->permohonan_id, $item->id]) }}" class="btn-ico view" title="Lihat Detail">
                                <i class="ri-eye-line"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 40px; text-align: center; color: #64748B;">
                            <i class="ri-inbox-line" style="font-size: 48px; color: #CBD5E1; margin-bottom: 10px; display: block;"></i>
                            Belum ada data permohonan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="card-footer bg-white border-top py-3" style="padding: 14px 20px;">
        {{ $permohonanUsers->links() }}
    </div>
</div>
@endsection