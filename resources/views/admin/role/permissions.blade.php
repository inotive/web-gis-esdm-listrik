{{-- resources/views/admin/role/permissions.blade.php --}}

@extends('admin.layouts.app')

@section('title', $title . ' - BPKAD')
@section('page-title', $title)

@section('breadcrumb')
<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">Hak Akses</li>
<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.hak-akses.role.index') }}">Role</a>
</li>
<li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
<li class="breadcrumb-item text-muted">Permission</li>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    .permission-card {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        background: #fff;
        transition: all 0.3s ease;
    }
    .permission-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .permission-group-title {
        font-size: 16px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3b82f6;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .permission-item {
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 8px;
        background: #f9fafb;
        transition: all 0.2s;
    }
    .permission-item:hover {
        background: #f3f4f6;
    }
    .form-check-input {
        width: 1.2em;
        height: 1.2em;
        cursor: pointer;
    }
    .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    .form-check-label {
        cursor: pointer;
        user-select: none;
    }
    .select-all-btn {
        font-size: 12px;
        padding: 4px 12px;
        margin-left: auto;
    }
    .debug-info {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 10px 15px;
        margin-bottom: 20px;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        {{-- ALERT ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fs-2x text-danger me-4"></i>
                    <div>
                        <h4 class="mb-1 text-danger">Error Validasi</h4>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('pesan'))
            <div class="alert alert-{{ session('alert') }} alert-dismissible fade show" role="alert">
                {{ session('pesan') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="card-title fw-bold mb-0">
                        <i class="fas fa-key text-primary me-2"></i>
                        Kelola Permission: {{ $role->name }}
                    </h3>
                    <small class="text-muted">Pilih permission yang dapat diakses oleh role ini</small>
                </div>
                <a href="{{ route('admin.hak-akses.role.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <div class="card-body">
                {{-- DEBUG INFO (uncomment untuk debugging) --}}
                

                <form action="{{ route('admin.hak-akses.role.permissions.update', $role) }}" 
                      method="POST" 
                      id="permissionForm">
                    @csrf
                    @method('PUT')

                    

                    <div class="row">
                        @foreach($permissions as $group => $groupPermissions)
                        {{-- HIDE SPECIFIC PERMISSIONS --}}
                        @if(in_array($group, ['Data Infrastruktur', 'Data Jalan']))
                            @continue
                        @endif

                        <div class="col-md-6">
                            <div class="permission-card">
                                <div class="permission-group-title">
                                    <i class="fas fa-folder-open"></i>
                                    <span>{{ $group }}</span>
                                    <button type="button" 
                                            class="btn btn-sm btn-light select-all-btn" 
                                            onclick="toggleGroupPermissions(this, '{{ $group }}')">
                                        <i class="fas fa-check-double me-1"></i>Pilih Semua
                                    </button>
                                </div>
                                
                                @foreach($groupPermissions as $permission)
                                <div class="permission-item">
                                    <div class="form-check">
                                        {{-- PASTIKAN VALUE ADALAH INTEGER --}}
                                        <input class="form-check-input permission-checkbox" 
                                               type="checkbox" 
                                               name="permissions[]" 
                                               value="{{ (int)$permission->id }}"
                                               id="permission-{{ $permission->id }}"
                                               data-group="{{ $group }}"
                                               data-permission-name="{{ $permission->name }}"
                                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                               onchange="updateCount()">
                                        <label class="form-check-label fw-semibold w-100" 
                                               for="permission-{{ $permission->id }}">
                                            {{ $permission->display_name }}
                                            <small class="text-muted d-block">
                                                {{ $permission->name }} 
                                                @if(config('app.debug'))
                                                    <span class="badge badge-light-info">ID: {{ $permission->id }}</span>
                                                @endif
                                            </small>
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('admin.hak-akses.role.index') }}" class="btn btn-light">
                            <i class="fas fa-times me-2"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Simpan Permission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle all permissions in a group
function toggleGroupPermissions(btn, groupName) {
    const checkboxes = document.querySelectorAll(`input[data-group="${groupName}"]`);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
    
    btn.innerHTML = allChecked 
        ? '<i class="fas fa-check-double me-1"></i>Pilih Semua' 
        : '<i class="fas fa-times me-1"></i>Batal Pilih';
    
    updateCount();
}

// Update active permission count
function updateCount() {
    const checkedCount = document.querySelectorAll('.permission-checkbox:checked').length;
    document.getElementById('activeCount').textContent = checkedCount;
}

// VALIDASI SEBELUM SUBMIT
document.getElementById('permissionForm').addEventListener('submit', function(e) {
    const checkedBoxes = document.querySelectorAll('.permission-checkbox:checked');
    const permissionIds = Array.from(checkedBoxes).map(cb => parseInt(cb.value));
    
    // Debug log (hapus di production)
    console.log('Submitting permissions:', permissionIds);
    console.log('Total checked:', permissionIds.length);
    
    // Validasi apakah ada ID yang invalid
    const invalidIds = permissionIds.filter(id => isNaN(id) || id <= 0);
    if (invalidIds.length > 0) {
        e.preventDefault();
        alert('Error: Ditemukan ID permission yang tidak valid: ' + invalidIds.join(', '));
        return false;
    }
    
    // Disable button untuk prevent double submit
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
});

// Initial count update
document.addEventListener('DOMContentLoaded', updateCount);

// Success/Error notification
@if (Session::has('pesan'))
    toastr.{{ Session::get('alert') }}("{{ Session::get('pesan') }}")
@endif
</script>
@endpush