@extends('layouts.header')

@section('pageTitle')
{{ __('Edit Course') }} | {{ config('app.name') }}
@endsection

@section('section')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="h3">Edit <strong>Course</strong></h1>

        @can('Course List')
        <a href="javascript:history.back()" class="btn btn-theme"><i class="fas fa-arrow-left me-2"></i> Back</a>
        @endcan
    </div>

    <div class="row">
        <div class="col-12 mt-2">
            @can('Course Edit')
            <div class="card">
                <div class="card-body">

                    <form method="POST" action="{{ route('courses.update', encrypt($course->id)) }}" enctype="multipart/form-data" id="edit-course-form">
                        @csrf
                        @method('PUT')

                        <!-- BASIC DETAILS -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Course Title *</label>
                                <input type="text" name="title" class="form-control" value="{{ $course->title }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Course Code</label>
                                <input type="text" name="course_code" class="form-control" value="{{ $course->course_code }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Language *</label>
                                <select name="language" class="form-control select2">
                                    <option value="">Select language</option>
                                    <option value="english" @selected($course->language == 'english')>English</option>
                                    <option value="tamil" @selected($course->language == 'tamil')>Tamil</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Course Language *</label>
                                <input type="text" name="cour_language" class="form-control" value="{{ $course->cour_language }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Categories *</label>
                                <select name="categories" class="form-control select2">
                                    <option value="">Select Category</option>
                                    <option value="Software Development" @selected($course->categories == 'Software Development')>Software Development</option>
                                    <option value="Web Development" @selected($course->categories == 'Web Development')>Web Development</option>
                                    <option value="Business Management" @selected($course->categories == 'Business Management')>Business Management</option>
                                    <option value="IT Infrastructure Management" @selected($course->categories == 'IT Infrastructure Management')>IT Infrastructure Management</option>
                                    <option value="Special Programs" @selected($course->categories == 'Special Programs')>Special Programs</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Release Date</label>
                                <input type="date" name="release_date" class="form-control" value="{{ $course->release_date }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Topics Covered *</label>
                            <textarea name="sub_description" rows="4" class="form-control">{{ $course->sub_description }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Course Type *</label>
                                <select name="course_type" class="form-control select2">
                                    <option value="free" @selected($course->course_type == 'free')>Free</option>
                                    <option value="paid" @selected($course->course_type == 'paid')>Paid</option>
                                    <option value="combo" @selected($course->course_type == 'combo')>Combo</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">MRP Price</label>
                                <input type="number" name="mrp_price" class="form-control" value="{{ $course->mrp_price ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Buy Price</label>
                                <input type="number" name="buy_price" class="form-control" value="{{ $course->buy_price ?? '' }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Single Student Price</label>
                                <input type="number" name="single_student_price" class="form-control" placeholder="Single Price" value="{{ $course->single_student_price ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Group Price</label>
                                <input type="number" name="group_student_price" class="form-control" placeholder="Group Price" value="{{ $course->group_student_price ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Offer Price</label>
                                <input type="number" name="offer_price" class="form-control" placeholder="Offer Price" value="{{ $course->offer_price ?? '' }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">With Certificate *</label>
                                <input type="number" name="with_certificate" class="form-control" value="{{ $course->with_certificate }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Recorded Content *</label>
                                <input type="number" name="recorded_content" class="form-control" value="{{ $course->recorded_content }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Course Image</label>
                                <input type="file" name="image" class="form-control">
                                @if ($course->image)
                                <img src="{{ asset('uploads/courses/' . $course->image) }}" class="img-thumbnail mt-2" width="150">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Syllabus PDF</label>
                                <input type="file" name="syllabus_pdf" class="form-control" accept=".pdf">
                                @if ($course->syllabus_pdf)
                                <a href="{{ asset('uploads/courses/' . $course->syllabus_pdf) }}" target="_blank" class="d-block mt-2">
                                    <i class="bi bi-file-earmark-pdf"></i> View Current Syllabus
                                </a>
                                @endif
                            </div>
                        </div>

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">About Course *</h6>
                            </div>
                            <div class="card-body">
                                <textarea name="about_course" rows="4" class="form-control">{{ $course->about_course }}</textarea>
                            </div>
                        </div>

                        <!-- CURRICULA -->
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Courses *</h6>
                            </div>
                            <div class="card-body">
                                <div id="curricula-wrapper">
                                    @foreach ($course->curricula as $cIndex => $curriculum)
                                    <div class="card mb-3 curriculum-block">
                                        <div class="card-body">
                                            <input type="hidden" name="curricula[{{ $cIndex }}][id]" value="{{ $curriculum->id }}">
                                            <label class="form-label">Course Title *</label>
                                            <div class="d-flex gap-2 mb-2">
                                                <input type="text" name="curricula[{{ $cIndex }}][title]" class="form-control" value="{{ $curriculum->title }}">
                                                <button type="button" class="btn btn-sm btn-danger remove-curriculum">X</button>
                                            </div>

                                            <label class="form-label">Course Description</label>
                                            <div class="mb-3">
                                                <textarea name="curricula[{{ $cIndex }}][description]" class="form-control" rows="2" placeholder="Curriculum section description">{{ $curriculum->description ?? '' }}</textarea>
                                            </div>

                                            <div class="descriptions-wrapper">
                                                @foreach ($curriculum->descriptions as $dIndex => $desc)
                                                <div class="row description-row mb-2">
                                                    <input type="hidden" name="curricula[{{ $cIndex }}][descriptions][{{ $dIndex }}][id]" value="{{ $desc->id }}">
                                                    <div class="col-10">
                                                        <input name="curricula[{{ $cIndex }}][descriptions][{{ $dIndex }}][description]" class="form-control" value="{{ $desc->description }}">
                                                    </div>
                                                    <div class="col-2">
                                                        <button type="button" class="btn btn-sm btn-danger remove-description">X</button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>

                                            <button type="button" class="btn btn-sm btn-outline-secondary add-description">+
                                                Add Description</button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-curriculum">+ Add
                                    Section</button>
                            </div>
                        </div>

                        <!-- ================= WHY CHOOSE US ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Why Choose Us</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Main Title</label>
                                        <input type="text" name="why_us_title" class="form-control" placeholder="E.g. Why We're the Best Online Python Course for You" value="{{ $course->why_us_title ?? '' }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Main Description</label>
                                        <textarea name="why_us_description" rows="3" class="form-control" placeholder="Description under the title">{{ $course->why_us_description ?? '' }}</textarea>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <!-- Feature 1 -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Heading 1</label>
                                        <input type="text" name="why_us_heading_1" class="form-control mb-2" placeholder="E.g. Beginner-Friendly Curriculum" value="{{ $course->why_us_heading_1 ?? '' }}">
                                        <textarea name="why_us_desc_1" rows="2" class="form-control" placeholder="Heading 1 description">{{ $course->why_us_desc_1 ?? '' }}</textarea>
                                    </div>
                                    <!-- Feature 2 -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Heading 2</label>
                                        <input type="text" name="why_us_heading_2" class="form-control mb-2" placeholder="E.g. Learn From Industry Experts" value="{{ $course->why_us_heading_2 ?? '' }}">
                                        <textarea name="why_us_desc_2" rows="2" class="form-control" placeholder="Heading 2 description">{{ $course->why_us_desc_2 ?? '' }}</textarea>
                                    </div>
                                    <!-- Feature 3 -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Heading 3</label>
                                        <input type="text" name="why_us_heading_3" class="form-control mb-2" placeholder="E.g. Certificate You Can Showcase" value="{{ $course->why_us_heading_3 ?? '' }}">
                                        <textarea name="why_us_desc_3" rows="2" class="form-control" placeholder="Heading 3 description">{{ $course->why_us_desc_3 ?? '' }}</textarea>
                                    </div>
                                    <!-- Feature 4 -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Heading 4</label>
                                        <input type="text" name="why_us_heading_4" class="form-control mb-2" placeholder="E.g. Flexible Online Learning" value="{{ $course->why_us_heading_4 ?? '' }}">
                                        <textarea name="why_us_desc_4" rows="2" class="form-control" placeholder="Heading 4 description">{{ $course->why_us_desc_4 ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ================= SKILLS YOU'LL GAIN ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Skills You'll Gain</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Main Title</label>
                                        <input type="text" name="skills_title" class="form-control" placeholder="E.g. Skills You'll Gain From Our Python Course" value="{{ $course->skills_title ?? '' }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Main Description</label>
                                        <textarea name="skills_description" rows="3" class="form-control" placeholder="Description under the title">{{ $course->skills_description ?? '' }}</textarea>
                                    </div>
                                </div>

                                <hr>
                                <label class="form-label">Dynamic Skills List</label>
                                <div id="skills-wrapper">
                                    @if(isset($course->skills) && is_iterable($course->skills) && count($course->skills) > 0)
                                    @foreach($course->skills as $skill)
                                    <div class="d-flex gap-2 mb-2 skill-item">
                                        <input type="text" name="skills[]" class="form-control" placeholder="Skill name (e.g. Python Programming)" value="{{ is_string($skill) ? $skill : (is_array($skill) ? ($skill['name'] ?? $skill['title'] ?? '') : ($skill->name ?? $skill->title ?? '')) }}">
                                        <button type="button" class="btn btn-danger remove-skill">X</button>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="d-flex gap-2 mb-2 skill-item">
                                        <input type="text" name="skills[]" class="form-control" placeholder="Skill name (e.g. Python Programming)">
                                        <button type="button" class="btn btn-danger remove-skill">X</button>
                                    </div>
                                    @endif
                                </div>

                                <button type="button" id="add-skill" class="btn btn-sm btn-outline-primary mt-2">
                                    + Add Skill
                                </button>
                            </div>
                        </div>

                        <!-- LEARNING POINTS -->
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Learning Points</h6>
                            </div>
                            <div class="card-body">

                                <div class="row mb-3">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Points in Learning Title</label>
                                        <input type="text" name="learning_title" class="form-control" placeholder="Enter learning points title" value="{{ $course->learning_title }}">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Points in Learning Description</label>
                                        <textarea name="learning_description" rows="3" class="form-control" placeholder="Enter learning points description">{{ $course->learning_description }}</textarea>
                                    </div>
                                </div>

                                <div id="leans-wrapper">
                                    @foreach ($course->leans as $lIndex => $lean)
                                    <div class="mb-2 lean-item d-flex gap-2">
                                        <input type="hidden" name="leans[{{ $lIndex }}][id]" value="{{ $lean->id }}">
                                        <input type="text" name="leans[{{ $lIndex }}][title]" class="form-control" value="{{ $lean->title }}" placeholder="Title">
                                        <input type="text" name="leans[{{ $lIndex }}][description]" class="form-control" value="{{ $lean->description }}" placeholder="Description">
                                        <button type="button" class="btn btn-sm btn-danger remove-lean">X</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-lean">+ Add
                                    Learning
                                    Point</button>
                            </div>
                        </div>

                        <!-- BENEFITS -->
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Benefits</h6>
                            </div>
                            <div class="card-body">
                                <div id="benefits-wrapper">
                                    @foreach ($course->benefits as $bIndex => $benefit)
                                    <div class="mb-2 benefit-item d-flex gap-2">
                                        <input type="text" name="benefits[{{ $bIndex }}]" class="form-control" value="{{ $benefit->description }}" placeholder="Benefit">
                                        <button type="button" class="btn btn-sm btn-danger remove-benefit">X</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-benefit">+ Add
                                    Benefit</button>
                            </div>
                        </div>

                        <!-- REQUIREMENTS -->
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Requirements</h6>
                            </div>
                            <div class="card-body">
                                <div id="requirements-wrapper">
                                    @foreach ($course->requirements as $rIndex => $req)
                                    <div class="d-flex gap-2 mb-2 requirement-item">
                                        <input type="text" name="requirements[{{ $rIndex }}]" class="form-control" value="{{ $req->description }}">
                                        <button type="button" class="btn btn-sm btn-danger remove-requirement">X</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-requirement">+ Add
                                    Requirement</button>
                            </div>
                        </div>

                        <!-- ================= FAQS ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">FAQs</h6>
                            </div>
                            <div class="card-body">
                                <div id="faqs-wrapper">
                                    @foreach ($course->faqs as $fIndex => $faq)
                                    <div class="d-flex gap-2 mb-2 faq-item">
                                        <input type="text" name="faqs[{{ $fIndex }}][question]" class="form-control" value="{{ $faq->question }}" placeholder="Question">
                                        <input type="text" name="faqs[{{ $fIndex }}][answer]" class="form-control" value="{{ $faq->answer }}" placeholder="Answer">
                                        <button type="button" class="btn btn-sm btn-danger remove-faq">X</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-faq">+ Add
                                    FAQ</button>
                            </div>
                        </div>

                        <!-- ================= TESTIMONIALS ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Testimonials</h6>
                            </div>
                            <div class="card-body">
                                <div id="testimonials-wrapper">
                                    @foreach ($course->testimonials as $tIndex => $testimonial)
                                    <div class="d-flex gap-2 mb-2 testimonial-item align-items-start border p-2">
                                        <div class="flex-grow-1">
                                            <input type="text" name="testimonials[{{ $tIndex }}][name]" class="form-control mb-2" value="{{ $testimonial->name }}" placeholder="Student Name">
                                            <textarea name="testimonials[{{ $tIndex }}][content]" class="form-control mb-2" rows="2" placeholder="Testimonial Content">{{ $testimonial->content }}</textarea>

                                            <div class="d-flex gap-2 align-items-center mb-2">
                                                @if($testimonial->image)
                                                <img src="{{ asset('uploads/testimonials/'.$testimonial->image) }}" alt="testimonial image" width="40" height="40" class="rounded-circle object-fit-cover">
                                                <input type="hidden" name="testimonials[{{ $tIndex }}][old_image]" value="{{ $testimonial->image }}">
                                                @endif
                                                <input type="file" name="testimonials[{{ $tIndex }}][image]" class="form-control" accept="image/*">
                                                <select name="testimonials[{{ $tIndex }}][rating]" class="form-select">
                                                    <option value="5" @selected($testimonial->rating == '5')>5 Stars</option>
                                                    <option value="4.5" @selected($testimonial->rating == '4.5')>4.5 Stars</option>
                                                    <option value="4" @selected($testimonial->rating == '4')>4 Stars</option>
                                                    <option value="3.5" @selected($testimonial->rating == '3.5')>3.5 Stars</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-danger remove-testimonial">X</button>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="add-testimonial">+ Add
                                    Testimonial</button>
                            </div>
                        </div>


                        <!-- ================= END CARD ================= -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">End Card Title</label>
                                <input type="text" name="end_card_title" class="form-control" placeholder="Enter end card title" value="{{ isset($course) ? $course->end_card_title : '' }}">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="form-label">End Card Description</label>
                                <textarea name="end_card_description" class="form-control" rows="3" placeholder="Enter end card description">{{ isset($course) ? $course->end_card_description : '' }}</textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-theme float-end" id="edit-course-submit">
                            <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                            Submit
                        </button>
                    </form>

                </div>
            </div>
            @endcan
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/course.js') }}"></script>
<script>
    $('.select2').select2();

    // INDEX COUNTERS (start from existing count)
    let curriculumIndex = {{ $course->curricula->count() }};
    let leanIndex = {{ $course->leans->count() }};
    let benefitIndex = {{ $course->benefits->count() }};
    let requirementIndex = {{ $course->requirements->count() }};
    let faqIndex = {{ $course->faqs->count() }};
    let testimonialIndex = {{ $course->testimonials->count() }};

    /* ===================== ADD CURRICULUM ===================== */
    $('#add-curriculum').on('click', function() {
        $('#curricula-wrapper').append(`
        <div class="card mb-3 curriculum-block">
            <div class="card-body">
                <div class="d-flex gap-2 mb-2">
                    <input type="text" name="curricula[${curriculumIndex}][title]" class="form-control" placeholder="Section Title">
                    <button type="button" class="btn btn-sm btn-danger remove-curriculum">X</button>
                </div>

                <label class="form-label mt-2">Course Description</label>
                <div class="mb-3">
                    <textarea name="curricula[${curriculumIndex}][description]" class="form-control" rows="2" placeholder="Curriculum section description"></textarea>
                </div>

                <div class="descriptions-wrapper">
                    <div class="row description-row mb-2">
                        <div class="col-12 mb-2 description-item d-flex gap-2">
                            <input type="text" name="curricula[${curriculumIndex}][descriptions][0][description]" class="form-control" placeholder="Description">
                            <button type="button" class="btn btn-sm btn-danger remove-description">X</button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary add-description">+ Add Description</button>
            </div>
        </div>`);
        curriculumIndex++;
    });

    /* ===================== ADD DESCRIPTION (2 per row) ===================== */
    $('body').on('click', '.add-description', function() {
        let wrapper = $(this).siblings('.descriptions-wrapper');
        let sectionIdx = $(this).closest('.curriculum-block').find('input[name^="curricula"]').attr('name')
            .match(/\d+/)[0];
        let count = wrapper.find('.description-item').length;

        // Create new row for every 2 items
        let row;
        if (count % 2 === 0) {
            row = $('<div class="row description-row mb-2"></div>');
            wrapper.append(row);
        } else {
            row = wrapper.find('.description-row').last();
            if (row.length === 0) {
                row = $('<div class="row description-row mb-2"></div>');
                wrapper.append(row);
            }
        }

        let col = $(`
            <div class="col-12 mb-2 description-item d-flex gap-2">
                <input type="text" name="curricula[${sectionIdx}][descriptions][${count}][description]" class="form-control" placeholder="Description">
                <button type="button" class="btn btn-sm btn-danger remove-description">X</button>
            </div>
        `);
        row.append(col);
    });

    /* ===================== REMOVE DESCRIPTION (MIN 1) ===================== */
    $('body').on('click', '.remove-description', function() {
        let wrapper = $(this).closest('.descriptions-wrapper');
        if (wrapper.find('.description-item').length > 1) {
            $(this).closest('.description-item').remove();
        } else {
            alert('Minimum 1 description required');
        }
    });

    /* ===================== REMOVE CURRICULUM (MIN 1) ===================== */
    $('body').on('click', '.remove-curriculum', function() {
        if ($('.curriculum-block').length > 1) {
            $(this).closest('.curriculum-block').remove();
        } else {
            alert('Minimum 1 curriculum required');
        }
    });

    /* ===================== ADD LEARNING POINT ===================== */
    $('#add-lean').click(function() {
        $('#leans-wrapper').append(`
        <div class="mb-2 lean-item d-flex gap-2">
            <input type="text" name="leans[${leanIndex}][title]" class="form-control" placeholder="Title">
            <input type="text" name="leans[${leanIndex}][description]" class="form-control" placeholder="Description">
            <button type="button" class="btn btn-sm btn-danger remove-lean">X</button>
        </div>`);
        leanIndex++;
    });

    /* ===================== REMOVE LEARNING POINT (MIN 1) ===================== */
    $('body').on('click', '.remove-lean', function() {
        if ($('.lean-item').length > 1) {
            $(this).closest('.lean-item').remove();
        } else {
            alert('Minimum 1 learning point required');
        }
    });

    /* ===================== ADD BENEFIT ===================== */
    $('#add-benefit').click(function() {
        $('#benefits-wrapper').append(`
        <div class="mb-2 benefit-item d-flex gap-2">
            <input type="text" name="benefits[${benefitIndex}]" class="form-control" placeholder="Benefit">
            <button type="button" class="btn btn-sm btn-danger remove-benefit">X</button>
        </div>`);
        benefitIndex++;
    });

    /* ===================== REMOVE BENEFIT (MIN 1) ===================== */
    $('body').on('click', '.remove-benefit', function() {
        if ($('.benefit-item').length > 1) {
            $(this).closest('.benefit-item').remove();
        } else {
            alert('Minimum 1 benefit required');
        }
    });

    /* ===================== ADD REQUIREMENT ===================== */
    $('#add-requirement').click(function() {
        $('#requirements-wrapper').append(`
        <div class="d-flex gap-2 mb-2 requirement-item">
            <input type="text" name="requirements[${requirementIndex}]" class="form-control" placeholder="Course requirement">
            <button type="button" class="btn btn-sm btn-danger remove-requirement">X</button>
        </div>`);
        requirementIndex++;
    });

    /* ===================== REMOVE REQUIREMENT (MIN 1) ===================== */
    $('body').on('click', '.remove-requirement', function() {
        if ($('.requirement-item').length > 1) {
            $(this).closest('.requirement-item').remove();
        } else {
            alert('Minimum 1 requirement required');
        }
    });

    /* ===================== ADD FAQ ===================== */
    $('#add-faq').click(function() {
        $('#faqs-wrapper').append(`
        <div class="d-flex gap-2 mb-2 faq-item">
            <input type="text" name="faqs[${faqIndex}][question]" class="form-control" placeholder="Question">
            <input type="text" name="faqs[${faqIndex}][answer]" class="form-control" placeholder="Answer">
            <button type="button" class="btn btn-sm btn-danger remove-faq">X</button>
        </div>`);
        faqIndex++;
    });

    /* ===================== REMOVE FAQ (MIN 1) ===================== */
    $('body').on('click', '.remove-faq', function() {
        if ($('.faq-item').length > 1) {
            $(this).closest('.faq-item').remove();
        } else {
            alert('Minimum 1 FAQ required');
        }
    });

    /* ===================== ADD TESTIMONIAL ===================== */
    $('#add-testimonial').click(function() {
        $('#testimonials-wrapper').append(`
        <div class="d-flex gap-2 mb-2 testimonial-item align-items-start border p-2">
            <div class="flex-grow-1">
                <input type="text" name="testimonials[${testimonialIndex}][name]" class="form-control mb-2" placeholder="Student Name">
                <textarea name="testimonials[${testimonialIndex}][content]" class="form-control mb-2" rows="2" placeholder="Testimonial Content"></textarea>
                <div class="d-flex gap-2">
                    <input type="file" name="testimonials[${testimonialIndex}][image]" class="form-control" accept="image/*">
                    <select name="testimonials[${testimonialIndex}][rating]" class="form-select">
                        <option value="5">5 Stars</option>
                        <option value="4.5">4.5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3.5">3.5 Stars</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn btn-danger remove-testimonial">X</button>
        </div>`);
        testimonialIndex++;
    });

    /* ===================== REMOVE TESTIMONIAL ===================== */
    $('body').on('click', '.remove-testimonial', function() {
        $(this).closest('.testimonial-item').remove();
    });

    // Submit Form via AJAX
    $('#edit-course-form').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = new FormData(this);
        let btn = $('#edit-course-submit');
        let originalText = btn.html();

        // Clear previous errors
        form.find('.invalid-feedback').remove();
        form.find('.is-invalid').removeClass('is-invalid');

        btn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm"></span> Updating...');

        $.ajax({
            url: form.attr('action')
            , type: "POST"
            , data: formData
            , processData: false
            , contentType: false
            , success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.location.href = "{{ route('courses.index') }}";
                    }, 1000);
                } else {
                    toastr.error(response.message || 'Something went wrong');
                    btn.prop('disabled', false).html(originalText);
                }
            }
            , error: function(xhr) {
                btn.prop('disabled', false).html(originalText);
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        // Handle array fields
                        let name = key;
                        if (name.includes('.')) {
                            let parts = name.split('.');
                            name = parts[0] + '[' + parts.slice(1).join('][') + ']';
                        }
                        // Special case for arrays that end with [] in the form name
                        let $input = form.find('[name="' + name + '"]');
                        if ($input.length === 0) {
                            $input = form.find('[name="' + name + '[]"]');
                        }

                        $input.addClass('is-invalid');

                        let $target = $input;
                        if ($input.hasClass('select2-hidden-accessible')) {
                            $target = $input.next('.select2-container');
                        }

                        if ($input.parent().hasClass('d-flex')) {
                            let $wrapElements = $input.hasClass('select2-hidden-accessible') ? $input.add($target) : $input;
                            $wrapElements.wrapAll('<div class="input-wrapper flex-grow-1 w-100"></div>');
                        }

                        $target.after('<span class="invalid-feedback" role="alert" style="display: block;">' + value[0] + '</span>');
                    });
                    toastr.error('Please check the highlighted fields.');
                } else {
                    toastr.error('An error occurred. Please try again.');
                }
            }
        });
    });

    // Course Type Free Logic
    function handleCourseType() {
        if ($('select[name="course_type"]').val() === 'free') {
            $('input[name="mrp_price"]').val('0').prop('readonly', true);
            $('input[name="buy_price"], input[name="single_student_price"], input[name="group_student_price"], input[name="offer_price"]').val('0').prop('readonly', true);
        } else {
            $('input[name="mrp_price"], input[name="buy_price"], input[name="single_student_price"], input[name="group_student_price"], input[name="offer_price"]').prop('readonly', false);
        }
    }

    $('select[name="course_type"]').on('change', handleCourseType);
    handleCourseType(); // initialize on load


    // --- SKILLS ---
    let skillIndex = {{ $course->skills->count() }};
    $('#add-skill').on('click', function() {
        $('#skills-wrapper').append(`
            <div class="d-flex gap-2 mb-2 skill-item">
                <input type="text" name="skills[]" class="form-control" placeholder="Skill name (e.g. Python Programming)">
                <button type="button" class="btn btn-danger remove-skill">X</button>
            </div>
        `);
        skillIndex++;
    });

    $(document).on('click', '.remove-skill', function() {
        $(this).closest('.skill-item').remove();
    });

</script>
@endpush

