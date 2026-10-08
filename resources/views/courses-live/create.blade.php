@extends('layouts.header')

@section('pageTitle')
{{ __('Add Live Course') }} | {{ config('app.name') }}
@endsection

@section('section')
<div class="container-fluid p-0">
    <div class="align-items-center d-flex justify-content-between">
        <h1 class="h3">Add <strong>Live Course</strong></h1>

        @can('Course List')
        <a href="{{ route('courses.live.index') }}" class="btn btn-theme">Back</a>
        @endcan
    </div>

    <div class="row">
        @can('Course Create')
        <div class="col-12 col-xl-12 mt-1">

            <div class="card">
                <div class="card-body">

                    <form method="POST" action="{{ route('courses.live.store') }}" enctype="multipart/form-data" id="create-course-form">
                        @csrf

                        <!-- ================= BASIC DETAILS ================= -->

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Course Title *</label>
                                <input type="text" name="title" class="form-control" required  placeholder="Enter course title">
                            </div>


                            <div class="col-md-4">
                                <label class="form-label">Course Code *</label>
                                <input type="text" name="course_code" class="form-control" required  placeholder="Enter course code">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Language *</label>
                                <select name="language" class="form-control select2" required >
                                    <option value="">Select language</option>
                                    <option value="english">English</option>
                                    <option value="tamil">Tamil</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Course Language *</label>
                                <input type="text" name="cour_language" class="form-control" required  placeholder="e.g. English">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Categories *</label>
                                <select name="categories" class="form-control select2" required >
                                    <option value="">Select Category</option>
                                    <option value="Software Development">Software Development</option>
                                    <option value="Web Development">Web Development</option>
                                    <option value="Business Management">Business Management</option>
                                    <option value="IT Infrastructure Management">IT Infrastructure Management</option>
                                    <option value="Special Programs">Special Programs</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Release Date *</label>
                                <input type="date" name="release_date" class="form-control" required >
                            </div>
                        </div>

                        <!-- ================= Description ================= -->

                        <div class="mb-3">
                            <label class="form-label">Topics Covered *</label>
                            <textarea name="sub_description" rows="4" class="form-control" required  placeholder="Describe the course in detail"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Course Type *</label>
                                <select name="course_type" class="form-control select2" required >
                                    <option value="live">Live</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">MRP Price *</label>
                                <input type="number" name="mrp_price" class="form-control" required  placeholder="Enter MRP price">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Buy Price *</label>
                                <input type="number" name="buy_price" class="form-control" required  placeholder="Enter selling price">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Single Student Price *</label>
                                <input type="number" name="single_student_price" class="form-control" required  placeholder="Single Price">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Group Price *</label>
                                <input type="number" name="group_student_price" class="form-control" required  placeholder="Group Price">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Offer Price *</label>
                                <input type="number" name="offer_price" class="form-control" required  placeholder="Offer Price">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Live Course Type *</label>
                                <select name="live_course_type" class="form-control select2" required >
                                    <option value="normal">Normal</option>
                                    <option value="fast track">Fast Track</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Duration *</label>
                                <input type="text" name="duration" class="form-control" required  placeholder="Enter duration (e.g. 6 Months, 30 Days)">
                            </div>
                        </div>

                        <!-- ================= OPTIONS ================= -->

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">With Certificate *</label>
                                <input type="number" name="with_certificate" class="form-control" required  placeholder="Enter Modules (or) Course">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Recorded Content *</label>
                                <input type="number" name="recorded_content" class="form-control" required  placeholder="Enter Hourse">
                            </div>
                        </div>

                        <!-- ================= IMAGE ================= -->

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Course Image *</label>
                                <input type="file" name="image" class="form-control" required >
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Course Pdf *</label>
                                <input type="file" name="course_pdf" class="form-control" required  accept=".pdf">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Syllabus PDF *</label>
                                <input type="file" name="syllabus_pdf" class="form-control" required  accept=".pdf">
                            </div>
                        </div>

                        <!-- ================= ABOUT ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">About Course *</h6>
                            </div>
                            <div class="card-body">
                                <textarea name="about_course" rows="4" class="form-control" required  placeholder="Describe the course in detail"></textarea>
                            </div>
                        </div>

                        <!-- ================= CURRICULA ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Courses *</h6>
                            </div>
                            <div class="card-body">

                                <div id="curricula-wrapper">
                                    <div class="card mb-3 curriculum-block">
                                        <div class="card-body">
                                            <label class="form-label">Course Title *</label>
                                            <div class="d-flex gap-2 mb-2">
                                                <input type="text" name="curricula[0][title]" class="form-control" required  placeholder="Curriculum section title">
                                                <button type="button" class="btn btn-danger remove-curriculum">
                                                    X
                                                </button>
                                            </div>

                                            <label class="form-label">Course Description *</label>
                                            <div class="mb-3">
                                                <textarea name="curricula[0][description]" class="form-control" required  rows="2" placeholder="Curriculum section description"></textarea>
                                            </div>

                                            <label class="form-label">Topics Covered *</label>
                                            <div class="descriptions-wrapper">
                                                <div class="d-flex gap-2 mb-2 description-item">
                                                    <input type="text" name="curricula[0][descriptions][0]" class="form-control" required  placeholder="Section description">
                                                    <button type="button" class="btn btn-danger remove-description">
                                                        X
                                                    </button>
                                                </div>
                                            </div>

                                            <button type="button" class="btn btn-sm btn-outline-secondary add-description">
                                                + Add Description
                                            </button>

                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="add-curriculum" class="btn btn-sm btn-outline-primary">
                                    + Add Section
                                </button>
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
                                        <label class="form-label">Main Title *</label>
                                        <input type="text" name="why_us_title" class="form-control" required  placeholder="E.g. Why We're the Best Online Python Course for You">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Main Description *</label>
                                        <textarea name="why_us_description" rows="3" class="form-control" required  placeholder="Description under the title"></textarea>
                                    </div>
                                </div>

                                <hr>

                                <div class="row">
                                    <!-- Feature 1 -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Heading 1 *</label>
                                        <input type="text" name="why_us_heading_1" class="form-control mb-2" required  placeholder="E.g. Beginner-Friendly Curriculum">
                                        <textarea name="why_us_desc_1" rows="2" class="form-control" required  placeholder="Heading 1 description"></textarea>
                                    </div>
                                    <!-- Feature 2 -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Heading 2 *</label>
                                        <input type="text" name="why_us_heading_2" class="form-control mb-2" required  placeholder="E.g. Learn From Industry Experts">
                                        <textarea name="why_us_desc_2" rows="2" class="form-control" required  placeholder="Heading 2 description"></textarea>
                                    </div>
                                    <!-- Feature 3 -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Heading 3 *</label>
                                        <input type="text" name="why_us_heading_3" class="form-control mb-2" required  placeholder="E.g. Certificate You Can Showcase">
                                        <textarea name="why_us_desc_3" rows="2" class="form-control" required  placeholder="Heading 3 description"></textarea>
                                    </div>
                                    <!-- Feature 4 -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Heading 4 *</label>
                                        <input type="text" name="why_us_heading_4" class="form-control mb-2" required  placeholder="E.g. Flexible Online Learning">
                                        <textarea name="why_us_desc_4" rows="2" class="form-control" required  placeholder="Heading 4 description"></textarea>
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
                                        <label class="form-label">Main Title *</label>
                                        <input type="text" name="skills_title" class="form-control" required  placeholder="E.g. Skills You'll Gain From Our Python Course">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Main Description *</label>
                                        <textarea name="skills_description" rows="3" class="form-control" required  placeholder="Description under the title"></textarea>
                                    </div>
                                </div>

                                <hr>
                                <label class="form-label">Dynamic Skills List *</label>
                                <div id="skills-wrapper">
                                    <div class="d-flex gap-2 mb-2 skill-item">
                                        <input type="text" name="skills[]" class="form-control" required  placeholder="Skill name (e.g. Python Programming)">
                                        <button type="button" class="btn btn-danger remove-skill">X</button>
                                    </div>
                                </div>

                                <button type="button" id="add-skill" class="btn btn-sm btn-outline-primary mt-2">
                                    + Add Skill
                                </button>
                            </div>
                        </div>

                        <!-- ================= LEARNING POINTS ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Learning Points</h6>
                            </div>
                            <div class="card-body">

                                <div class="row mb-3">
                                    <div class="col-md-12 mb-2">
                                        <label class="form-label">Points in Learning Title *</label>
                                        <input type="text" name="learning_title" class="form-control" required  placeholder="Enter learning points title">
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">Points in Learning Description *</label>
                                        <textarea name="learning_description" rows="3" class="form-control" required  placeholder="Enter learning points description"></textarea>
                                    </div>
                                </div>

                                <div id="leans-wrapper">
                                    <div class="d-flex gap-2 mb-2 lean-item">
                                        <input type="text" name="leans[0][title]" class="form-control" required  placeholder="Title">
                                        <input type="text" name="leans[0][description]" class="form-control" required  placeholder="Description">
                                        <button type="button" class="btn btn-danger remove-lean">
                                            X
                                        </button>
                                    </div>
                                </div>

                                <button type="button" id="add-lean" class="btn btn-sm btn-outline-primary">
                                    + Add Learning Point
                                </button>
                            </div>
                        </div>

                        <!-- ================= BENEFITS ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Benefits</h6>
                            </div>
                            <div class="card-body">

                                <div id="benefits-wrapper">
                                    <div class="d-flex gap-2 mb-2 benefit-item">
                                        <input type="text" name="benefits[0]" class="form-control" required  placeholder="Course benefit">
                                        <button type="button" class="btn btn-danger remove-benefit">
                                            X
                                        </button>
                                    </div>
                                </div>

                                <button type="button" id="add-benefit" class="btn btn-sm btn-outline-primary">
                                    + Add Benefit
                                </button>
                            </div>
                        </div>

                        <!-- ================= REQUIREMENTS ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Requirements</h6>
                            </div>
                            <div class="card-body">

                                <div id="requirements-wrapper">
                                    <div class="d-flex gap-2 mb-2 requirement-item">
                                        <input type="text" name="requirements[0]" class="form-control" required  placeholder="Course requirement">
                                        <button type="button" class="btn btn-danger remove-requirement">
                                            X
                                        </button>
                                    </div>
                                </div>

                                <button type="button" id="add-requirement" class="btn btn-sm btn-outline-primary">
                                    + Add Requirement
                                </button>
                            </div>
                        </div>

                        <!-- ================= FAQS ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">FAQs</h6>
                            </div>
                            <div class="card-body">

                                <div id="faqs-wrapper">
                                    <div class="d-flex gap-2 mb-2 faq-item">
                                        <input type="text" name="faqs[0][question]" class="form-control" required  placeholder="Question">
                                        <input type="text" name="faqs[0][answer]" class="form-control" required  placeholder="Answer">
                                        <button type="button" class="btn btn-danger remove-faq">
                                            X
                                        </button>
                                    </div>
                                </div>

                                <button type="button" id="add-faq" class="btn btn-sm btn-outline-primary">
                                    + Add FAQ
                                </button>
                            </div>
                        </div>

                        <!-- ================= TESTIMONIALS ================= -->

                        <div class="card mb-4 shadow-sm border-0">
                            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                                <h6 class="mb-0 text-uppercase text-muted fw-bold">Testimonials</h6>
                            </div>
                            <div class="card-body">

                                <div id="testimonials-wrapper">
                                    <div class="d-flex gap-2 mb-2 testimonial-item align-items-start border p-2">
                                        <div class="flex-grow-1">
                                            <input type="text" name="testimonials[0][name]" class="form-control mb-2" required  placeholder="Student Name">
                                            <textarea name="testimonials[0][content]" class="form-control mb-2" required  rows="2" placeholder="Testimonial Content"></textarea>
                                            <div class="d-flex gap-2">
                                                <input type="file" name="testimonials[0][image]" class="form-control" required  accept="image/*">
                                                <select name="testimonials[0][rating]" class="form-select">
                                                    <option value="5">5 Stars</option>
                                                    <option value="4.5">4.5 Stars</option>
                                                    <option value="4">4 Stars</option>
                                                    <option value="3.5">3.5 Stars</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-danger remove-testimonial">X</button>
                                    </div>
                                </div>

                                <button type="button" id="add-testimonial" class="btn btn-sm btn-outline-primary">
                                    + Add Testimonial
                                </button>
                            </div>
                        </div>


                        <!-- ================= END CARD ================= -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">End Card Title *</label>
                                <input type="text" name="end_card_title" class="form-control" required  placeholder="Enter end card title" value="{{ isset($course) ? $course->end_card_title : '' }}">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="form-label">End Card Description *</label>
                                <textarea name="end_card_description" class="form-control" required  rows="3" placeholder="Enter end card description">{{ isset($course) ? $course->end_card_description : '' }}</textarea>
                            </div>
                        </div>

                        <!-- ================= SUBMIT ================= -->

                        <button type="submit" class="btn btn-theme float-end" id="create-course-submit">
                            <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                            Submit
                        </button>

                    </form>

                </div>
            </div>
        </div>
        @endcan
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/live-course.js') }}"></script>

