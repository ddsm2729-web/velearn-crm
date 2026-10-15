{{-- Shared create/edit form for placement classes. Expects optional $placementClass. --}}
@php
    $isEdit = isset($placementClass);
    $formAction = $isEdit ? route('placement-classes.update', $placementClass->id) : route('placement-classes.store');
@endphp

<style>
    .pc-page { max-width: 1200px; }
    .pc-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 55%, #a855f7 100%);
        border-radius: 18px; padding: 26px 30px; color: #fff; position: relative; overflow: hidden;
        box-shadow: 0 12px 30px -12px rgba(79, 70, 229, .55);
    }
    .pc-hero::after {
        content: ""; position: absolute; right: -60px; top: -60px; width: 220px; height: 220px;
        border-radius: 50%; background: rgba(255, 255, 255, .08);
    }
    .pc-hero h2 { font-weight: 800; font-size: 1.5rem; margin: 0; color: #fff; }
    .pc-hero p { margin: 4px 0 0; opacity: .85; font-size: .9rem; }
    .pc-hero .btn-back {
        background: rgba(255, 255, 255, .16); color: #fff; border: 1px solid rgba(255, 255, 255, .3);
        border-radius: 10px; padding: 8px 16px; font-weight: 600; backdrop-filter: blur(4px); position: relative; z-index: 1;
    }
    .pc-hero .btn-back:hover { background: rgba(255, 255, 255, .28); color: #fff; }

    .pc-card { background: #fff; border: 1px solid #eef0f6; border-radius: 16px; box-shadow: 0 4px 18px rgba(15, 23, 42, .05); height: 100%; }
    .pc-card-head { padding: 18px 22px; border-bottom: 1px solid #f1f3f9; display: flex; align-items: center; gap: 12px; }
    .pc-card-icon { width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; font-size: 1rem; }
    .pc-card-head h6 { margin: 0; font-weight: 700; color: #1e293b; }
    .pc-card-head small { color: #94a3b8; }
    .pc-card-body { padding: 22px; }

    .pc-label { font-weight: 600; font-size: .85rem; color: #334155; margin-bottom: 6px; }
    .pc-input-group { display: flex; align-items: center; border: 1.5px solid #e2e8f0; border-radius: 12px; background: #f8fafc; transition: all .2s; }
    .pc-input-group:focus-within { border-color: #6366f1; background: #fff; box-shadow: 0 0 0 4px rgba(99, 102, 241, .12); }
    .pc-input-group.is-invalid { border-color: #ef4444; }
    .pc-input-group i { color: #94a3b8; padding: 0 12px 0 14px; }
    .pc-input-group input { border: 0; background: transparent; padding: 12px 14px 12px 0; width: 100%; outline: none; font-size: .95rem; color: #0f172a; }
    .pc-hint { font-size: .78rem; color: #94a3b8; margin-top: 6px; }
    .pc-error { font-size: .8rem; color: #ef4444; margin-top: 6px; }

    .pc-drop {
        border: 2px dashed #c7d2fe; border-radius: 14px; background: linear-gradient(180deg, #f8faff, #f1f5ff);
        padding: 34px 20px; text-align: center; cursor: pointer; transition: all .2s;
    }
    .pc-drop:hover, .pc-drop.dragover { border-color: #6366f1; background: #eef2ff; transform: translateY(-1px); }
    .pc-drop.is-invalid { border-color: #ef4444; background: #fef2f2; }
    .pc-drop-icon {
        width: 64px; height: 64px; border-radius: 50%; margin: 0 auto 12px; display: grid; place-items: center;
        background: linear-gradient(135deg, #6366f1, #a855f7); color: #fff; font-size: 1.5rem;
        box-shadow: 0 8px 20px -6px rgba(99, 102, 241, .6);
    }
    .pc-drop h6 { font-weight: 700; color: #1e293b; margin-bottom: 4px; }
    .pc-drop span.browse { color: #6366f1; font-weight: 700; text-decoration: underline; }

    .pc-file { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0; margin-top: 14px; }
    .pc-file-icon { width: 40px; height: 40px; border-radius: 10px; background: #e0e7ff; color: #4f46e5; display: grid; place-items: center; }
    .pc-file-name { font-weight: 600; color: #0f172a; font-size: .9rem; word-break: break-all; }
    .pc-file-size { font-size: .78rem; color: #64748b; }
    .pc-file-remove { margin-left: auto; border: 0; background: #fee2e2; color: #dc2626; width: 32px; height: 32px; border-radius: 8px; }
    .pc-file-remove:hover { background: #fecaca; }

    .pc-player { border-radius: 14px; overflow: hidden; background: #0f172a; aspect-ratio: 16/9; margin-top: 14px; }
    .pc-player video, .pc-player iframe { width: 100%; height: 100%; display: block; border: 0; }
    .pc-tag { display: inline-flex; align-items: center; gap: 6px; font-size: .72rem; font-weight: 700; padding: 4px 10px; border-radius: 999px; text-transform: uppercase; letter-spacing: .4px; }

    .pc-progress { display: none; margin-top: 16px; }
    .pc-progress-bar-wrap { height: 10px; border-radius: 999px; background: #eef2ff; overflow: hidden; }
    .pc-progress-bar { height: 100%; width: 0; background: linear-gradient(90deg, #6366f1, #a855f7); transition: width .2s; }
    .pc-progress-text { display: flex; justify-content: space-between; font-size: .8rem; color: #475569; margin-top: 6px; font-weight: 600; }

    .pc-actions {
        position: sticky; bottom: 0; z-index: 5; margin-top: 22px; background: rgba(255, 255, 255, .9); backdrop-filter: blur(8px);
        border: 1px solid #eef0f6; border-radius: 14px; padding: 14px 20px; display: flex; justify-content: flex-end; gap: 10px;
        box-shadow: 0 -4px 18px rgba(15, 23, 42, .05);
    }
    .pc-btn { border-radius: 10px; padding: 10px 22px; font-weight: 700; border: 0; transition: all .2s; }
    .pc-btn-primary { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: #fff; box-shadow: 0 8px 18px -8px rgba(79, 70, 229, .7); }
    .pc-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 22px -8px rgba(79, 70, 229, .8); color: #fff; }
    .pc-btn-primary:disabled { opacity: .7; transform: none; cursor: not-allowed; }
    .pc-btn-light { background: #f1f5f9; color: #334155; }
    .pc-btn-light:hover { background: #e2e8f0; color: #0f172a; }
</style>

<div class="container-fluid py-4 pc-page">
    {{-- Header --}}
    <div class="pc-hero d-flex justify-content-between align-items-center mb-4">
        <div style="position: relative; z-index: 1;">
            <h2><i class="fas {{ $isEdit ? 'fa-pen-to-square' : 'fa-circle-plus' }} me-2"></i>{{ $isEdit ? 'Edit Placement Class' : 'Create Placement Class' }}</h2>
            <p>{{ $isEdit ? 'Update the class details or replace its video.' : 'Add a new day-wise placement training video for students.' }}</p>
        </div>
        <a href="javascript:history.back()" class="btn btn-back"><i class="fas fa-arrow-left me-2"></i> Back to list</a>
    </div>

    <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" id="placementClassForm" novalidate>
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="row g-4">
            {{-- Details --}}
            <div class="col-lg-5">
                <div class="pc-card">
                    <div class="pc-card-head">
                        <div class="pc-card-icon" style="background:#e0e7ff;color:#4f46e5;"><i class="fas fa-list-check"></i></div>
                        <div>
                            <h6>Class Details</h6>
                            <small>Which day and what it covers</small>
                        </div>
                    </div>
                    <div class="pc-card-body">
                        <div class="mb-4">
                            <label class="pc-label" for="day">Day Number <span class="text-danger">*</span></label>
                            <div class="pc-input-group @error('day') is-invalid @enderror" data-field="day">
                                <i class="fas fa-calendar-day"></i>
                                <input type="number" min="1" id="day" name="day" placeholder="e.g. 1" required
                                    value="{{ old('day', $placementClass->day ?? '') }}">
                            </div>
                            <div class="pc-error" data-error-for="day">@error('day'){{ $message }}@enderror</div>
                            <div class="pc-hint">Students see classes in day order.</div>
                        </div>

                        <div class="mb-2">
                            <label class="pc-label" for="title">Class Title <span class="text-danger">*</span></label>
                            <div class="pc-input-group @error('title') is-invalid @enderror" data-field="title">
                                <i class="fas fa-heading"></i>
                                <input type="text" id="title" name="title" maxlength="255" placeholder="e.g. Resume Building" required
                                    value="{{ old('title', $placementClass->title ?? '') }}">
                            </div>
                            <div class="pc-error" data-error-for="title">@error('title'){{ $message }}@enderror</div>
                            <div class="pc-hint d-flex justify-content-between">
                                <span>Short, clear topic name.</span>
                                <span id="pcTitleCount">0/255</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Video --}}
            <div class="col-lg-7">
                <div class="pc-card">
                    <div class="pc-card-head">
                        <div class="pc-card-icon" style="background:#f3e8ff;color:#9333ea;"><i class="fas fa-film"></i></div>
                        <div>
                            <h6>Class Video {!! $isEdit ? '' : '<span class="text-danger">*</span>' !!}</h6>
                            <small>{{ $isEdit ? 'Upload a new file to replace the current video' : 'Upload the lesson video' }}</small>
                        </div>
                    </div>
                    <div class="pc-card-body">
                        @if ($isEdit && $placementClass->video)
                            <div id="pcCurrentVideo">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="pc-label mb-0">Current Video</span>
                                    @if ($placementClass->video_path)
                                        <span class="pc-tag" style="background:#dcfce7;color:#15803d;"><i class="fas fa-circle-check"></i> Uploaded</span>
                                    @else
                                        <span class="pc-tag" style="background:#fef3c7;color:#b45309;"><i class="fas fa-link"></i> Old link</span>
                                    @endif
                                </div>
                                <div class="pc-player">
                                    @if ($placementClass->video_path)
                                        <video src="{{ $placementClass->video }}" controls preload="metadata"></video>
                                    @else
                                        <iframe src="{{ $placementClass->video }}" allowfullscreen></iframe>
                                    @endif
                                </div>
                                <hr class="my-4" style="border-color:#eef0f6;">
                            </div>
                        @endif

                        <input type="file" id="placementVideoInput" name="video" accept="video/*" class="d-none" {{ $isEdit ? '' : 'required' }}>

                        <div class="pc-drop @error('video') is-invalid @enderror" id="pcDropZone" data-field="video" role="button" tabindex="0">
                            <div class="pc-drop-icon"><i class="fas fa-cloud-arrow-up"></i></div>
                            <h6>{{ $isEdit ? 'Drop a new video to replace' : 'Drag & drop your video here' }}</h6>
                            <div class="text-muted small">or <span class="browse">browse files</span></div>
                            <div class="pc-hint mt-2">MP4, MOV, AVI, WMV, WEBM, MKV &middot; max 50 MB</div>
                        </div>
                        <div class="pc-error" data-error-for="video">@error('video'){{ $message }}@enderror</div>

                        <div id="pcSelected" class="d-none">
                            <div class="pc-file">
                                <div class="pc-file-icon"><i class="fas fa-file-video"></i></div>
                                <div>
                                    <div class="pc-file-name" id="pcFileName"></div>
                                    <div class="pc-file-size" id="pcFileSize"></div>
                                </div>
                                <button type="button" class="pc-file-remove" id="pcFileRemove" title="Remove"><i class="fas fa-xmark"></i></button>
                            </div>
                            <div class="pc-player"><video id="placementVideoPreview" controls></video></div>
                        </div>

                        <div class="pc-progress" id="pcProgress">
                            <div class="pc-progress-bar-wrap"><div class="pc-progress-bar" id="pcProgressBar"></div></div>
                            <div class="pc-progress-text"><span id="pcProgressLabel">Uploading…</span><span id="pcProgressPct">0%</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pc-actions">
            <a href="{{ route('placement-classes.index') }}" class="pc-btn pc-btn-light text-decoration-none">Cancel</a>
            <button type="submit" class="pc-btn pc-btn-primary" id="pcSubmitBtn">
                <i class="fas {{ $isEdit ? 'fa-floppy-disk' : 'fa-cloud-arrow-up' }} me-1"></i>
                <span>{{ $isEdit ? 'Save Changes' : 'Create Class' }}</span>
            </button>
        </div>
    </form>
</div>

@push('script')
<script>
    (function () {
        const MAX_BYTES = 50 * 1024 * 1024;
        const ALLOWED = ['mp4', 'mov', 'avi', 'wmv', 'webm', 'mkv'];
        const isEdit = {{ $isEdit ? 'true' : 'false' }};

        const form = document.getElementById('placementClassForm');
        const input = document.getElementById('placementVideoInput');
        const drop = document.getElementById('pcDropZone');
        const selected = document.getElementById('pcSelected');
        const preview = document.getElementById('placementVideoPreview');
        const submitBtn = document.getElementById('pcSubmitBtn');
        const titleInput = document.getElementById('title');
        const titleCount = document.getElementById('pcTitleCount');
        let previewUrl = null;

        function notifyError(message) {
            if (window.toastr) {
                toastr.error(message);
            } else {
                alert(message);
            }
        }

        function formatSize(bytes) {
            return bytes >= 1048576 ? (bytes / 1048576).toFixed(1) + ' MB' : (bytes / 1024).toFixed(0) + ' KB';
        }

        function setFieldError(field, message) {
            const box = form.querySelector('[data-error-for="' + field + '"]');
            const wrap = form.querySelector('[data-field="' + field + '"]');
            if (box) box.textContent = message || '';
            if (wrap) wrap.classList.toggle('is-invalid', !!message);
        }

        function clearErrors() {
            ['day', 'title', 'video'].forEach(f => setFieldError(f, ''));
        }

        function resetFile() {
            input.value = '';
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            preview.removeAttribute('src');
            selected.classList.add('d-none');
            drop.classList.remove('d-none');
        }

        function handleFile(file) {
            if (!file) return;
            const ext = file.name.split('.').pop().toLowerCase();
            if (!ALLOWED.includes(ext)) {
                setFieldError('video', 'Please choose a video file (' + ALLOWED.join(', ') + ').');
                resetFile();
                return;
            }
            if (file.size > MAX_BYTES) {
                setFieldError('video', 'This file is ' + formatSize(file.size) + '. Maximum allowed is 50 MB.');
                resetFile();
                return;
            }
            setFieldError('video', '');
            const dt = new DataTransfer();
            dt.items.add(file);
            input.files = dt.files;

            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = URL.createObjectURL(file);
            preview.src = previewUrl;
            document.getElementById('pcFileName').textContent = file.name;
            document.getElementById('pcFileSize').textContent = formatSize(file.size);
            selected.classList.remove('d-none');
            drop.classList.add('d-none');
        }

        // Title counter
        const updateCount = () => titleCount.textContent = titleInput.value.length + '/255';
        titleInput.addEventListener('input', updateCount);
        updateCount();

        // Drop zone
        drop.addEventListener('click', () => input.click());
        drop.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); } });
        ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('dragover'); }));
        ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('dragover'); }));
        drop.addEventListener('drop', e => handleFile(e.dataTransfer.files[0]));
        input.addEventListener('change', () => handleFile(input.files[0]));
        document.getElementById('pcFileRemove').addEventListener('click', resetFile);

        // AJAX submit with upload progress
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors();

            let hasError = false;
            if (!document.getElementById('day').value) { setFieldError('day', 'Day is required.'); hasError = true; }
            if (!titleInput.value.trim()) { setFieldError('title', 'Title is required.'); hasError = true; }
            if (!isEdit && !input.files.length) { setFieldError('video', 'Please upload a video.'); hasError = true; }
            if (hasError) return;

            const progress = document.getElementById('pcProgress');
            const bar = document.getElementById('pcProgressBar');
            const pct = document.getElementById('pcProgressPct');
            const label = document.getElementById('pcProgressLabel');
            const hasFile = input.files.length > 0;

            submitBtn.disabled = true;
            submitBtn.querySelector('span').textContent = hasFile ? 'Uploading…' : 'Saving…';
            if (hasFile) { progress.style.display = 'block'; bar.style.width = '0%'; pct.textContent = '0%'; label.textContent = 'Uploading…'; }

            const xhr = new XMLHttpRequest();
            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');

            xhr.upload.addEventListener('progress', function (ev) {
                if (!ev.lengthComputable) return;
                const p = Math.round((ev.loaded / ev.total) * 100);
                bar.style.width = p + '%';
                pct.textContent = p + '%';
                if (p === 100) label.textContent = 'Processing…';
            });

            xhr.onload = function () {
                let res = {};
                try { res = JSON.parse(xhr.responseText); } catch (err) {}

                if (xhr.status >= 200 && xhr.status < 300) {
                    label.textContent = 'Done!';
                    window.location.href = res.redirect || '{{ route('placement-classes.index') }}';
                    return;
                }

                submitBtn.disabled = false;
                submitBtn.querySelector('span').textContent = isEdit ? 'Save Changes' : 'Create Class';
                progress.style.display = 'none';

                if (xhr.status === 422 && res.errors) {
                    Object.keys(res.errors).forEach(f => setFieldError(f, res.errors[f][0]));
                    notifyError('Please fix the highlighted fields.');
                } else if (xhr.status === 413) {
                    notifyError('The file is too large for the server.');
                } else {
                    notifyError(res.message || 'Something went wrong. Please try again.');
                }
            };

            xhr.onerror = function () {
                submitBtn.disabled = false;
                submitBtn.querySelector('span').textContent = isEdit ? 'Save Changes' : 'Create Class';
                progress.style.display = 'none';
                notifyError('Network error. Please check your connection.');
            };

            xhr.send(new FormData(form));
        });
    })();
</script>
@endpush
