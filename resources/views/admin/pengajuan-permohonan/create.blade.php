@extends('admin.layouts.app')

@section('title', 'Buat Permohonan Baru')

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

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-primary:disabled {
            background: #9CA3AF;
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
    </style>
@endpush

@section('content')
    <div class="page-head">
        <div>
            <div class="page-meta">{{ now()->translatedFormat('l, d F Y') }}</div>
            <div class="page-title">Buat Permohonan Baru</div>
        </div>
    </div>

    <section class="card">
        <form method="POST" action="{{ route('admin.pengajuan-permohonan.store') }}" id="permohonanForm"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="permohonan_id" value="{{ $permohonan->id }}">

            <div class="form-group">
                <label class="label">Jenis Permohonan</label>
                <input type="text" class="input" value="{{ $permohonan->nama }}" readonly style="background: #F3F4F6;">
            </div>

            <div id="questionsContainer">
                @foreach ($permohonan->questions as $index => $question)
                    <div class="question-item">
                        <div class="question-number">
                            {{ $index + 1 }}. {{ $question->pertanyaan }}
                            @if ($question->wajib)
                                <span style="color:#ef4444">*</span>
                            @endif
                        </div>

                        @php
                            $questionId = $question->id;
                            $oldValue = old('jawaban.' . $questionId, '');
                        @endphp

                        @switch($question->tipe)
                            @case('text')
                                <input type="text" name="jawaban[{{ $questionId }}]" class="input" value="{{ $oldValue }}"
                                    {{ $question->wajib ? 'required' : '' }} />
                            @break

                            @case('textarea')
                                <textarea name="jawaban[{{ $questionId }}]" class="input" rows="4" {{ $question->wajib ? 'required' : '' }}>{{ $oldValue }}</textarea>
                            @break

                            @case('number')
                                <input type="number" name="jawaban[{{ $questionId }}]" class="input"
                                    value="{{ $oldValue }}" {{ $question->wajib ? 'required' : '' }} />
                            @break

                            @case('date')
                                <input type="date" name="jawaban[{{ $questionId }}]" class="input"
                                    value="{{ $oldValue }}" {{ $question->wajib ? 'required' : '' }} />
                            @break

                            @case('file')
                                <input type="file" name="jawaban[{{ $questionId }}]" class="input"
                                    {{ $question->wajib ? 'required' : '' }} />
                                @if ($oldValue)
                                    <div style="margin-top: 8px; font-size: 12px; color: #64748B;">
                                        File saat ini: {{ $oldValue }}
                                    </div>
                                @endif
                            @break

                            @case('file_multiple')
                                <input type="file" name="jawaban[{{ $questionId }}][]" class="input"
                                    {{ $question->wajib ? 'required' : '' }} multiple />
                                @if ($oldValue)
                                    <div style="margin-top: 8px; font-size: 12px; color: #64748B;">
                                        File saat ini: {{ $oldValue }}
                                    </div>
                                @endif
                            @break

                            @case('radio')
                                @if ($question->options && $question->options->count() > 0)
                                    @foreach ($question->options as $optIndex => $option)
                                        <div class="option-item">
                                            <input type="radio" name="jawaban[{{ $questionId }}]"
                                                id="q{{ $questionId }}_opt{{ $optIndex }}" value="{{ $option->id }}"
                                                {{ $oldValue == $option->id ? 'checked' : '' }}
                                                {{ $question->wajib ? 'required' : '' }} />
                                            <label for="q{{ $questionId }}_opt{{ $optIndex }}">{{ $option->opsi }}</label>
                                        </div>
                                    @endforeach
                                @endif
                            @break

                            @case('checkbox')
                                @if ($question->options && $question->options->count() > 0)
                                    @php
                                        $oldValues = is_array($oldValue)
                                            ? $oldValue
                                            : (is_string($oldValue) && $oldValue
                                                ? [$oldValue]
                                                : []);
                                        $oldValues = array_map('strval', $oldValues);
                                    @endphp
                                    @foreach ($question->options as $optIndex => $option)
                                        <div class="option-item">
                                            <input type="checkbox" name="jawaban[{{ $questionId }}][]"
                                                id="q{{ $questionId }}_opt{{ $optIndex }}" value="{{ $option->id }}"
                                                {{ in_array((string) $option->id, $oldValues) ? 'checked' : '' }} />
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
                                            <option value="{{ $option->id }}" {{ $oldValue == $option->id ? 'selected' : '' }}>
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

            <div class="form-actions">
                <a href="{{ route('admin.pengajuan-permohonan.index') }}" class="btn btn-secondary">
                    <i class="ri-close-line"></i>
                    Batal
                </a>
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="ri-save-line"></i>
                    <span id="submitBtnText">Simpan Permohonan</span>
                </button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('permohonanForm');
            const submitBtn = document.getElementById('submitBtn');
            const submitBtnText = document.getElementById('submitBtnText');
            const submitBtnIcon = submitBtn.querySelector('i');

            // Handle form submission with confirmation
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Check if form is valid
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                // Show confirmation dialog
                Swal.fire({
                    title: 'Konfirmasi Pengajuan',
                    html: `
                        <div style="text-align: left; padding: 10px;">
                            <p style="margin-bottom: 12px;">Apakah Anda yakin ingin mengajukan permohonan ini?</p>
                            <div style="background: #F3F4F6; padding: 12px; border-radius: 8px; font-size: 13px;">
                                <strong>Jenis Permohonan:</strong><br>
                                {{ $permohonan->nama }}
                            </div>
                            <p style="margin-top: 12px; font-size: 12px; color: #64748B;">
                                <i class="ri-information-line"></i>
                                Setelah diajukan, permohonan akan diproses oleh admin.
                            </p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#6366F1',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="ri-check-line"></i> Ya, Ajukan!',
                    cancelButtonText: '<i class="ri-close-line"></i> Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal-wide'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Disable button and show loading state
                        submitBtn.disabled = true;
                        submitBtnIcon.className = 'ri-loader-4-line';
                        submitBtnIcon.style.animation = 'spin 1s linear infinite';
                        submitBtnText.textContent = 'Menyimpan...';

                        // Add CSS for spin animation if not exists
                        if (!document.getElementById('spinAnimation')) {
                            const style = document.createElement('style');
                            style.id = 'spinAnimation';
                            style.textContent = `
                                @keyframes spin {
                                    from { transform: rotate(0deg); }
                                    to { transform: rotate(360deg); }
                                }
                            `;
                            document.head.appendChild(style);
                        }

                        // Submit the form
                        form.submit();
                    }
                });
            });

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
