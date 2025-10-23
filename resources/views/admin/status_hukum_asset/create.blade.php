@extends('admin.layouts.app')

@section('title', 'Tambah Status Hukum Asset - Dinas ESDM')

@section('page-title', 'Tambah Status Hukum Asset')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Konfigurasi</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Status Hukum Asset</li>
<li class="breadcrumb-item text-muted">Tambah</li>
@endsection

@section('content')
<div class="row col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Form Tambah Status Hukum Asset</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.status-hukum-asset.store') }}" method="POST">
                @csrf

                @include('admin.status_hukum_asset.partials.form-fields')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.status-hukum-asset.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
