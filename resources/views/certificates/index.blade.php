@extends('layouts.master')

@section('pageTitle', 'Certificate Dispatch')

@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/certificates.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
        <style>
            /* Certificate template styles (inline for html2pdf isolation) */
            .certificate_template_wrapper {
                background: #f4f4f4;
                padding: 20px;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
            }
            .certificate_main {
                width: 980px;
                height: 660px;
                background: #fff;
                padding: 10px;
                box-sizing: border-box;
                box-shadow: 0 15px 45px rgba(0,0,0,0.1);
                position: relative;
                overflow: hidden;
                flex-shrink: 0;
            }
            .cert_outer_frame {
                border: 1px solid #c5a059;
                padding: 6px;
                height: 100%;
                position: relative;
                background: #fff;
            }
            .cert_academic_border {
                border: 15px solid #22346b;
                height: 100%;
                padding: 8px;
                position: relative;
            }
            .cert_inner_border {
                border: 2px double #c5a059;
                height: 100%;
                padding: 20px 60px;
                position: relative;
                background: #fff;
                display: flex;
                flex-direction: column;
            }
            .cert_inner_border::before {
                content: '';
                position: absolute;
                width: 60px; height: 60px;
                top: 10px; left: 10px;
                border-top: 4px solid #c5a059;
                border-left: 4px solid #c5a059;
                z-index: 5;
            }
            .cert_inner_border::after {
                content: '';
                position: absolute;
                width: 60px; height: 60px;
                bottom: 10px; right: 10px;
                border-bottom: 4px solid #c5a059;
                border-right: 4px solid #c5a059;
                z-index: 5;
            }
            .verified_ribbon {
                position: absolute;
                top: 25px; right: 15px;
                background: linear-gradient(135deg, #22346b 0%, #24b8ec 100%);
                color: white;
                padding: 6px 30px;
                font-weight: 700;
                font-size: 0.8rem;
                clip-path: polygon(0% 0%, 100% 0%, 92% 50%, 100% 100%, 0% 100%);
                z-index: 10;
                letter-spacing: 2px;
            }
            .cert_header_row { display: flex; justify-content: center; margin-bottom: 15px; }
            .cert_logo_img { height: 60px; }
            .cert_content_body { text-align: center; position: relative; z-index: 2; margin-bottom: 5px; }
            .cert_main_title {
                font-family: 'Playfair Display', serif;
                font-size: 2.8rem;
                font-weight: 900;
                color: #22346b;
                letter-spacing: 4px;
                margin: 0; line-height: 1;
            }
            .cert_divider_ornate {
                width: 300px;
                height: 2px;
                background: linear-gradient(to right, transparent, #c5a059, transparent);
                margin: 10px auto 20px;
                position: relative;
            }
            .cert_divider_ornate::after {
                content: '◈';
                position: absolute;
                top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                background: #fff;
                padding: 0 12px;
                color: #c5a059;
                font-size: 1rem;
            }
            .cert_intro_text { font-size: 0.95rem; color: #666; font-style: italic; margin-bottom: 10px; }
            .cert_user_name {
                font-family: 'Playfair Display', serif;
                font-size: 2.5rem;
                font-weight: 700;
                color: #1a1a1a;
                margin-bottom: 10px;
                border-bottom: 1px solid #d4af37;
                display: inline-block;
                padding: 0 25px 5px;
            }
            .cert_completion_text { font-size: 0.95rem; color: #666; margin-bottom: 5px; }
            .cert_course_title {
                font-size: 1.4rem;
                font-weight: 800;
                color: #22346b;
                margin-bottom: 10px;
                max-width: 85%;
                margin-left: auto;
                margin-right: auto;
            }
            .cert_gold_seal {
                position: relative;
                margin: 5px auto 10px;
                width: 90px; height: 90px;
                background: radial-gradient(#f9d976, #b8860b);
                border-radius: 50%;
                box-shadow: 0 0 0 5px rgba(184,134,11,0.2);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 5;
            }
            .seal_inner {
                border: 2px solid white;
                width: 80px; height: 80px;
                border-radius: 50%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: #fff;
                font-size: 0.6rem;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: 1px;
                text-align: center;
            }
            .cert_footer_row { display: flex; justify-content: space-between; align-items: flex-end; margin-top: auto; }
            .cert_footer_left { text-align: left; border-left: 3px solid #22346b; padding-left: 15px; }
            .cert_footer_right { text-align: center; }
            .cert_meta_info { font-size: 0.85rem; color: #444; line-height: 1.8; }
            .cert_meta_info strong { color: #22346b; }
            .cert_signature_area { width: 250px; }
            .cert_signature_line { border-top: 2px solid #333; padding-top: 8px; font-weight: 700; font-size: 1rem; }
            .cert_signature_role { font-size: 0.75rem; color: #666; text-transform: uppercase; letter-spacing: 2px; }
            .cert_footer_note { font-size: 0.65rem; color: #999; margin-top: 10px; text-transform: uppercase; }

            /* Download spinner */
            .cert-btn-download.loading {
                opacity: 0.75;
                pointer-events: none;
            }
        </style>
    @endpush

    <div class="cert-page">

        {{-- Header Section --}}
        <div class="cert-header">
            <h2>Certificate dispatch</h2>
            <div class="cert-dropdown">
                <form method="GET" action="{{ route('certificates.index') }}" id="courseFilterForm">
                    <select name="course_id" id="courseFilter" onchange="document.getElementById('courseFilterForm').submit()">
                        <option value="all" {{ (!$courseId || $courseId === 'all') ? 'selected' : '' }}>All courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ (string)$courseId === (string)$course->id ? 'selected' : '' }}>
                                {{ $course->title }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        {{-- List Section --}}
        <div class="cert-list">
            @forelse($certificates as $cert)
                <div class="cert-card">

                    {{-- Left Side: Icon + Details --}}
                    <div class="cert-info">
                        <div class="cert-icon-box">
                            <i class="fa-solid fa-cube"></i>
                        </div>
                        <div class="cert-details">
                            <h4 class="cert-name">{{ $cert['name'] }}</h4>
                            <p class="cert-course">{{ $cert['course'] }}</p>
                            <div class="cert-meta">
                                <span><i class="fa-regular fa-clock"></i> {{ $cert['date'] }}</span>
                                @if($cert['grade'] !== '—')
                                    <span><i class="fa-solid fa-certificate"></i> {{ $cert['grade'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Status + Action --}}
                    <div class="cert-actions">
                        <span class="cert-status status-{{ strtolower($cert['status']) }}">
                            {{ $cert['status'] }}
                        </span>

                        @if($cert['status'] === 'PENDING')
                            <button class="cert-btn-download btn-disabled" disabled>
                                <i class="fa-solid fa-arrow-down"></i> Download
                            </button>
                        @else
                            <button
                                class="cert-btn-download"
                                onclick="downloadCertificate(this, '{{ addslashes($cert['name']) }}', '{{ addslashes($cert['course']) }}', '{{ $cert['date'] }}', {{ $cert['id'] }})">
                                <i class="fa-solid fa-arrow-down"></i> Download
                            </button>
                        @endif
                    </div>

                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    <i class="fa-solid fa-certificate fa-2x mb-2 d-block"></i>
                    No certificates found{{ $courseId && $courseId !== 'all' ? ' for the selected course' : '' }}.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Hidden Certificate Template for PDF Generation --}}
    <div id="certTemplateContainer" style="position:absolute;left:-9999px;top:0;opacity:0;pointer-events:none;z-index:-1;">
        <div class="certificate_template_wrapper" id="certTemplateToPrint">
            <div class="certificate_main">
                <div class="cert_outer_frame">
                    <div class="cert_academic_border">
                        <div class="cert_inner_border">

                            <div class="verified_ribbon">VERIFIED ✓</div>

                            <div class="cert_header_row">
                                <img src="{{ asset('assets/images/velearn-logo.png') }}"
                                     alt="Velearn"
                                     class="cert_logo_img"
                                     crossorigin="anonymous">
                            </div>

                            <div class="cert_content_body">
                                <h1 class="cert_main_title">CERTIFICATE</h1>
                                <div class="cert_divider_ornate"></div>
                                <p class="cert_intro_text">This is to certify that</p>
                                <h2 class="cert_user_name" id="certStudentName">Student Name</h2>
                                <p class="cert_completion_text">has successfully completed all academic requirements for</p>
                                <h4 class="cert_course_title" id="certCourseTitle">Course Title</h4>
                            </div>

                            <div class="cert_gold_seal">
                                <div class="seal_inner">
                                    ★<br>Official<br>Academy
                                </div>
                            </div>

                            <div class="cert_footer_row">
                                <div class="cert_footer_left">
                                    <div class="cert_meta_info">
                                        <p class="mb-0"><strong>Certificate ID:</strong> <span id="certId">VL-000000</span></p>
                                        <p class="mb-0"><strong>Issue Date:</strong> <span id="certIssueDate"></span></p>
                                        <p class="cert_footer_note">*Digital Verification: velearn.in/verify</p>
                                    </div>
                                </div>
                                <div class="cert_footer_right">
                                    <div class="cert_signature_area">
                                        <div class="cert_signature_line">Velearn Academy</div>
                                        <div class="cert_signature_role">Managing Director</div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadCertificate(btn, studentName, courseTitle, issueDate, admissionId) {
            // Update template content
            document.getElementById('certStudentName').textContent = studentName;
            document.getElementById('certCourseTitle').textContent = courseTitle;
            document.getElementById('certIssueDate').textContent = issueDate;
            document.getElementById('certId').textContent = 'VL-' + String(admissionId).padStart(6, '0');

            // Show loading state on button
            const originalHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';
            btn.classList.add('loading');

            const element = document.getElementById('certTemplateToPrint');

            const opt = {
                margin: 5,
                filename: courseTitle.replace(/\s+/g, '_') + '_Certificate.pdf',
                image: { type: 'jpeg', quality: 1 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    letterRendering: true,
                    backgroundColor: '#ffffff',
                    logging: false,
                    width: 1000,
                    height: 680
                },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' },
                pagebreak: { mode: 'avoid-all' }
            };

            setTimeout(function() {
                html2pdf()
                    .set(opt)
                    .from(element)
                    .save()
                    .then(function() {
                        btn.innerHTML = originalHtml;
                        btn.classList.remove('loading');
                    })
                    .catch(function(err) {
                        console.error('PDF error:', err);
                        btn.innerHTML = originalHtml;
                        btn.classList.remove('loading');
                        toastr.error('Failed to generate certificate. Please try again.');
                    });
            }, 300);
        }
    </script>
@endpush