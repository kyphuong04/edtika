@extends('design_1.web.layouts.app')

@php
    $appHeader = true;
    $appFooter = true;
    $floatingBar = null;
    $criteriaCount = count($criteria);
@endphp

@push('styles_top')
    <link rel="stylesheet" href="/assets/design_1/css/ai-examiner.css?v=1.0">
@endpush

@section('content')
<div class="aie-page">
    @include('design_1.web.ai_examiner.partials.header')

    <main class="aie-main">
        <div class="aie-container">
            <section class="aie-grading" aria-live="polite">
                <h1>Đang chấm bài của bạn...</h1>
                <p class="aie-grading__sub">Edtika đang xem xét và đánh giá bài làm theo các tiêu chí IELTS.<br>Vui lòng đợi trong giây lát!</p>

                <img class="aie-grading__art" src="/assets/design_1/img/ai-examiner/grading-illustration.jpg" alt="Edti đang chấm bài IELTS">

                <div class="aie-grading__progress">
                    <span id="aieGradeCount">0/{{ $criteriaCount }}</span>
                    <div class="aie-grading__bar"><span id="aieGradeBar"></span></div>
                    <span id="aieGradePercent">6%</span>
                </div>
                <div class="aie-grading__current" id="aieGradeCurrent">Đang chấm tiêu chí {{ reset($criteria)['name'] }}...</div>

                <div class="aie-grading__list">
                    @foreach($criteria as $criterionKey => $criterion)
                        <div class="aie-grading__item {{ $loop->first ? 'is-processing' : '' }}" data-aie-criterion="{{ $criterionKey }}">
                            <span class="aie-grading__dot"></span>
                            <span class="aie-grading__label">{{ $criterion['name'] }}</span>
                            <span class="aie-grading__state">{{ $loop->first ? 'Đang chấm...' : 'Chờ...' }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="aie-grading__error" id="aieGradeError">
                    Có lỗi khi chấm bài. <a href="{{ route('ai_examiner.writing', ['task' => $submission['task']]) }}">Quay lại màn làm bài</a>
                </div>
            </section>
        </div>
    </main>
</div>
@endsection

@push('scripts_bottom')
    <script>
        (function () {
            'use strict';
            var statusUrl = @json($statusUrl);
            var labels = { done: 'Đã chấm', processing: 'Đang chấm...', waiting: 'Chờ...' };
            var failures = 0;

            function render(data) {
                document.getElementById('aieGradeCount').textContent = data.done + '/' + data.total;
                document.getElementById('aieGradeBar').style.width = data.progress + '%';
                document.getElementById('aieGradePercent').textContent = data.progress + '%';

                var current = null;
                (data.criteria || []).forEach(function (c) {
                    var row = document.querySelector('[data-aie-criterion="' + c.key + '"]');
                    if (!row) return;
                    row.classList.remove('is-done', 'is-processing', 'is-waiting');
                    row.classList.add('is-' + c.status);
                    row.querySelector('.aie-grading__state').textContent = labels[c.status] || '';
                    if (c.status === 'processing') current = c.name;
                });

                document.getElementById('aieGradeCurrent').textContent = current
                    ? 'Đang chấm tiêu chí ' + current + '...'
                    : (data.status === 'done' ? 'Hoàn tất! Đang mở kết quả...' : '');
            }

            function poll() {
                fetch(statusUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' })
                    .then(function (r) { if (!r.ok) throw new Error(r.status); return r.json(); })
                    .then(function (data) {
                        failures = 0;
                        render(data);
                        if (data.status === 'done' && data.redirect) {
                            setTimeout(function () { window.location.href = data.redirect; }, 700);
                        } else if (data.status === 'failed') {
                            document.getElementById('aieGradeError').style.display = 'block';
                        } else {
                            setTimeout(poll, 1000);
                        }
                    })
                    .catch(function () {
                        failures++;
                        if (failures >= 5) {
                            document.getElementById('aieGradeError').style.display = 'block';
                        } else {
                            setTimeout(poll, 2000);
                        }
                    });
            }

            setTimeout(poll, 600);
        })();
    </script>
@endpush
