@extends('admin.layouts.app')

@section('title', 'Tambah Unit Kerja - Dinas ESDM')

@section('page-title', 'Tambah Unit Kerja')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Konfigurasi</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Unit Kerja Pengelola</li>
<li class="breadcrumb-item text-muted">Tambah</li>
@endsection

@section('content')
<div class="row col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">Form Tambah Unit Kerja</h3>
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

            <form action="{{ route('admin.unit-kerja.store') }}" method="POST">
                @csrf

                @include('admin.unit_kerja.partials.form-fields')

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.unit-kerja.index') }}" class="btn btn-light">Batal</a>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
