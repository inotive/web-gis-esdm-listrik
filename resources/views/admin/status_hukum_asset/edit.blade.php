@extends('admin.layouts.app')

@section('title', 'Edit Status Hukum Asset - Dinas ESDM')

@section('page-title', 'Edit Status Hukum Asset')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Konfigurasi</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Status Hukum Asset</li>
<li class="breadcrumb-item text-muted">Edit</li>
@endsection

@section('content')
<div class="row col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Form Edit Status Hukum Asset</h3>
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

            <form action="{{ route('admin.status-hukum-asset.update', $statusHukumAsset) }}" method="POST">
                @csrf
                @method('PUT')

                @include('admin.status_hukum_asset.partials.form-fields', ['statusHukumAsset' => $statusHukumAsset])

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.status-hukum-asset.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
