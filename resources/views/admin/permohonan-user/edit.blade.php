@extends('admin.layouts.app')

@section('title', 'Edit Permohonan')

@push('styles')
    <style>
        .card {
            border: 1px solid var(--line);
            border-radius: 16px;
            box-shadow: var(--shadow-1);
            padding: 24px;
            margin-top: 18px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .label {
            display: block;
            font-size: 14px;
            color: #374151;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .label-required::after {
            content: ' *';
            color: #ef4444;
        }

        .input,
        .select {
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border: 1px solid #E2E8F0;
            border-radius: 10px;
            background: #FCFCFD;
            outline: none;
            font: inherit;
            color: #111827;
        }

        textarea.input {
            min-height: 80px;
            padding: 12px;
            resize: vertical;
        }

        .question-item {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .question-number {
            font-weight: 700;
            color: var(--accent-2);
            font-size: 16px;
            margin-bottom: 12px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #E5E7EB;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 10px;
            border: 1px solid var(--line);
            cursor: pointer;
            background: #fff;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-primary {
            background: var(--accent-2);
            color: #fff;
            border: none;
        }

        .btn-secondary {
            background: #6b7280;
            color: #fff;
            border: none;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
        }

        .option-item {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        input[type="checkbox"],
        input[type="radio"] {
            width: 18px;
            height: 18px;
        }

        .status-info {
            background: #F0F9FF;
            border: 1px solid #BAE6FD;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
        }

        .status-info strong {
            color: #0369A1;
        }
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Edit Permohonan</div>
        </div>
    </div>

    <section class="card">
        <div class="status-info">
            <strong>Status:</strong>
            @if ($permohonanUser->status === 'pending')
                Pending
            @elseif($permohonanUser->status === 'proses')
                Proses
            @elseif($permohonanUser->status === 'selesai')
                Selesai
            @elseif($permohonanUser->status === 'ditolak')
                Ditolak
            @else
                {{ $permohonanUser->status }}
            @endif
        </div>

        <form method="POST"
            action="{{ route('admin.permohonan-user.update', [$permohonanUser->permohonan_id, $permohonanUser]) }}"
            id="permohonanForm">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="label">Jenis Permohonan</label>
                <input type="text" class="input" value="{{ $permohonanUser->permohonan->nama }}" readonly
                    style="background: #F3F4F6;">
            </div>

            <div id="questionsContainer">
                @foreach ($permohonanUser->permohonan->questions as $index => $question)
                    <div class="question-item">
                        <div class="question-number">
                            {{ $index + 1 }}. {{ $question->pertanyaan }}
                            @if ($question->wajib)
                                <span style="color:#ef4444">*</span>
                            @endif
                        </div>

                        @php
                            $questionId = $question->id;
                            $answer = $jawaban[$questionId] ?? '';
                        @endphp

                        @switch($question->tipe)
                            @case('text')
                                <input type="text" name="jawaban[{{ $questionId }}]" class="input" value="{{ $answer }}"
                                    {{ $question->wajib ? 'required' : '' }} />
                            @break

                            @case('textarea')
                                <textarea name="jawaban[{{ $questionId }}]" class="input" rows="4" {{ $question->wajib ? 'required' : '' }}>{{ $answer }}</textarea>
                            @break

                            @case('number')
                                <input type="number" name="jawaban[{{ $questionId }}]" class="input"
                                    value="{{ $answer }}" {{ $question->wajib ? 'required' : '' }} />
                            @break

                            @case('date')
                                <input type="date" name="jawaban[{{ $questionId }}]" class="input"
                                    value="{{ $answer }}" {{ $question->wajib ? 'required' : '' }} />
                            @break

                            @case('file')
                                <input type="file" name="jawaban[{{ $questionId }}]" class="input"
                                    {{ $question->wajib ? 'required' : '' }} />
                                @if ($answer)
                                    <div style="margin-top: 8px; font-size: 12px; color: #64748B;">
                                        File saat ini: {{ $answer }}
                                    </div>
                                @endif
                            @break

                            @case('file_multiple')
                                <input type="file" name="jawaban[{{ $questionId }}][]" class="input"
                                    {{ $question->wajib ? 'required' : '' }} multiple />
                                @if ($answer)
                                    <div style="margin-top: 8px; font-size: 12px; color: #64748B;">
                                        File saat ini: {{ $answer }}
                                    </div>
                                @endif
                            @break

                            @case('radio')
                                @if ($question->options && $question->options->count() > 0)
                                    @foreach ($question->options as $optIndex => $option)
                                        <div class="option-item">
                                            <input type="radio" name="jawaban[{{ $questionId }}]"
                                                id="q{{ $questionId }}_opt{{ $optIndex }}" value="{{ $option->id }}"
                                                {{ $answer == $option->id ? 'checked' : '' }}
                                                {{ $question->wajib ? 'required' : '' }} />
                                            <label for="q{{ $questionId }}_opt{{ $optIndex }}">{{ $option->opsi }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            @break

                            @case('checkbox')
                                @if ($question->options && $question->options->count() > 0)
                                    @php
                                        $answerArray = is_array($answer) ? $answer : [];
                                        // Convert all to strings for comparison
                                        $answerArray = array_map('strval', $answerArray);
                                    @endphp
                                    @foreach ($question->options as $optIndex => $option)
                                        <div class="option-item">
                                            <input type="checkbox" name="jawaban[{{ $questionId }}][]"
                                                id="q{{ $questionId }}_opt{{ $optIndex }}" value="{{ $option->id }}"
                                                {{ in_array((string) $option->id, $answerArray) ? 'checked' : '' }} />
                                            <label for="q{{ $questionId }}_opt{{ $optIndex }}">{{ $option->opsi }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            @break

                            @case('select')
                                <select name="jawaban[{{ $questionId }}]" class="select"
                                    {{ $question->wajib ? 'required' : '' }}>
                                    <option value="">-- Pilih --</option>
                                    @if ($question->options && $question->options->count() > 0)
                                        @foreach ($question->options as $option)
                                            <option value="{{ $option->id }}" {{ $answer == $option->id ? 'selected' : '' }}>
                                                {{ $option->opsi }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            @break
                        @endswitch

                        @error('jawaban.' . $questionId)
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
            </div>

            <div class="form-group">
                <label class="label" for="status">Status</label>
                <select name="status" id="status" class="select">
                    <option value="pending" {{ $permohonanUser->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ $permohonanUser->status === 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ $permohonanUser->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ $permohonanUser->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                @error('status')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.permohonan.index', ['tab' => 'permohonan']) }}"
                    class="btn btn-secondary">
                    <i class="ri-close-line"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-line"></i>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                });
            @endif

            @if ($errors->any())
                const firstError = document.querySelector('.error-message');
                if (firstError) {
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            @endif
        });
    </script>
@endpush
