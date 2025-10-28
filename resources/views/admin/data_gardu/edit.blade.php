@extends('admin.layouts.app')

@section('title', 'Ubah Data Gardu')

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">Ubah Data Gardu</div>
    </div>
    <div class="page-actions">
      <a href="{{ route('admin.gardu.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Kembali</a>
    </div>
  </div>

  <div class="card" style="margin-top:18px;">
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('admin.gardu.update', $gardu) }}" method="POST" class="form">
        @method('PUT')
        @include('admin.data_gardu._form', ['gardu' => $gardu])

        <div class="mt-3 d-flex gap-2">
          <button type="submit" class="btn btn-primary"><i class="ri-save-3-line"></i> Perbarui</button>
          <a href="{{ route('admin.gardu.index') }}" class="btn btn-secondary">Batal</a>
        </div>
      </form>
    </div>
  </div>
@endsection
