@extends('layouts.dashboard')

@section('title', __('app.edit_question'))

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
    }

    .correct-box{
        background: #ffffff;
        border: 1px solid rgba(148,163,184,.28);
        border-radius: 14px;
        padding: .75rem .9rem;
        height: 100%;
        display: flex;
        align-items: center;
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
</style>
@endpush

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="question-page-title m-0">
            {{ __('app.edit_question') }} #{{ $question->id }}
        </h2>

        <a class="btn btn-outline-secondary" href="{{ route('instructor.questions.show', $question) }}">
            {{ __('app.back') }}
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">
                {{ __('app.validation_errors') ?? 'There are validation errors:' }}
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

            <form method="POST" action="{{ route('instructor.questions.update', $question) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">{{ __('app.topic') }}</label>

                    <select class="form-select" name="topic_id" required>
                        @foreach($topics as $t)
                            <option value="{{ $t->id }}" @selected(old('topic_id', $question->topic_id) == $t->id)>
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
                              required>{{ old('body', $question->body) }}</textarea>
                </div>

                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.type') }}</label>

                        @php
                            $currentType = old('type', is_object($question->type) ? $question->type->value : $question->type);
                        @endphp

                        <select class="form-select" name="type" id="question-type" required>
                            <option value="mcq" @selected($currentType === 'mcq')>
                                Multiple Choice
                            </option>

                            <option value="tf" @selected($currentType === 'tf')>
                                True / False
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.difficulty') }}</label>

                        @php
                            $currentDifficulty = old('difficulty', is_object($question->difficulty) ? $question->difficulty->value : $question->difficulty);
                        @endphp

                        <select class="form-select" name="difficulty" required>
                            @foreach($difficultyOptions as $val => $label)
                                <option value="{{ $val }}" @selected($currentDifficulty === $val)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            {{ __('app.learning_domain') === 'app.learning_domain' ? 'Learning Domain' : __('app.learning_domain') }}
                        </label>

                        @php
                            $currentLearningDomain = old('learning_domain', $question->learning_domain ?? 'knowledge');
                        @endphp

                        <select class="form-select" name="learning_domain" required>
                            <option value="knowledge" @selected($currentLearningDomain === 'knowledge')>
                                Knowledge
                            </option>

                            <option value="skills" @selected($currentLearningDomain === 'skills')>
                                Skills
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">{{ __('app.marks') }}</label>

                        <input type="number"
                               class="form-control"
                               name="marks"
                               value="{{ old('marks', $question->marks) }}"
                               min="1"
                               max="100"
                               required>
                    </div>
                </div>

                <hr class="my-4">

                <div class="alert alert-info">
                    <div class="fw-bold mb-1">{{ __('app.options') }}</div>

                    <div class="small text-muted">
                        For Multiple Choice, enter the options and select exactly one correct answer.<br>
                        For True / False, use only the first two options: True and False.
                    </div>

                    <div class="type-note mt-2" id="typeNote"></div>
                </div>

                <div class="options-card">
                    @php
                        $opts = $question->options ?? collect();
                        $rows = 4;
                    @endphp

                    @for($i = 0; $i < $rows; $i++)
                        @php($opt = $opts[$i] ?? null)

                        <div class="row g-2 align-items-center mb-2 option-row" data-index="{{ $i }}">
                            <div class="col-md-1">
                                <div class="option-number">{{ $i + 1 }}</div>
                            </div>

                            <div class="col-md-8">
                                <input type="hidden"
                                       name="options[{{ $i }}][id]"
                                       value="{{ $opt->id ?? '' }}">

                                <input type="text"
                                       class="form-control option-text"
                                       name="options[{{ $i }}][text]"
                                       value="{{ old("options.$i.text", $opt->text ?? '') }}"
                                       placeholder="Option text...">
                            </div>

                            <div class="col-md-3">
                                <div class="form-check correct-box">
                                    <input class="form-check-input option-correct"
                                           type="checkbox"
                                           name="options[{{ $i }}][is_correct]"
                                           value="1"
                                           @checked(old("options.$i.is_correct", $opt?->is_correct ?? false))>

                                    <label class="form-check-label ms-2">
                                        {{ __('app.correct') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>

                <button class="btn btn-primary mt-4">
                    {{ __('app.update') }}
                </button>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('question-type');
        const rows = document.querySelectorAll('.option-row');
        const typeNote = document.getElementById('typeNote');

        const isArabic = @json(app()->getLocale() === 'ar');

        const labels = {
            mcqNote: isArabic
                ? 'نوع السؤال اختيار من متعدد: سيظهر 4 خيارات.'
                : 'Multiple Choice question: 4 options will be displayed.',

            tfNote: isArabic
                ? 'نوع السؤال صح وخطأ: سيظهر خياران فقط.'
                : 'True / False question: only 2 options will be displayed.',

            option: isArabic ? 'الخيار' : 'Option',
            trueText: isArabic ? 'صح' : 'True',
            falseText: isArabic ? 'خطأ' : 'False'
        };

        function makeOnlyOneCorrect() {
            const checks = document.querySelectorAll('.option-correct');

            checks.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        checks.forEach(function (other) {
                            if (other !== checkbox) {
                                other.checked = false;
                            }
                        });
                    }
                });
            });
        }

        function setRowEnabled(row, enabled) {
            row.style.display = enabled ? '' : 'none';

            row.querySelectorAll('input').forEach(function (input) {
                input.disabled = !enabled;

                if (!enabled) {
                    if (input.type === 'checkbox') {
                        input.checked = false;
                    }

                    if (input.type === 'text') {
                        input.value = '';
                    }
                }
            });
        }

        function applyTypeBehavior() {
            if (!typeSelect) {
                return;
            }

            const type = typeSelect.value;

            rows.forEach(function (row, index) {
                const textInput = row.querySelector('.option-text');

                if (type === 'tf') {
                    setRowEnabled(row, index < 2);

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
                } else {
                    setRowEnabled(row, true);

                    if (textInput) {
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
            });
        }

        makeOnlyOneCorrect();

        if (typeSelect) {
            typeSelect.addEventListener('change', applyTypeBehavior);
            applyTypeBehavior();
        }
    });
</script>
@endpush