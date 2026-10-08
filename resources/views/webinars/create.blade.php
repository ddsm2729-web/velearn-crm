@extends('layouts.header')

@section('pageTitle')
    {{ __('Add Webinar') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3">Add <strong> Webinar </strong></h1>

            @can('Webinar List')
                <a href="{{ route('webinars.index') }}" class="btn btn-theme"> Back</a>
            @endcan
        </div>

        <div class="row">
            @can('Webinar Create')
                <div class="col-12 col-xl-12 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <form method="POST" action="{{ route('webinars.store') }}" enctype="multipart/form-data"
                                onsubmit="return false;" id="create-webinar-form">
                                @csrf

                                <!-- Title, Category & Course Level -->
                                <div class="row mb-3">
                                    <div class="col-xl-4">
                                        <label class="form-label">Webinar Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control"
                                            placeholder="Webinar Title">
                                        <span class="invalid-feedback" id="title_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select name="category" class="select2 form-control">
                                            <option value="" disabled selected>Select Category</option>
                                            <option value="Full Stack Development">Full Stack Development</option>
                                            <option value="Data Science">Data Science</option>
                                            <option value="UI/UX Design">UI/UX Design</option>
                                            <option value="Digital Marketing">Digital Marketing</option>
                                            <option value="AI/ML">AI/ML</option>
                                        </select>
                                        <span class="invalid-feedback" id="category_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">Course Level <span class="text-danger">*</span></label>
                                        <select name="course_level" class="select2 form-control">
                                            <option value="" disabled selected>Select Level</option>
                                            <option value="Beginner">Beginner</option>
                                            <option value="Intermediate">Intermediate</option>
                                            <option value="Advanced">Advanced</option>
                                        </select>
                                        <span class="invalid-feedback" id="course_level_err"></span>
                                    </div>
                                </div>

                                <!-- Date & Time -->
                                <div class="row mb-3">
                                    <div class="col-xl-4">
                                        <label class="form-label">Date <span class="text-danger">*</span></label>
                                        <input type="text" name="date" id="webinar_date" class="form-control"
                                            readonly>
                                        <span class="invalid-feedback" id="date_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">From Time <span class="text-danger">*</span></label>
                                        <input type="time" name="from_time" class="form-control">
                                        <span class="invalid-feedback" id="from_time_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">To Time <span class="text-danger">*</span></label>
                                        <input type="time" name="to_time" class="form-control">
                                        <span class="invalid-feedback" id="to_time_err"></span>
                                    </div>
                                </div>

                                <!-- Seats & Image -->
                                <div class="row mb-3">
                                    <div class="col-xl-6">
                                        <label class="form-label">Seats <span class="text-danger">*</span></label>
                                        <input type="number" name="seats" class="form-control" min="1">
                                        <span class="invalid-feedback" id="seats_err"></span>
                                    </div>

                                    <div class="col-xl-6">
                                        <label class="form-label">Webinar Image <span class="text-danger">*</span></label>
                                        <input type="file" name="image" class="form-control">
                                        <span class="invalid-feedback" id="image_err"></span>
                                    </div>
                                </div>



                                <!-- Description -->
                                <div class="mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea id="description" name="description" rows="4" class="form-control"></textarea>
                                    <span class="invalid-feedback" id="description_err"></span>
                                </div>

                                <!-- Professor -->
                                <div class="row mb-3">
                                    <div class="col-xl-4">
                                        <label class="form-label">Professor Name <span class="text-danger">*</span></label>
                                        <input type="text" name="professor" class="form-control">
                                        <span class="invalid-feedback" id="professor_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">Position <span class="text-danger">*</span></label>
                                        <input type="text" name="professor_position" class="form-control">
                                        <span class="invalid-feedback" id="professor_position_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">Experience <span class="text-danger">*</span></label>
                                        <input type="text" name="professor_experience" class="form-control">
                                        <span class="invalid-feedback" id="professor_experience_err"></span>
                                    </div>
                                </div>

                                <!-- Requirements -->
                                <!-- Webinar Sections -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        Webinar Sections <span class="text-danger">*</span>
                                    </label>
                                    <span class="text-danger d-block" id="sections_err" style="font-size: 80%;"></span>

                                    <div id="sections-wrapper">

                                        <!-- Section -->
                                        <div class="card mb-3 section-block">
                                            <div class="card-body">

                                                <!-- Section Title -->
                                                <div class="mb-2">
                                                    <label class="form-label">Section Title</label>
                                                    <input type="text" name="sections[0][title]" class="form-control"
                                                        placeholder="Enter section title">
                                                </div>

                                                <!-- Descriptions -->
                                                <div class="descriptions-wrapper">

                                                    <div class="mb-2 description-item">
                                                        <label class="form-label">Description</label>
                                                        <textarea name="sections[0][descriptions][0]" class="form-control" rows="3"></textarea>
                                                    </div>

                                                </div>

                                                <button type="button"
                                                    class="btn btn-sm btn-outline-secondary add-description">
                                                    + Add Description
                                                </button>

                                            </div>
                                        </div>

                                    </div>

                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-section">
                                        + Add Another Title
                                    </button>
                                </div>

                                <button type="submit" class="btn btn-theme float-end" id="create-webinar-submit">
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
    <link rel="stylesheet" href="{{ asset('assets/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/webinar.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Initialize Table into DataTable
            $('body').find('.select2').select2();

            $('#webinar_date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY',
                    cancelLabel: 'Clear'
                },
                startDate: moment().add(0, 'days'),
                minDate: moment().add(0, 'days'),
                showDropdowns: true,
            });
        });
    </script>
    <script>
        let sectionIndex = 1;

        $('#add-section').on('click', function() {
            let html = `
    <div class="card mb-3 section-block">
        <div class="card-body">

            <div class="mb-2">
                <label class="form-label">Section Title</label>
                <input type="text"
                       name="sections[${sectionIndex}][title]"
                       class="form-control">
            </div>

            <div class="descriptions-wrapper">
                <div class="mb-2 description-item">
                    <label class="form-label">Description</label>
                    <textarea
                        name="sections[${sectionIndex}][descriptions][0]"
                        class="form-control"
                        rows="3"></textarea>
                </div>
            </div>

            <button type="button"
                    class="btn btn-sm btn-outline-secondary add-description">
                + Add Description
            </button>

        </div>
    </div>
    `;
            $('#sections-wrapper').append(html);
            sectionIndex++;
        });

        // Add description under same title
        $('body').on('click', '.add-description', function() {
            let wrapper = $(this).siblings('.descriptions-wrapper');
            let sectionIdx = $(this)
                .closest('.section-block')
                .find('input[name^="sections"]')
                .attr('name')
                .match(/\d+/)[0];

            let descCount = wrapper.find('.description-item').length;

            let html = `
        <div class="mb-2 description-item">
            <textarea
                name="sections[${sectionIdx}][descriptions][${descCount}]"
                class="form-control"
                rows="3"></textarea>
        </div>
    `;

            wrapper.append(html);
        });
    </script>
@endpush
