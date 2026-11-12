/* EDTIKA AI Examiner — màn làm bài Writing (setup) */
(function () {
    'use strict';

    var AIE = window.AIE;
    var CFG = window.AIE_SETUP;
    var $ = AIE.$;
    var DRAFT_KEY = 'aie_writing_draft_v1';

    var state = {
        task: CFG.activeTask,
        // Bản nháp từng task — chuyển tab không mất bài
        drafts: AIE.storage.get(DRAFT_KEY, null) || { 1: {}, 2: {} },
        chartFile: null,
        submitting: false
    };

    var els = {
        form: $('#aieWritingForm'),
        topic: $('#aieTopic'),
        essay: $('#aieEssay'),
        chartField: $('#aieChartField'),
        chartZone: $('#aieChartZone'),
        chartInput: $('#aieChartInput'),
        chartPreview: $('#aieChartPreview'),
        chartRemove: $('#aieChartRemove'),
        wordCount: $('#aieWordCount'),
        minWords: $('#aieMinWords'),
        submit: $('#aieSubmit'),
        handBtn: $('#aieHandwritten'),
        handInput: $('#aieHandInput')
    };

    function draft() {
        state.drafts[state.task] = state.drafts[state.task] || {};
        return state.drafts[state.task];
    }

    function saveDraft() {
        var d = draft();
        d.prompt = els.topic.value;
        d.essay = els.essay.value;
        AIE.storage.set(DRAFT_KEY, state.drafts);
    }

    function taskCfg() { return CFG.tasks[state.task]; }

    function updateWordCount() {
        var count = AIE.wordCount(els.essay.value);
        var min = taskCfg().min_words;
        els.wordCount.querySelector('b').textContent = count;
        els.minWords.textContent = min;
        els.wordCount.classList.toggle('is-short', count > 0 && count < min);
    }

    function setChart(src) {
        var d = draft();
        if (src) {
            els.chartPreview.src = src;
            els.chartZone.classList.add('has-file');
        } else {
            els.chartPreview.removeAttribute('src');
            els.chartZone.classList.remove('has-file');
            d.chartUrl = null;
            d.chartDataUrl = null;
            state.chartFile = null;
            els.chartInput.value = '';
        }
    }

    function render() {
        AIE.$$('[data-aie-task]').forEach(function (btn) {
            var active = Number(btn.getAttribute('data-aie-task')) === state.task;
            btn.classList.toggle('is-active', active);
            btn.setAttribute('aria-selected', active ? 'true' : 'false');
        });

        var d = draft();
        els.topic.value = d.prompt || '';
        els.essay.value = d.essay || '';
        els.essay.placeholder = taskCfg().placeholder;
        els.chartField.style.display = taskCfg().has_chart ? '' : 'none';
        state.chartFile = null;
        setChart(d.chartDataUrl || d.chartUrl || null);
        clearErrors();
        updateWordCount();
    }

    function switchTask(task) {
        if (task === state.task) return;
        saveDraft();
        state.task = task;
        render();
        if (window.history && history.replaceState) {
            history.replaceState(null, '', CFG.urls.setup + '?task=' + task);
        }
    }

    function clearErrors() {
        AIE.$$('[data-aie-field]').forEach(function (f) { f.classList.remove('has-error'); });
    }

    function setError(field, message) {
        var f = document.querySelector('[data-aie-field="' + field + '"]');
        if (!f) return;
        f.classList.add('has-error');
        if (message) f.querySelector('.aie-error').textContent = message;
    }

    function handleChartFile(file) {
        if (!file || !/^image\//.test(file.type)) {
            AIE.toast('Vui lòng chọn file ảnh (PNG, JPG...).');
            return;
        }
        state.chartFile = file;
        var reader = new FileReader();
        reader.onload = function () {
            var d = draft();
            d.chartUrl = null;
            // Chỉ lưu ảnh nhỏ vào nháp (localStorage có giới hạn dung lượng)
            d.chartDataUrl = reader.result.length < 1500000 ? reader.result : null;
            setChart(reader.result);
            saveDraft();
        };
        reader.readAsDataURL(file);
    }

    function dataUrlToFile(dataUrl) {
        var parts = dataUrl.split(',');
        var mime = (parts[0].match(/:(.*?);/) || [])[1] || 'image/png';
        var bin = atob(parts[1]);
        var arr = new Uint8Array(bin.length);
        for (var i = 0; i < bin.length; i++) arr[i] = bin.charCodeAt(i);
        return new File([arr], 'chart.' + (mime.split('/')[1] || 'png'), { type: mime });
    }

    function validate() {
        clearErrors();
        var ok = true;
        if (!els.topic.value.trim()) { setError('prompt'); ok = false; }
        if (taskCfg().has_chart && !els.chartZone.classList.contains('has-file')) { setError('chart'); ok = false; }
        if (!els.essay.value.trim()) { setError('essay', 'Vui lòng nhập bài viết.'); ok = false; }
        if (!ok) {
            var first = document.querySelector('.has-error');
            if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return ok;
    }

    function setLoading(on) {
        state.submitting = on;
        els.submit.disabled = on;
        els.submit.innerHTML = on ? '<span class="aie-spinner"></span> Đang gửi bài...' : 'Chấm chữa <span aria-hidden="true">→</span>';
    }

    function doSubmit() {
        if (state.submitting) return;
        saveDraft();

        // Khách chưa đăng nhập → lưu nháp, đánh dấu chờ nộp, bật popup đăng nhập
        if (CFG.isGuest) {
            draft().pendingSubmit = true;
            AIE.storage.set(DRAFT_KEY, state.drafts);
            AIE.storage.set(DRAFT_KEY + '_pending_task', state.task);
            AIE.openModal('aieLoginModal');
            return;
        }

        var d = draft();
        var fd = new FormData();
        fd.append('task', state.task);
        fd.append('prompt', els.topic.value.trim());
        fd.append('essay', els.essay.value.trim());
        if (taskCfg().has_chart) {
            if (state.chartFile) fd.append('chart', state.chartFile);
            else if (d.chartDataUrl) fd.append('chart', dataUrlToFile(d.chartDataUrl));
            else if (d.chartUrl) fd.append('chart_url', d.chartUrl);
        }

        setLoading(true);
        fetch(CFG.urls.submit, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CFG.csrf, 'Accept': 'application/json' },
            body: fd,
            credentials: 'same-origin'
        }).then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (json) { return { res: res, json: json }; });
        }).then(function (r) {
            if (r.res.status === 401) {
                setLoading(false);
                CFG.isGuest = true;
                AIE.openModal('aieLoginModal');
                return;
            }
            if (r.res.ok && r.json.status === 'ok') {
                d.pendingSubmit = false;
                AIE.storage.set(DRAFT_KEY, state.drafts);
                window.location.href = r.json.redirect;
                return;
            }
            setLoading(false);
            var errors = r.json.errors || {};
            Object.keys(errors).forEach(function (k) { setError(k, errors[k][0]); });
            AIE.toast(r.json.message || 'Không gửi được bài. Vui lòng kiểm tra lại.');
        }).catch(function () {
            setLoading(false);
            AIE.toast('Lỗi kết nối, vui lòng thử lại.');
        });
    }

    // ===== Events =====
    AIE.$$('[data-aie-task]').forEach(function (btn) {
        btn.addEventListener('click', function () { switchTask(Number(btn.getAttribute('data-aie-task'))); });
    });

    els.topic.addEventListener('input', saveDraft);
    els.essay.addEventListener('input', function () { updateWordCount(); saveDraft(); });

    els.chartZone.addEventListener('click', function (e) {
        if (e.target.closest('#aieChartRemove') || els.chartZone.classList.contains('has-file')) return;
        els.chartInput.click();
    });
    els.chartZone.addEventListener('keydown', function (e) {
        if ((e.key === 'Enter' || e.key === ' ') && !els.chartZone.classList.contains('has-file')) { e.preventDefault(); els.chartInput.click(); }
    });
    els.chartInput.addEventListener('change', function () { handleChartFile(els.chartInput.files[0]); });
    ['dragenter', 'dragover'].forEach(function (ev) {
        els.chartZone.addEventListener(ev, function (e) { e.preventDefault(); els.chartZone.classList.add('is-drag'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
        els.chartZone.addEventListener(ev, function (e) { e.preventDefault(); els.chartZone.classList.remove('is-drag'); });
    });
    els.chartZone.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files[0]) handleChartFile(e.dataTransfer.files[0]);
    });
    els.chartRemove.addEventListener('click', function (e) {
        e.stopPropagation();
        setChart(null);
        saveDraft();
    });

    $('#aieRandomTopic').addEventListener('click', function () {
        fetch(CFG.urls.randomTopic + '?task=' + state.task + '&current=' + encodeURIComponent(els.topic.value), { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (topic) {
                els.topic.value = topic.prompt;
                var d = draft();
                if (taskCfg().has_chart) {
                    if (topic.chart_url) {
                        state.chartFile = null;
                        d.chartDataUrl = null;
                        d.chartUrl = topic.chart_url;
                        setChart(topic.chart_url);
                    } else {
                        setChart(null);
                        AIE.toast('Đề này chưa có ảnh mẫu — hãy tải ảnh biểu đồ lên.');
                    }
                }
                saveDraft();
            })
            .catch(function () { AIE.toast('Không lấy được đề ngẫu nhiên.'); });
    });

    $('#aieFillDemo').addEventListener('click', function () {
        var demo = CFG.demo[state.task];
        els.topic.value = demo.prompt;
        els.essay.value = demo.essay;
        if (taskCfg().has_chart) {
            var d = draft();
            state.chartFile = null;
            d.chartDataUrl = null;
            d.chartUrl = demo.chart_url;
            setChart(d.chartUrl);
        }
        clearErrors();
        updateWordCount();
        saveDraft();
    });

    els.handBtn.addEventListener('click', function () { els.handInput.click(); });
    els.handInput.addEventListener('change', function () {
        var file = els.handInput.files[0];
        if (!file) return;
        var fd = new FormData();
        fd.append('image', file);
        fd.append('task', state.task);
        AIE.$('#aieOcrOverlay').classList.add('is-open');
        fetch(CFG.urls.ocr, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CFG.csrf, 'Accept': 'application/json' },
            body: fd,
            credentials: 'same-origin'
        }).then(function (r) { return r.json(); }).then(function (json) {
            AIE.$('#aieOcrOverlay').classList.remove('is-open');
            if (json.status === 'ok') {
                els.essay.value = json.text;
                updateWordCount();
                saveDraft();
                AIE.toast(json.is_mock ? 'Bản mẫu: văn bản nhận dạng là dữ liệu giả. Hãy kiểm tra lại trước khi chấm.' : 'Đã nhận dạng bài viết tay. Hãy kiểm tra lại trước khi chấm.');
            } else {
                AIE.toast('Không nhận dạng được ảnh.');
            }
        }).catch(function () {
            AIE.$('#aieOcrOverlay').classList.remove('is-open');
            AIE.toast('Không nhận dạng được ảnh.');
        });
        els.handInput.value = '';
    });

    els.form.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!validate()) return;
        var count = AIE.wordCount(els.essay.value);
        var min = taskCfg().min_words;
        if (count < min) {
            AIE.$('#aieShortText').textContent = 'Bài của bạn có ' + count + ' từ, chưa đạt tối thiểu ' + min + ' từ của Task ' + state.task + '. Trong bài thi thật, bài thiếu từ sẽ bị trừ điểm. Bạn vẫn muốn chấm?';
            AIE.openModal('aieShortModal');
            return;
        }
        doSubmit();
    });

    AIE.$('#aieShortConfirm').addEventListener('click', function () {
        AIE.closeModal('aieShortModal');
        doSubmit();
    });

    var loginNow = AIE.$('#aieLoginNow');
    if (loginNow) {
        loginNow.addEventListener('click', function () {
            AIE.closeModal('aieLoginModal');
            var opener = document.getElementById('aieOpenAuthModal');
            if (opener) opener.click();
        });
    }

    // ===== Init =====
    var pendingTask = AIE.storage.get(DRAFT_KEY + '_pending_task', null);
    if (CFG.resume && pendingTask) {
        state.task = Number(pendingTask);
    }
    render();

    // Quay lại sau khi đăng nhập: tự nộp bài nháp đang chờ
    if (CFG.resume && !CFG.isGuest && pendingTask && draft().pendingSubmit) {
        AIE.storage.remove(DRAFT_KEY + '_pending_task');
        if (validate()) {
            AIE.toast('Đăng nhập thành công — đang gửi bài của bạn để chấm...');
            setTimeout(doSubmit, 400);
        }
    }
})();
