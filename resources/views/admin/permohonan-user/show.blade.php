@extends('admin.layouts.app')

@section('title', 'Dashboard ESDM - Detail Permohonan')

@push('styles')
<style>
  .card {
    border: 1px solid #F1F1F4;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    padding: 24px;
    margin-top: 18px;
    background: #fff;
  }

  .card-header {
    padding-bottom: 16px;
    border-bottom: 1px solid #F1F1F4;
    margin-bottom: 24px;
  }

  .card-title {
    font-size: 20px;
    font-weight: 800;
    color: #111827;
    margin: 0;
  }

  .info-row {
    display: flex;
    padding: 12px 0;
    border-bottom: 1px solid #F1F1F4;
  }

  .info-row:last-child {
    border-bottom: none;
  }

  .info-label {
    width: 200px;
    font-weight: 600;
    color: #64748B;
    font-size: 14px;
  }

  .info-value {
    flex: 1;
    color: #111827;
    font-size: 14px;
  }

  .badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
  }

  .badge-selesai {
    background: #D1FAE5;
    color: #065F46;
  }

  .question-item {
    background: #FCFCFD;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 16px;
  }

  .question-number {
    font-weight: 700;
    font-size: 15px;
    color: #111827;
    margin-bottom: 12px;
  }

  .answer-value {
    color: #374151;
    font-size: 14px;
    line-height: 1.6;
  }

  .answer-value ul {
    margin: 8px 0;
    padding-left: 20px;
  }

  .document-item {
    background: #FCFCFD;
    border: 1px solid #E2E8F0;
    border-radius: 10px;
    padding: 16px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .document-info {
    flex: 1;
  }

  .document-name {
    font-weight: 600;
    color: #111827;
    font-size: 14px;
    margin-bottom: 4px;
  }

  .document-meta {
    font-size: 12px;
    color: #64748B;
  }

  .btn-download {
    padding: 8px 16px;
    background: #3B82F6;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.18s ease;
  }

  .btn-download:hover {
    background: #2563EB;
  }

  .btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    border-radius: 8px;
    border: 1px solid #E2E8F0;
    background: #fff;
    color: #64748B;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.18s ease;
  }

  .btn-back:hover {
    background: #F8FAFC;
    border-color: #CBD5E1;
  }

  .section-title {
    font-size: 18px;
    font-weight: 700;
    color: #111827;
    margin: 24px 0 16px 0;
    padding-bottom: 12px;
    border-bottom: 2px solid #F1F1F4;
  }

  .empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #64748B;
  }

  .empty-state svg {
    width: 64px;
    height: 64px;
    margin-bottom: 16px;
    opacity: 0.5;
  }
</style>
@endpush

