@extends('layouts.dashboard')

@section('title', __('app.create_exam'))

@push('styles')
<style>
    .exam-page-title{
        font-size: 2rem;
        font-weight: 900;
        color: #0f172a;
        letter-spacing: .2px;
    }

    .exam-card{
        border-radius: 18px;
        border: 1px solid rgba(148,163,184,.25);
    }

    .section-box{
        background: #f8fafc;
        border: 1px solid rgba(148,163,184,.25);
        border-radius: 16px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .students-box{
        max-height: 230px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid rgba(148,163,184,.25);
        border-radius: 14px;
        padding: .75rem;
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
        <h2 class="exam-page-title m-0">{{ __('app.create_exam') }}</h2>

        <a class="btn btn-outline-secondary" href="{{ route('instructor.exams.index') }}">
            {{ __('app.back') }}
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

    <div class="card shadow-sm exam-card">
        <div class="card-body">

            <form method="POST" action="{{ route('instructor.exams.store') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">{{ __('app.exam_title') }}</label>
                    <input type="text"
                           name="title"
                           value="{{ old('title') }}"
                           class="form-control"
                           placeholder="Enter exam title"
                           required>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.course') }}</label>
                        <select name="course_id" id="courseSelect" class="form-select" required>
                            <option value="">
                                {{ __('app.choose') === 'app.choose' ? 'اختر...' : __('app.choose') }}
                            </option>

                            @foreach($courses as $c)
                                <option value="{{ $c->id }}" @selected(old('course_id') == $c->id)>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.topic') === 'app.topic' ? 'Topic' : __('app.topic') }}</label>
                        <select name="topic_id" id="topicSelect" class="form-select" required>
                            <option value="">
                                {{ __('app.choose') === 'app.choose' ? 'اختر الموضوع...' : __('app.choose') }}
                            </option>

                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}"
                                        data-course-id="{{ $topic->course_id }}"
                                        @selected(old('topic_id') == $topic->id)>
                                    {{ $topic->name }}
                                </option>
                            @endforeach
                        </select>

                        <div class="form-text">
                            اختر الموضوع المرتبط بالاختبار.
                        </div>
                    </div>

                </div>

                <div class="row g-3 mt-1">

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.duration_minutes') }}</label>
                        <input type="number"
                               name="duration_minutes"
                               value="{{ old('duration_minutes', 30) }}"
                               class="form-control"
                               min="5"
                               max="300"
                               required>

                        <div class="form-text">
                            مدة الاختبار كاملة لجميع الأسئلة، وليست لكل سؤال.
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">{{ __('app.status') }}</label>
                        <select name="status" class="form-select" required>
                            <option value="draft" @selected(old('status', 'draft') === 'draft')>
                                {{ __('app.draft') === 'app.draft' ? 'Draft' : __('app.draft') }}
                            </option>

                            <option value="published" @selected(old('status') === 'published')>
                                {{ __('app.published') === 'app.published' ? 'Published' : __('app.published') }}
                            </option>
                        </select>
                    </div>

                </div>

                <div class="section-box">
                    <label class="form-label fw-bold">
                        تحديد الطلاب
                    </label>

                    <div class="mb-2">
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="assign_to"
                                   id="assignAll"
                                   value="all"
                                   @checked(old('assign_to', 'all') === 'all')>

                            <label class="form-check-label" for="assignAll">
                                جميع الطلاب
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="assign_to"
                                   id="assignSelected"
                                   value="selected"
                                   @checked(old('assign_to') === 'selected')>

                            <label class="form-check-label" for="assignSelected">
                                طلاب محددين
                            </label>
                        </div>
                    </div>

                    <div id="studentsWrapper" class="students-box mt-3" style="display:none;">
                        @forelse($students as $student)
                            <div class="form-check mb-2">
                                <input class="form-check-input student-checkbox"
                                       type="checkbox"
                                       name="student_ids[]"
                                       value="{{ $student->id }}"
                                       id="student_{{ $student->id }}"
                                       @checked(in_array($student->id, old('student_ids', [])))>

                                <label class="form-check-label" for="student_{{ $student->id }}">
                                    {{ $student->name }}
                                    @if(!empty($student->email))
                                        <span class="text-muted"> - {{ $student->email }}</span>
                                    @endif
                                </label>
                            </div>
                        @empty
                            <div class="text-muted">
                                لا يوجد طلاب حاليًا.
                            </div>
                        @endforelse
                    </div>
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
        const courseSelect = document.getElementById('courseSelect');
        const topicSelect = document.getElementById('topicSelect');

        const assignAll = document.getElementById('assignAll');
        const assignSelected = document.getElementById('assignSelected');
        const studentsWrapper = document.getElementById('studentsWrapper');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');

        function filterTopicsByCourse() {
            const selectedCourseId = courseSelect.value;
            const topicOptions = topicSelect.querySelectorAll('option');

            topicOptions.forEach(function (option) {
                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                const optionCourseId = option.getAttribute('data-course-id');
                option.hidden = selectedCourseId && optionCourseId !== selectedCourseId;
            });

            const selectedOption = topicSelect.options[topicSelect.selectedIndex];

            if (selectedOption && selectedOption.hidden) {
                topicSelect.value = '';
            }
        }

        function toggleStudentsBox() {
            const showStudents = assignSelected.checked;

            studentsWrapper.style.display = showStudents ? '' : 'none';

            studentCheckboxes.forEach(function (checkbox) {
                checkbox.disabled = !showStudents;

                if (!showStudents) {
                    checkbox.checked = false;
                }
            });
        }

        if (courseSelect && topicSelect) {
            courseSelect.addEventListener('change', filterTopicsByCourse);
            filterTopicsByCourse();
        }

        if (assignAll && assignSelected) {
            assignAll.addEventListener('change', toggleStudentsBox);
            assignSelected.addEventListener('change', toggleStudentsBox);
            toggleStudentsBox();
        }
    });
</script>
@endpush