<script>
    $('.select2').select2();

    let curriculumIndex = 1;
    let leanIndex = 1;
    let benefitIndex = 1;
    let requirementIndex = 1;

    // Add Curriculum
    $('#add-curriculum').click(function() {
        $('#curricula-wrapper').append(`
        <div class="card mb-3 curriculum-block">
            <div class="card-body">
                <div class="d-flex gap-2 mb-2">
                    <input type="text"
                        name="curricula[${curriculumIndex}][title]"
                        class="form-control" required 
                        placeholder="Curriculum section title">
                    <button type="button"
                        class="btn btn-danger remove-curriculum">X</button>
                </div>

                <label class="form-label mt-2">Course Description *</label>
                <div class="mb-3">
                    <textarea name="curricula[${curriculumIndex}][description]" class="form-control" required  rows="2" placeholder="Curriculum section description"></textarea>
                </div>


                <div class="descriptions-wrapper">
                    <div class="row description-row mb-2">
                        <div class="col-12 mb-2 description-item d-flex gap-2">
                            <input type="text" name="curricula[${curriculumIndex}][descriptions][0]"
                                class="form-control" required 
                                placeholder="Section description">
                            <button type="button"
                                class="btn btn-danger remove-description">X</button>
                        </div>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-sm btn-outline-secondary add-description">
                    + Add Description
                </button>
            </div>
        </div>`);
        curriculumIndex++;
    });

    // Add Description
    $('body').on('click', '.add-description', function() {
        let wrapper = $(this).siblings('.descriptions-wrapper');
        let section = $(this).closest('.curriculum-block')
            .find('input[name^="curricula"]').attr('name').match(/\d+/)[0];
        let idx = wrapper.find('.description-item').length;

        wrapper.append(`
        <div class="d-flex gap-2 mb-2 description-item">
            <input type="text" name="curricula[${section}][descriptions][${idx}]"
                class="form-control" required 
                rows="2"
                placeholder="Section description">
            <button type="button"
                class="btn btn-danger remove-description">X</button>
        </div>`);
    });

    // Remove curriculum
    $('body').on('click', '.remove-curriculum', function() {
        if ($('.curriculum-block').length > 1) {
            $(this).closest('.curriculum-block').remove();
        } else {
            alert('At least one curriculum is required.');
        }
    });

    // Remove description
    $('body').on('click', '.remove-description', function() {
        let wrapper = $(this).closest('.descriptions-wrapper');
        if (wrapper.find('.description-item').length > 1) {
            $(this).closest('.description-item').remove();
        } else {
            alert('At least one description is required.');
        }
    });

    // Add Learning Point
    $('#add-lean').click(function() {
        $('#leans-wrapper').append(`
        <div class="d-flex gap-2 mb-2 lean-item">
            <input type="text"
                name="leans[${leanIndex}][title]"
                class="form-control" required 
                placeholder="Title">
            <input type="text"
                name="leans[${leanIndex}][description]"
                class="form-control" required 
                placeholder="Description">
            <button type="button"
                class="btn btn-danger remove-lean">X</button>
        </div>`);
        leanIndex++;
    });

    // Remove Learning Point
    $('body').on('click', '.remove-lean', function() {
        if ($('.lean-item').length > 1) {
            $(this).closest('.lean-item').remove();
        } else {
            alert('At least one learning point is required.');
        }
    });

    // Add Benefit
    $('#add-benefit').click(function() {
        $('#benefits-wrapper').append(`
        <div class="d-flex gap-2 mb-2 benefit-item">
            <input type="text"
                name="benefits[${benefitIndex}]"
                class="form-control" required 
                placeholder="Course benefit">
            <button type="button"
                class="btn btn-danger remove-benefit">X</button>
        </div>`);
        benefitIndex++;
    });

    // Remove Benefit
    $('body').on('click', '.remove-benefit', function() {
        if ($('.benefit-item').length > 1) {
            $(this).closest('.benefit-item').remove();
        } else {
            alert('At least one benefit is required.');
        }
    });

    // Add Requirement
    $('#add-requirement').click(function() {
        $('#requirements-wrapper').append(`
        <div class="d-flex gap-2 mb-2 requirement-item">
            <input type="text"
                name="requirements[${requirementIndex}]"
                class="form-control" required 
                placeholder="Course requirement">
            <button type="button"
                class="btn btn-danger remove-requirement">X</button>
        </div>`);
        requirementIndex++;
    });

    // Remove Requirement
    $('body').on('click', '.remove-requirement', function() {
        if ($('.requirement-item').length > 1) {
            $(this).closest('.requirement-item').remove();
        } else {
            alert('At least one requirement is required.');
        }
    });

    let faqIndex = 1;

    // Add FAQ
    $('#add-faq').click(function() {
        $('#faqs-wrapper').append(`
        <div class="d-flex gap-2 mb-2 faq-item">
            <input type="text"
                name="faqs[${faqIndex}][question]"
                class="form-control" required 
                placeholder="Question">
            <input type="text"
                name="faqs[${faqIndex}][answer]"
                class="form-control" required 
                placeholder="Answer">
            <button type="button"
                class="btn btn-danger remove-faq">X</button>
        </div>`);
        faqIndex++;
    });

    // Remove FAQ
    $('body').on('click', '.remove-faq', function() {
        if ($('.faq-item').length > 1) {
            $(this).closest('.faq-item').remove();
        } else {
            alert('At least one FAQ is required.');
        }
    });

    let testimonialIndex = 1;

    // Add Testimonial
    $('#add-testimonial').click(function() {
        $('#testimonials-wrapper').append(`
        <div class="d-flex gap-2 mb-2 testimonial-item align-items-start border p-2">
            <div class="flex-grow-1">
                <input type="text" name="testimonials[${testimonialIndex}][name]" class="form-control mb-2" required  placeholder="Student Name">
                <textarea name="testimonials[${testimonialIndex}][content]" class="form-control mb-2" required  rows="2" placeholder="Testimonial Content"></textarea>
                <div class="d-flex gap-2">
                    <input type="file" name="testimonials[${testimonialIndex}][image]" class="form-control" required  accept="image/*">
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

    // Remove Testimonial
    $('body').on('click', '.remove-testimonial', function() {
        $(this).closest('.testimonial-item').remove();
    });

    // Submit Form via AJAX
    $('#create-course-form').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = new FormData(this);
        let btn = $('#create-course-submit');
        let originalText = btn.html();

        // Clear previous errors
        form.find('.invalid-feedback').remove();
        form.find('.is-invalid').removeClass('is-invalid');

        btn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm"></span> Saving...');

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
                        window.location.href = "{{ route('courses.live.index') }}";
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

    // --- SKILLS ---
    let skillIndex = 1;
    $('#add-skill').on('click', function() {
        $('#skills-wrapper').append(`
            <div class="d-flex gap-2 mb-2 skill-item">
                <input type="text" name="skills[]" class="form-control" required  placeholder="Skill name (e.g. Python Programming)">
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

