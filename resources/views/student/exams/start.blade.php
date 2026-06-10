@extends('layouts.dashboard')

@section('title', __('app.take_exam'))

@section('content')
@php
    // helper لتجنب ظهور مفاتيح الترجمة لو ناقصة
    $tr = function(string $key, string $fallback) {
        $v = __($key);
        return $v === $key ? $fallback : $v;
    };

    $items = $questions ?? collect();
    $count = $items->count();
    $perQ = (int)($perQuestionSeconds ?? 60);
    if ($perQ <= 0) $perQ = 60;
@endphp

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="m-0">{{ $tr('app.take_exam','حل الاختبار') }}</h2>
            <div class="text-muted small">
                {{ $tr('app.exam','الاختبار') }}: <b>{{ $exam->title }}</b> —
                {{ $tr('app.attempt','المحاولة') }}: <b>#{{ $attempt->id }}</b>
            </div>
        </div>

        <a class="btn btn-outline-secondary" href="{{ route('student.exams.index') }}">
            {{ $tr('app.back','رجوع') }}
        </a>
    </div>

    {{-- شريط وقت السؤال --}}
    <div class="card shadow-sm mb-3 position-sticky" style="top: 84px; z-index: 1020;">
        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="text-muted small">
                    {{ $tr('app.question_time_remaining','الوقت المتبقي لهذا السؤال') }}
                </div>
                <div class="h4 m-0" id="qTime">--:--</div>
            </div>

            <div class="text-end">
                <div class="text-muted small" id="qProgressText">--</div>
                <div class="progress mt-2" style="height: 10px; width: 220px; max-width: 60vw;">
                    <div class="progress-bar" id="qProgressBar" role="progressbar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="timeUpMsg" class="alert alert-warning d-none">
        {{ $tr('app.time_up_move_next','انتهى وقت السؤال — سيتم الانتقال تلقائيًا للسؤال التالي، وتُحسب الإجابة خطأ إن لم تُجب.') }}
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">
                {{ $tr('app.validation_errors','يوجد أخطاء:') }}
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
            <form id="attemptForm"
                  method="POST"
                  action="{{ route('student.attempts.submit', $attempt) }}"
                  onsubmit="return window.__autoSubmit ? true : confirm('{{ $tr('app.confirm_submit_exam','هل تريد تسليم الاختبار؟') }}');">
                @csrf

                {{-- عرض الأسئلة سؤال سؤال --}}
                @forelse($items as $idx => $q)
                    <div class="question-slide"
                         data-idx="{{ $idx }}"
                         data-qid="{{ $q->id }}"
                         style="{{ $idx === 0 ? '' : 'display:none' }}">

                        <div class="mb-3">
                            <div class="fw-bold">
                                {{ $tr('app.question_no','سؤال رقم ') }} {{ $idx + 1 }}
                                <span class="text-muted">
                                    ({{ $q->pivot->marks ?? $q->marks }} {{ $tr('app.marks','درجة') }})
                                </span>
                            </div>

                            <div class="text-muted small">
                                {{ $tr('app.type','النوع') }}:
                                {{ $q->type?->value ?? $q->type }}
                                |
                                {{ $tr('app.difficulty','الصعوبة') }}:
                                {{ $q->difficulty?->value ?? $q->difficulty }}
                                |
                                {{ $tr('app.topic','الموضوع') }}:
                                {{ $q->topic?->name ?? '—' }}
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded">
                            {!! nl2br(e($q->body)) !!}
                        </div>

                        <div class="mt-3">
                            @if($q->type === \App\Enums\QuestionType::MCQ || $q->type === \App\Enums\QuestionType::TrueFalse)
                                @php($opts = $q->options ?? collect())

                                @foreach($opts as $o)
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="radio"
                                               name="answers[{{ $q->id }}][selected_option_id]"
                                               value="{{ $o->id }}"
                                               id="q{{ $q->id }}o{{ $o->id }}">

                                        <label class="form-check-label" for="q{{ $q->id }}o{{ $o->id }}">
                                            {{ $o->text }}
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <label class="form-label">
                                    {{ $tr('app.your_answer','إجابتك') }}
                                </label>

                                <textarea class="form-control"
                                          name="answers[{{ $q->id }}][answer_text]"
                                          rows="3"
                                          placeholder="{{ $tr('app.your_answer_placeholder','اكتب إجابتك هنا...') }}"></textarea>
                            @endif
                        </div>

                        {{-- أزرار التحكم --}}
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            @if($idx !== $count - 1)
                                <button type="button" class="btn btn-outline-warning btn-skip">
                                    {{ $tr('app.skip','تخطي') }}
                                </button>
                            @endif

                            <button type="button" class="btn btn-primary btn-next">
                                {{ $idx === $count - 1 ? $tr('app.submit_exam','إرسال الاختبار') : $tr('app.next','التالي') }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">
                        {{ $tr('app.no_questions_in_exam','لا توجد أسئلة في هذا الاختبار.') }}
                    </div>
                @endforelse
            </form>
        </div>
    </div>
</div>

<script>
(() => {
    const form = document.getElementById('attemptForm');
    const slides = Array.from(document.querySelectorAll('.question-slide'));

    if (!form || slides.length === 0) return;

    const attemptId = @json($attempt->id);
    const perQuestionSeconds = @json($perQ);

    const elTime = document.getElementById('qTime');
    const elProgText = document.getElementById('qProgressText');
    const elProgBar = document.getElementById('qProgressBar');
    const msg = document.getElementById('timeUpMsg');

    /*
        مهم:
        غيرنا v1 إلى v2 حتى لا يستخدم المتصفح التخزين القديم
        الذي كان يجعلك تبدأ من سؤال سابق مثل السؤال 4.
    */
    const stateKey = `attempt_${attemptId}_onebyone_v2`;

    let state = {};

    try {
        state = JSON.parse(localStorage.getItem(stateKey) || '{}');
    } catch (e) {
        state = {};
    }

    if (typeof state.idx !== 'number') {
        state.idx = 0;
    }

    if (typeof state.qStart !== 'number') {
        state.qStart = Date.now();
    }

    if (!state.locked) {
        state.locked = {};
    }

    function save() {
        localStorage.setItem(stateKey, JSON.stringify(state));
    }

    function pad(n) {
        return String(n).padStart(2, '0');
    }

    function setProgress(idx) {
        const total = slides.length;
        const current = Math.min(idx + 1, total);
        const pct = total ? Math.round((current / total) * 100) : 0;

        if (elProgText) {
            elProgText.textContent = `سؤال ${current} من ${total}`;
        }

        if (elProgBar) {
            elProgBar.style.width = `${pct}%`;
        }
    }

    function renderTimeLeft(sec) {
        const s = Math.max(0, Math.floor(sec));
        const m = Math.floor(s / 60);
        const r = s % 60;

        if (elTime) {
            elTime.textContent = `${pad(m)}:${pad(r)}`;
        }
    }

    function showSlide(idx) {
        slides.forEach((slide, i) => {
            slide.style.display = i === idx ? '' : 'none';
        });

        setProgress(idx);
        renderTimeLeft(perQuestionSeconds);
    }

    function ensureHidden(name, value) {
        let el = form.querySelector(`input[type="hidden"][name="${CSS.escape(name)}"]`);

        if (!el) {
            el = document.createElement('input');
            el.type = 'hidden';
            el.name = name;
            form.appendChild(el);
        }

        el.value = value ?? '';
    }

    function lockSlide(slide) {
        const qid = slide.dataset.qid;

        if (!qid) return;

        // إذا السؤال مقفل سابقًا بعد تحديث الصفحة
        if (state.locked[qid]?.done) {
            const prev = state.locked[qid] || {};

            if (prev.selected) {
                ensureHidden(`answers[${qid}][selected_option_id]`, prev.selected);
            }

            if (prev.text) {
                ensureHidden(`answers[${qid}][answer_text]`, prev.text);
            }

            slide.querySelectorAll('input, textarea').forEach(el => {
                if (el.type !== 'hidden') {
                    el.disabled = true;
                }
            });

            return;
        }

        const checked = slide.querySelector('input[type="radio"]:checked');
        const textarea = slide.querySelector('textarea');

        const selectedVal = checked ? String(checked.value) : null;
        const textVal = textarea ? String(textarea.value || '').trim() : null;

        const locked = {
            done: true,
            at: Date.now()
        };

        // حفظ إجابة الاختيار من متعدد
        if (selectedVal) {
            ensureHidden(`answers[${qid}][selected_option_id]`, selectedVal);
            locked.selected = selectedVal;
        }

        // حفظ الإجابة النصية
        if (textVal) {
            ensureHidden(`answers[${qid}][answer_text]`, textVal);
            locked.text = textVal;
        }

        /*
            إذا ضغط الطالب تخطي بدون إجابة:
            لا يتم حفظ إجابة لهذا السؤال.
            وبالتالي يحسب خطأ أو صفر حسب نظام التصحيح الحالي.
        */
        state.locked[qid] = locked;
        save();

        // تعطيل السؤال بعد تجاوزه حتى لا يرجع ويعدله
        slide.querySelectorAll('input, textarea').forEach(el => {
            if (el.type !== 'hidden') {
                el.disabled = true;
            }
        });
    }

    function finishExam(auto = false) {
        window.__autoSubmit = !!auto;

        /*
            عند إنهاء الاختبار نحذف التخزين
            حتى لو دخل محاولة جديدة لا يبدأ من سؤال قديم.
        */
        localStorage.removeItem(stateKey);

        if (form.requestSubmit) {
            form.requestSubmit();
        } else {
            form.submit();
        }
    }

    function goNext(auto = false) {
        const current = slides[state.idx];

        if (current) {
            lockSlide(current);
        }

        state.idx += 1;
        state.qStart = Date.now();
        save();

        if (state.idx >= slides.length) {
            finishExam(auto);
            return;
        }

        if (msg) {
            msg.classList.add('d-none');
        }

        showSlide(state.idx);
    }

    // استرجاع الأسئلة المقفلة بعد تحديث الصفحة
    slides.forEach(slide => {
        const qid = slide.dataset.qid;

        if (qid && state.locked[qid]?.done) {
            lockSlide(slide);
        }
    });

    // تأكد أن رقم السؤال صحيح
    if (state.idx < 0) {
        state.idx = 0;
    }

    if (state.idx >= slides.length) {
        finishExam(true);
        return;
    }

    showSlide(state.idx);

    // زر التالي
    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', () => {
            goNext(false);
        });
    });

    // زر التخطي
    document.querySelectorAll('.btn-skip').forEach(btn => {
        btn.addEventListener('click', () => {
            goNext(false);
        });
    });

    // مؤقت السؤال
    setInterval(() => {
        const elapsed = (Date.now() - state.qStart) / 1000;
        const left = perQuestionSeconds - elapsed;

        renderTimeLeft(left);

        if (left <= 0) {
            if (msg) {
                msg.classList.remove('d-none');
            }

            goNext(true);
        }
    }, 500);
})();
</script>
@endsection