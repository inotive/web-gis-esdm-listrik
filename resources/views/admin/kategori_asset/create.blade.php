@extends('admin.layouts.app')

@section('title', 'Tambah Kategori Asset - Dinas ESDM')

@section('page-title', 'Tambah Kategori Asset')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Konfigurasi</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.kategori-asset.index') }}" class="text-muted text-hover-primary">Kategori Asset</a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Tambah</li>
@endsection

@section('content')
<div class="row col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column mb-0">
                <span class="card-label fw-bold fs-3">Form Tambah Kategori Asset</span>
            </h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-6">
                    <h4 class="mb-3">Terjadi kesalahan</h4>
                    <ul class="mb-0 ps-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.kategori-asset.store') }}" method="POST" class="row g-4">
                @csrf

                @include('admin.kategori_asset.partials.form-fields')

                <div class="col-12 d-flex justify-content-between pt-5">
                    <a href="{{ route('admin.kategori-asset.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
