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

        .input {
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

        .btn-danger {
            background: #ef4444;
            color: #fff;
            border: none;
        }

        .question-item {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .question-number {
            font-weight: 700;
            color: var(--accent-2);
            font-size: 16px;
        }

        .btn-remove-question {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
        }

        .options-container {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #E5E7EB;
        }

        .option-item {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            margin-bottom: 12px;
            padding: 12px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #E5E7EB;
        }

        .option-item input {
            flex: 1;
        }

        .btn-add-option {
            background: #10b981;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            margin-top: 8px;
        }

        .btn-remove-option {
            background: #ef4444;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
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
        <form method="POST" action="{{ route('admin.kategori-permohonan.update', $permohonan) }}" id="formPermohonan">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="label">Nama Permohonan <span style="color:#DC2626">*</span></label>
                <input type="text" name="nama" class="input" value="{{ old('nama', $permohonan->nama) }}"
                    placeholder="Masukkan nama permohonan" required>
            </div>

            <div class="form-group">
                <label class="label">Jenis Permohonan <span style="color:#DC2626">*</span></label>
                <select name="jenis_permohonan" class="input" required>
                    <option value="" disabled
                        {{ old('jenis_permohonan', $permohonan->jenis_permohonan) ? '' : 'selected' }}>Pilih jenis
                        permohonan</option>
                    <option value="desa"
                        {{ old('jenis_permohonan', $permohonan->jenis_permohonan) == 'desa' ? 'selected' : '' }}>Desa
                    </option>
                    <option value="perusahan"
                        {{ old('jenis_permohonan', $permohonan->jenis_permohonan) == 'perusahan' ? 'selected' : '' }}>
                        Perusahaan</option>
                </select>
            </div>

            <div class="form-group">
                <label class="label">Keterangan</label>
                <textarea name="keterangan" class="input" rows="3" placeholder="Masukkan keterangan">{{ old('keterangan', $permohonan->keterangan) }}</textarea>
            </div>

            <hr style="margin: 24px 0; border: none; border-top: 1px solid #E5E7EB;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 700;">Pertanyaan</h3>
            </div>

            <div id="questionsContainer">
                <!-- Questions will be added here dynamically -->
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 16px;">
                <button type="button" class="btn btn-primary" id="btnAddQuestion">
                    <i class="ri-add-line"></i> Tambah Pertanyaan
                </button>
            </div>

            <div style="margin-top: 24px; display: flex; gap: 10px;">
                <a href="{{ route('admin.permohonan.index') }}" class="btn">
                    <i class="ri-arrow-go-back-line"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="ri-save-3-line"></i> Update Permohonan
                </button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
    <script>
        const TIPE_OPTIONS = @json(\App\Models\PermohonanQuestion::TIPE_PERMOHONAN);
        const TIPE_DENGAN_OPTIONS = ['radio', 'checkbox', 'select'];

        const existingQuestions = @json($questionsData ?? []);

        let questionIndex = 0;

        function createQuestionHTML(index, data = {}) {
            const hasOptions = TIPE_DENGAN_OPTIONS.includes(data.tipe || 'text');

            return `
    <div class="question-item" data-question-index="${index}">
      <div class="question-header">
        <span class="question-number">Pertanyaan #${index + 1}</span>
        <button type="button" class="btn-remove-question" onclick="removeQuestion(${index})">
          <i class="ri-delete-bin-line"></i> Hapus
        </button>
      </div>

      ${data.id ? `<input type="hidden" name="questions[${index}][id]" value="${data.id}">` : ''}

      <div class="form-group">
        <label class="label">Urutan <span style="color:#DC2626">*</span></label>
        <input type="number" name="questions[${index}][urutan]" class="input" value="${data.urutan || index + 1}" min="1" required>
      </div>

      <div class="form-group">
        <label class="label">Pertanyaan <span style="color:#DC2626">*</span></label>
        <textarea name="questions[${index}][pertanyaan]" class="input" rows="2" placeholder="Masukkan pertanyaan" required>${data.pertanyaan || ''}</textarea>
      </div>

      <div class="form-group">
        <label class="label">Tipe Pertanyaan <span style="color:#DC2626">*</span></label>
        <select name="questions[${index}][tipe]" class="input" onchange="handleTipeChange(${index}, this.value)" required>
          ${Object.entries(TIPE_OPTIONS).map(([key, label]) =>
            `<option value="${key}" ${data.tipe === key ? 'selected' : ''}>${label}</option>`
          ).join('')}
        </select>
      </div>

      <div class="form-group">
        <div class="checkbox-wrapper">
          <input type="checkbox" name="questions[${index}][wajib]" value="1" id="wajib_${index}" ${data.wajib ? 'checked' : ''}>
          <label for="wajib_${index}">Wajib diisi</label>
        </div>
      </div>

      <div class="options-container" id="options_${index}" style="display: ${hasOptions ? 'block' : 'none'};">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
          <strong>Opsi Jawaban</strong>
          <button type="button" class="btn-add-option" onclick="addOption(${index})">
            <i class="ri-add-line"></i> Tambah Opsi
          </button>
        </div>
        <div id="options_list_${index}">
          ${hasOptions && data.options ? data.options.map((opt, optIdx) =>
            createOptionHTML(index, optIdx, opt)
          ).join('') : ''}
        </div>
      </div>
    </div>
  `;
        }

        function createOptionHTML(questionIndex, optionIndex, data = {}) {
            return `
    <div class="option-item" data-option-index="${optionIndex}">
      ${data.id ? `<input type="hidden" name="questions[${questionIndex}][options][${optionIndex}][id]" value="${data.id}">` : ''}
      <input type="text" name="questions[${questionIndex}][options][${optionIndex}][opsi]"
             class="input" placeholder="Masukkan opsi" value="${data.opsi || ''}" required>
      <input type="text" name="questions[${questionIndex}][options][${optionIndex}][keterangan]"
             class="input" placeholder="Keterangan (opsional)" value="${data.keterangan || ''}" style="flex: 0.7;">
      <div class="checkbox-wrapper">
        <input type="checkbox" name="questions[${questionIndex}][options][${optionIndex}][wajib]"
               value="1" id="opt_wajib_${questionIndex}_${optionIndex}" ${data.wajib ? 'checked' : ''}>
        <label for="opt_wajib_${questionIndex}_${optionIndex}">Wajib</label>
      </div>
      <button type="button" class="btn-remove-option" onclick="removeOption(${questionIndex}, ${optionIndex})">
        <i class="ri-delete-bin-line"></i>
      </button>
    </div>
  `;
        }

        function addQuestion() {
            const container = document.getElementById('questionsContainer');
            container.insertAdjacentHTML('beforeend', createQuestionHTML(questionIndex));
            questionIndex++;
            updateQuestionNumbers();
        }

        function removeQuestion(index) {
            const questionItem = document.querySelector(`[data-question-index="${index}"]`);
            if (questionItem) {
                questionItem.remove();
                updateQuestionNumbers();
            }
        }

        function updateQuestionNumbers() {
            const questions = document.querySelectorAll('.question-item');
            questions.forEach((q, idx) => {
                const numberSpan = q.querySelector('.question-number');
                if (numberSpan) {
                    numberSpan.textContent = `Pertanyaan #${idx + 1}`;
                }
            });
        }

        function handleTipeChange(questionIndex, tipe) {
            const optionsContainer = document.getElementById(`options_${questionIndex}`);
            const optionsList = document.getElementById(`options_list_${questionIndex}`);

            if (TIPE_DENGAN_OPTIONS.includes(tipe)) {
                optionsContainer.style.display = 'block';
                if (optionsList.children.length === 0) {
                    addOption(questionIndex);
                }
            } else {
                optionsContainer.style.display = 'none';
                optionsList.innerHTML = '';
            }
        }

        function addOption(questionIndex) {
            const optionsList = document.getElementById(`options_list_${questionIndex}`);
            const optionIndex = optionsList.children.length;
            optionsList.insertAdjacentHTML('beforeend', createOptionHTML(questionIndex, optionIndex));
        }

        function removeOption(questionIndex, optionIndex) {
            const optionItem = document.querySelector(
            `#options_list_${questionIndex} [data-option-index="${optionIndex}"]`);
            if (optionItem) {
                optionItem.remove();
                reindexOptions(questionIndex);
            }
        }

        function reindexOptions(questionIndex) {
            const optionsList = document.getElementById(`options_list_${questionIndex}`);
            const options = optionsList.querySelectorAll('.option-item');
            options.forEach((opt, idx) => {
                opt.setAttribute('data-option-index', idx);
                const inputs = opt.querySelectorAll('input, label');
                inputs.forEach(input => {
                    if (input.name) {
                        input.name = input.name.replace(/\[options\]\[\d+\]/, `[options][${idx}]`);
                    }
                    if (input.id) {
                        input.id = input.id.replace(/opt_wajib_\d+_\d+/,
                            `opt_wajib_${questionIndex}_${idx}`);
                    }
                });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btnAddQuestion').addEventListener('click', addQuestion);

            // Load existing questions
            if (existingQuestions && existingQuestions.length > 0) {
                existingQuestions.forEach((q, idx) => {
                    const container = document.getElementById('questionsContainer');
                    container.insertAdjacentHTML('beforeend', createQuestionHTML(questionIndex, q));
                    questionIndex++;
                });
            } else {
                // Add first question by default if no existing questions
                addQuestion();
            }

            updateQuestionNumbers();
        });
    </script>
@endpush