@section('content')
  <div class="page-head">
    <div>
      <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
      <div class="page-title">
        Detail Permohonan
        <span style="font-size: 14px; font-weight: normal; color: #64748B;"> - {{ $permohonanUser->permohonan->nama }}</span>
      </div>
    </div>
    <div class="page-actions">
      <a href="{{ route('admin.permohonan-user.index', $permohonanId) }}" class="btn-back">
        <i class="ri-arrow-left-line"></i>
        Kembali
      </a>
    </div>
  </div>

  <section class="card">
    <div class="card-header">
      <h2 class="card-title">Informasi Permohonan</h2>
    </div>

    <div class="info-row">
      <div class="info-label">Nama Permohonan</div>
      <div class="info-value"><strong>{{ $permohonanUser->permohonan->nama }}</strong></div>
    </div>

    <div class="info-row">
      <div class="info-label">Status</div>
      <div class="info-value">
        <span class="badge badge-selesai">Selesai</span>
      </div>
    </div>

    <div class="info-row">
      <div class="info-label">Pengaju</div>
      <div class="info-value">{{ $permohonanUser->user->name }}</div>
    </div>

    <div class="info-row">
      <div class="info-label">Tanggal Dibuat</div>
      <div class="info-value">{{ $permohonanUser->created_at->translatedFormat('d F Y, H:i') }}</div>
    </div>

    @if($permohonanUser->keterangan)
    <div class="info-row">
      <div class="info-label">Keterangan</div>
      <div class="info-value">{{ $permohonanUser->keterangan }}</div>
    </div>
    @endif
  </section>

  <section class="card">
    <h2 class="section-title">Detail Pengajuan</h2>

    @foreach ($permohonanUser->permohonan->questions as $index => $question)
      @php
        $questionId = $question->id;
        $answer = $jawaban[$questionId] ?? null;
      @endphp

      <div class="question-item">
        <div class="question-number">
          {{ $index + 1 }}. {{ $question->pertanyaan }}
          @if ($question->wajib)
            <span style="color:#ef4444">*</span>
          @endif
        </div>

        <div class="answer-value">
          @if($answer === null || $answer === '')
            <span style="color: #94A3B8; font-style: italic;">Tidak diisi</span>
          @else
            @switch($question->tipe)
              @case('text')
              @case('textarea')
              @case('number')
              @case('date')
                {{ $answer }}
              @break

              @case('radio')
                @php
                  $option = $question->options->firstWhere('id', $answer);
                @endphp
                {{ $option ? $option->opsi : $answer }}
              @break

              @case('checkbox')
                @php
                  $answerArray = is_array($answer) ? $answer : [$answer];
                  $selectedOptions = $question->options->whereIn('id', $answerArray);
                @endphp
                @if($selectedOptions->count() > 0)
                  <ul>
                    @foreach($selectedOptions as $option)
                      <li>{{ $option->opsi }}</li>
                    @endforeach
                  </ul>
                @else
                  {{ implode(', ', $answerArray) }}
                @endif
              @break

              @default
                {{ is_array($answer) ? implode(', ', $answer) : $answer }}
            @endswitch
          @endif
        </div>
      </div>
    @endforeach
  </section>

  @if($permohonanUser->documents && $permohonanUser->documents->count() > 0)
  <section class="card">
    <h2 class="section-title">Dokumen Persetujuan</h2>

    @foreach($permohonanUser->documents as $document)
      <div class="document-item">
        <div class="document-info">
          <div class="document-name">{{ $document->nama }}</div>
          <div class="document-meta">
            @if($document->masa_berlaku)
              Masa Berlaku: {{ $document->masa_berlaku->translatedFormat('d F Y') }}
              @if($document->masa_berlaku->isPast())
                <span style="color: #EF4444; font-weight: 600;">(Kedaluwarsa)</span>
              @elseif($document->masa_berlaku->isToday())
                <span style="color: #F59E0B; font-weight: 600;">(Berakhir Hari Ini)</span>
              @elseif($document->masa_berlaku->diffInDays(now()) <= 30)
                <span style="color: #F59E0B; font-weight: 600;">(Akan Berakhir dalam {{ $document->masa_berlaku->diffInDays(now()) }} hari)</span>
              @endif
            @else
              Tidak ada masa berlaku
            @endif
          </div>
        </div>
        <a href="{{ asset('storage/permohonan-documents/' . $document->path) }}" 
           target="_blank" 
           class="btn-download"
           download>
          <i class="ri-download-line"></i>
          Download
        </a>
      </div>
    @endforeach
  </section>
  @else
  <section class="card">
    <div class="empty-state">
      <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14 2H6C5.46957 2 4.96086 2.21071 4.58579 2.58579C4.21071 2.96086 4 3.46957 4 4V20C4 20.5304 4.21071 21.0391 4.58579 21.4142C4.96086 21.7893 5.46957 22 6 22H18C18.5304 22 19.0391 21.7893 19.4142 21.4142C19.7893 21.0391 20 20.5304 20 20V8L14 2Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M14 2V8H20" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <p>Tidak ada dokumen persetujuan</p>
    </div>
  </section>
  @endif
@endsection

