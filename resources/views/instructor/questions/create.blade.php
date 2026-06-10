@extends('layouts.dashboard')

@section('title', __('app.create_question'))

@push('styles')
<style>
    .question-page-title{
        font-size: 2rem;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: .2px;
    }

    .options-card{
        background: #f8fafc;
        border: 1px solid rgba(148,163,184,.28);
        border-radius: 18px;
        padding: 1rem;
        width: 100%;
        overflow: hidden;
    }

    .option-row{
        transition: all .2s ease;
    }

    .option-number{
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: rgba(59,130,246,.12);
        color: #2563eb;
        font-weight: 900;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .correct-box{
        background: #ffffff;
        border: 1px solid rgba(148,163,184,.28);
        border-radius: 14px;
        padding: .75rem .9rem;
        min-height: 48px;
        display: flex;
        align-items: center;
        width: 100%;
    }

    .type-note{
        font-weight: 800;
        color: #475569;
    }

    select.form-select{
        max-width: 100%;
        direction: rtl;
        text-align: right;
    }

    .form-select option{
        direction: ltr;
        text-align: left;
    }

    textarea.form-control{
        resize: vertical;
    }

    .form-control,
    .form-select,
    .btn,
    .alert,
    .card{
        max-width: 100%;
    }

    .card-body{
        overflow-x: hidden;
    }

    @media (max-width: 768px){
        .container{
            padding-left: .75rem;
            padding-right: .75rem;
        }

        .question-page-title{
            font-size: 1.45rem;
            width: 100%;
        }

        .d-flex.justify-content-between{
            gap: .75rem;
            flex-wrap: wrap;
        }

        .d-flex.justify-content-between .btn{
            width: 100%;
        }

        .options-card{
            padding: .75rem;
            border-radius: 14px;
        }

        .option-row{
            margin-bottom: 1rem !important;
            padding-bottom: 1rem;
            border-bottom: 1px dashed rgba(148,163,184,.35);
        }

        .option-row:last-child{
            border-bottom: 0;
            padding-bottom: 0;
        }

        .option-number{
            width: 34px;
            height: 34px;
            margin-bottom: .35rem;
        }

        .correct-box{
            margin-top: .25rem;
            min-height: 46px;
        }

        .btn{
            width: 100%;
        }

        .card-body{
            padding: 1rem !important;
        }

        textarea.form-control{
            min-height: 120px;
        }
    }

    @media (max-width: 480px){
        .question-page-title{
            font-size: 1.3rem;
        }

        .options-card{
            padding: .65rem;
        }

        .correct-box{
            padding: .65rem .75rem;
        }

        .alert{
            font-size: .9rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="question-page-title m-0">
            {{ __('app.create_question') }}
        </h2>

        <a class="btn btn-outline-secondary" href="{{ route('instructor.questions.index') }}">
            {{ __('app.back') === 'app.back' ? 'رجوع' : __('app.back') }}
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">
                {{ __('app.validation_errors') === 'app.validation_errors' ? 'يوجد أخطاء:' : __('app.validation_errors') }}
            </div>

            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <form method="POST" action="{{ route('instructor.questions.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">{{ __('app.topic') }}</label>

                    <select class="form-select" name="topic_id" required>
                        <option value="">
                            {{ __('app.choose') === 'app.choose' ? 'اختر...' : __('app.choose') }}
                        </option>

                        @foreach($topics as $t)
                            <option value="{{ $t->id }}" @selected(old('topic_id') == $t->id)>
                                {{ $t->course?->name ?? 'Course' }} – {{ $t->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">{{ __('app.question_body') }}</label>

                    <textarea class="form-control"
                              name="body"
                              rows="4"
                              required>{{ old('body') }}</textarea>
                </div>

                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.type') }}</label>

                        <select class="form-select" name="type" id="questionType" required>
                            @foreach($typeOptions as $val => $label)
                                @if(in_array($val, ['mcq', 'tf']))
                                    <option value="{{ $val }}" @selected(old('type', 'mcq') == $val)>
                                        {{ $label }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.difficulty') }}</label>

                        <select class="form-select" name="difficulty" required>
                            @foreach($difficultyOptions as $val => $label)
                                <option value="{{ $val }}" @selected(old('difficulty', 'easy') == $val)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            {{ __('app.learning_domain') === 'app.learning_domain' ? 'Learning Domain' : __('app.learning_domain') }}
                        </label>

                        <select class="form-select" name="learning_domain" required>
                            <option value="knowledge" @selected(old('learning_domain', 'knowledge') === 'knowledge')>
                                Knowledge
                            </option>

                            <option value="skills" @selected(old('learning_domain') === 'skills')>
                                Skills
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.marks') }}</label>

                        <input type="number"
                               class="form-control"
                               name="marks"
                               value="{{ old('marks', 1) }}"
                               min="1"
                               max="100"
                               required>
                    </div>
                </div>

                <hr class="my-4">

                <div class="alert alert-info">
                    <div class="fw-bold mb-1">
                        {{ __('app.options_hint_create_title') === 'app.options_hint_create_title' ? __('app.options') : __('app.options_hint_create_title') }}
                    </div>

                    <div class="small text-muted">
                        For Multiple Choice, enter at least two options and select exactly one correct answer.<br>
                        For True / False, only two options will be used.
                    </div>

                    <div class="type-note mt-2" id="typeNote"></div>
                </div>

                <div class="options-card">
                    @for($i = 0; $i < 4; $i++)
                        <div class="row g-2 align-items-center mb-2 option-row" data-index="{{ $i }}">
                            <div class="col-md-1 col-12">
                                <div class="option-number">{{ $i + 1 }}</div>
                            </div>

                            <div class="col-md-8 col-12">
                                <input type="text"
                                       class="form-control option-text"
                                       name="options[{{ $i }}][text]"
                                       placeholder="{{ __('app.option_text_placeholder') === 'app.option_text_placeholder' ? 'Option text...' : __('app.option_text_placeholder') }}"
                                       value="{{ old("options.$i.text") }}">
                            </div>

                            <div class="col-md-3 col-12">
                                <div class="form-check correct-box">
                                    <input class="form-check-input option-correct"
                                           type="checkbox"
                                           name="options[{{ $i }}][is_correct]"
                                           value="1"
                                           @checked(old("options.$i.is_correct"))>

                                    <label class="form-check-label ms-2">
                                        {{ __('app.correct') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <button class="btn btn-success mt-4">
                    {{ __('app.save') }}
                </button>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('questionType');
        const rows = document.querySelectorAll('.option-row');
        const typeNote = document.getElementById('typeNote');

        const isArabic = @json(app()->getLocale() === 'ar');

        const labels = {
            mcqNote: isArabic
                ? 'نوع السؤال اختيار من متعدد: اكتب خيارين على الأقل وحدد إجابة صحيحة واحدة فقط.'
                : 'Multiple Choice: enter at least two options and select exactly one correct answer.',

            tfNote: isArabic
                ? 'نوع السؤال صح وخطأ: سيظهر خياران فقط، اختر الإجابة الصحيحة.'
                : 'True / False: only two options will be displayed, select the correct answer.',

            option: isArabic ? 'الخيار' : 'Option',
            trueText: isArabic ? 'صح' : 'True',
            falseText: isArabic ? 'خطأ' : 'False'
        };

        function showRow(row, visible) {
            row.style.display = visible ? '' : 'none';

            row.querySelectorAll('input').forEach(function (input) {
                input.disabled = !visible;

                if (!visible) {
                    if (input.type === 'checkbox') {
                        input.checked = false;
                    }

                    if (input.type === 'text') {
                        input.value = '';
                    }
                }
            });
        }

        function makeOnlyOneCorrect() {
            const correctChecks = document.querySelectorAll('.option-correct');

            correctChecks.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        correctChecks.forEach(function (other) {
                            if (other !== checkbox) {
                                other.checked = false;
                            }
                        });
                    }
                });
            });
        }

        function resetTextInputs() {
            rows.forEach(function (row) {
                const textInput = row.querySelector('.option-text');

                if (textInput) {
                    textInput.readOnly = false;
                }
            });
        }

        function applyQuestionTypeUI() {
            if (!typeSelect) {
                return;
            }

            const type = typeSelect.value;

            resetTextInputs();

            rows.forEach(function (row, index) {
                const textInput = row.querySelector('.option-text');

                if (type === 'mcq') {
                    showRow(row, index < 4);

                    if (index < 4 && textInput) {
                        textInput.readOnly = false;

                        if (
                            textInput.value === labels.trueText ||
                            textInput.value === labels.falseText ||
                            textInput.value === 'True' ||
                            textInput.value === 'False' ||
                            textInput.value === 'صح' ||
                            textInput.value === 'خطأ'
                        ) {
                            textInput.value = '';
                        }

                        textInput.placeholder = labels.option + ' ' + (index + 1);
                    }

                    if (typeNote) {
                        typeNote.textContent = labels.mcqNote;
                    }
                }

                else if (type === 'tf') {
                    showRow(row, index < 2);

                    if (index === 0 && textInput) {
                        textInput.value = labels.trueText;
                        textInput.placeholder = labels.trueText;
                        textInput.readOnly = true;
                    }

                    if (index === 1 && textInput) {
                        textInput.value = labels.falseText;
                        textInput.placeholder = labels.falseText;
                        textInput.readOnly = true;
                    }

                    if (typeNote) {
                        typeNote.textContent = labels.tfNote;
                    }
                }
            });
        }

        makeOnlyOneCorrect();

        if (typeSelect) {
            typeSelect.addEventListener('change', applyQuestionTypeUI);
            applyQuestionTypeUI();
        }
    });
</script>
@endpush