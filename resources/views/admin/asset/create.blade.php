@extends('admin.layouts.app')

@section('title', 'Tambah Data Asset - Dinas ESDM')

@section('page-title', 'Tambah Data Asset')

@section('breadcrumb')
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Konfigurasi</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">
    <a href="{{ route('admin.asset.index') }}" class="text-muted text-hover-primary">Data Asset</a>
</li>
<li class="breadcrumb-item">
    <span class="bullet bg-gray-500 w-5px h-2px"></span>
</li>
<li class="breadcrumb-item text-muted">Tambah</li>
@endsection

{{-- ✅ LEAFLET CSS & JS --}}
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css"/>
<style>
    .leaflet-draw-toolbar a {
        background-image: url('https://unpkg.com/leaflet-draw@1.0.4/dist/images/spritesheet.png');
    }
    .leaflet-retina .leaflet-draw-toolbar a {
        background-image: url('https://unpkg.com/leaflet-draw@1.0.4/dist/images/spritesheet-2x.png');
    }
    .leaflet-draw-actions a {
        background-image: url('https://unpkg.com/leaflet-draw@1.0.4/dist/images/spritesheet.png');
    }
    .leaflet-retina .leaflet-draw-actions a {
        background-image: url('https://unpkg.com/leaflet-draw@1.0.4/dist/images/spritesheet-2x.png');
    }
</style>
@endpush

@section('content')
<div class="row col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column mb-0">
                <span class="card-label fw-bold fs-3">Form Tambah Data Asset</span>
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

            <form action="{{ route('admin.asset.store') }}" method="POST" enctype="multipart/form-data" data-region-form="asset">
                @csrf

                @include('admin.asset.partials.form-fields', [
                    'asset' => null,
                    'kategoriList' => $kategoriList,
                    'unitKerjaList' => $unitKerjaList,
                    'statusList' => $statusList,
                    'provinsiList' => $provinsiList,
                    'kabupatenList' => $kabupatenList,
                    'kecamatanList' => $kecamatanList,
                    'kelurahanList' => $kelurahanList,
                ])

                <div class="row mt-8">
                    <div class="col-12 d-flex justify-content-between pt-5">
                        <a href="{{ route('admin.asset.index') }}" class="btn btn-light">
                            <i class="fa-solid fa-arrow-left fs-3 me-2"></i> Batal

                            Batal
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ki-duotone ki-check fs-3 me-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            Simpan Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- ✅ LEAFLET & DRAW PLUGIN --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

{{-- Region cascade script --}}
@include('admin.asset.partials.region-script', [
    'kabupatenList' => $kabupatenList,
    'kecamatanList' => $kecamatanList,
    'kelurahanList' => $kelurahanList,
])

{{-- ✅ Leaflet Map script --}}
@include('admin.asset.partials.leaflet-map-script')
@endpush