@extends('layouts.header')

@section('pageTitle')
    {{ __('Edit Webinar') }} | {{ config('app.name') }}
@endsection

@section('section')
    <div class="container-fluid p-0">
        <div class="align-items-center d-flex justify-content-between">
            <h1 class="h3">Edit <strong> Webinar </strong></h1>

            @can('Webinar List')
                <a href="javascript:history.back()" class="btn btn-theme"><i class="fas fa-arrow-left me-2"></i> Back</a>
            @endcan
        </div>

        <div class="row">
            @can('Webinar Edit')
                <div class="col-12 col-xl-12 mt-1">
                    <div class="card">
                        <div class="card-body">

                            <form method="POST" action="{{ route('webinars.update', encrypt($webinar->id)) }}"
                                onsubmit="return false;" enctype="multipart/form-data" id="edit-webinar-form">

                                @csrf
                                @method('PUT')

                                {{-- Title, Category & Course Level --}}
                                <div class="mb-3 row">
                                    <div class="col-xl-4">
                                        <label class="form-label">Title <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control"
                                            value="{{ $webinar->title }}" placeholder="Webinar Title">
                                        <span class="invalid-feedback" id="title_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">Category <span class="text-danger">*</span></label>
                                        <select name="category" class="select2 form-control">
                                            <option value="" disabled>Select Category</option>
                                            <option value="Full Stack Development" {{ $webinar->category == 'Full Stack Development' ? 'selected' : '' }}>Full Stack Development</option>
                                            <option value="Data Science" {{ $webinar->category == 'Data Science' ? 'selected' : '' }}>Data Science</option>
                                            <option value="UI/UX Design" {{ $webinar->category == 'UI/UX Design' ? 'selected' : '' }}>UI/UX Design</option>
                                            <option value="Digital Marketing" {{ $webinar->category == 'Digital Marketing' ? 'selected' : '' }}>Digital Marketing</option>
                                            <option value="AI/ML" {{ $webinar->category == 'AI/ML' ? 'selected' : '' }}>AI/ML</option>
                                        </select>
                                        <span class="invalid-feedback" id="category_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">Course Level <span class="text-danger">*</span></label>
                                        <select name="course_level" class="select2 form-control">
                                            <option value="" disabled>Select Level</option>
                                            <option value="Beginner" {{ $webinar->course_level == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                            <option value="Intermediate" {{ $webinar->course_level == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                            <option value="Advanced" {{ $webinar->course_level == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                                        </select>
                                        <span class="invalid-feedback" id="course_level_err"></span>
                                    </div>
                                </div>

                                {{-- Date & Time --}}
                                <div class="mb-3 row">
                                    <div class="col-xl-6">
                                        <label class="form-label">Date <span class="text-danger">*</span></label>
                                        <input type="text" id="webinar_date" name="date" class="form-control"
                                            readonly>
                                        <span class="invalid-feedback" id="date_err"></span>
                                    </div>

                                    <div class="col-xl-6">
                                        <label class="form-label">From Time <span class="text-danger">*</span></label>
                                        <input type="time" name="from_time" class="form-control"
                                            value="{{ $webinar->from_time }}">
                                        <span class="invalid-feedback" id="from_time_err"></span>
                                    </div>
                                </div>

                                {{-- Seats --}}
                                <div class="mb-3 row">
                                    <div class="col-xl-6">
                                        <label class="form-label">To Time <span class="text-danger">*</span></label>
                                        <input type="time" name="to_time" class="form-control"
                                            value="{{ $webinar->to_time }}">
                                        <span class="invalid-feedback" id="to_time_err"></span>
                                    </div>

                                    <div class="col-xl-6">
                                        <label class="form-label">Seats <span class="text-danger">*</span></label>
                                        <input type="number" name="seats" class="form-control"
                                            value="{{ $webinar->seats }}" min="1">
                                        <span class="invalid-feedback" id="seats_err"></span>
                                    </div>
                                </div>

                                {{-- Image --}}
                                <div class="mb-3 row">
                                    <div class="col-xl-6">
                                        <label class="form-label">Webinar Image</label>
                                        <input type="file" name="image" class="form-control">
                                        <span class="invalid-feedback" id="image_err"></span>
                                    </div>

                                    <div class="col-xl-6">
                                        @if ($webinar->image)
                                            <img src="{{ asset('uploads/webinars/' . $webinar->image) }}"
                                                class="img-thumbnail mt-2" width="150">
                                        @endif
                                    </div>
                                </div>



                                {{-- Description --}}
                                <div class="mb-3">
                                    <label class="form-label">Description <span class="text-danger">*</span></label>
                                    <textarea id="description" name="description" rows="4" class="form-control">{{ $webinar->description }}</textarea>
                                    <span class="invalid-feedback" id="description_err"></span>
                                </div>

                                {{-- Professor --}}
                                <div class="mb-3 row">
                                    <div class="col-xl-4">
                                        <label class="form-label">Professor <span class="text-danger">*</span></label>
                                        <input type="text" name="professor" class="form-control"
                                            value="{{ $webinar->professor }}">
                                        <span class="invalid-feedback" id="professor_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">Position <span class="text-danger">*</span></label>
                                        <input type="text" name="professor_position" class="form-control"
                                            value="{{ $webinar->professor_position }}">
                                        <span class="invalid-feedback" id="professor_position_err"></span>
                                    </div>

                                    <div class="col-xl-4">
                                        <label class="form-label">Experience <span class="text-danger">*</span></label>
                                        <input type="text" name="professor_experience" class="form-control"
                                            value="{{ $webinar->professor_experience }}">
                                        <span class="invalid-feedback" id="professor_experience_err"></span>
                                    </div>
                                </div>

                                {{-- Webinar Sections (Edit) --}}
                                <div class="mb-3">
                                    <label class="form-label">
                                        Webinar Sections <span class="text-danger">*</span>
                                    </label>
                                    <span class="text-danger d-block" id="sections_err" style="font-size: 80%;"></span>

                                    <div id="sections-wrapper">

                                        @foreach ($webinar->titles as $tIndex => $title)
                                            <div class="card mb-3 section-block">
                                                <div class="card-body">

                                                    {{-- Hidden Title ID --}}
                                                    <input type="hidden" name="sections[{{ $tIndex }}][id]"
                                                        value="{{ $title->id }}">

                                                    {{-- Section Title --}}
                                                    <div class="mb-2">
                                                        <label class="form-label">Section Title</label>
                                                        <div class="d-flex gap-2">
                                                            <input type="text" name="sections[{{ $tIndex }}][title]"
                                                                class="form-control" value="{{ $title->title }}">

                                                            <button type="button"
                                                                class="btn btn-sm btn-danger mt-2 remove-section">
                                                                X
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {{-- Descriptions --}}
                                                    <div class="descriptions-wrapper">
                                                        @foreach ($title->descriptions as $dIndex => $desc)
                                                            <div class="mb-2 description-item">
                                                                <input type="hidden"
                                                                    name="sections[{{ $tIndex }}][descriptions][{{ $dIndex }}][id]"
                                                                    value="{{ $desc->id }}">

                                                                <textarea name="sections[{{ $tIndex }}][descriptions][{{ $dIndex }}][description]" class="form-control"
                                                                    rows="3">{{ $desc->description }}</textarea>

                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger mt-1 remove-description">
                                                                    Remove Description
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary add-description">
                                                        + Add Description
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>

                                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-section">
                                        + Add Another Title
                                    </button>
                                </div>

                                {{-- Submit --}}
                                <button type="submit" class="btn btn-theme float-end" id="edit-webinar-submit">
                                    <span class="spinner-border spinner-border-sm d-none" id="loader-spinner"></span>
                                    Update
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

            let webinarDate = @json($webinar->date);

            $('#webinar_date').daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD'
                },
                startDate: moment(webinarDate),
                minDate: moment().add(0, 'days'),
                showDropdowns: true,
            });

            $('body').find('.select2').select2();
        });
    </script>
    <script>
        let sectionIndex = {{ $webinar->titles->count() }};

        // ADD TITLE
        $('#add-section').on('click', function() {
            $('#sections-wrapper').append(`
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
                        <textarea
                            name="sections[${sectionIndex}][descriptions][0][description]"
                            class="form-control"
                            rows="3"></textarea>

                        <button type="button"
                                class="btn btn-sm btn-danger mt-1 remove-description">
                            Remove Description
                        </button>
                    </div>
                </div>

                <button type="button"
                        class="btn btn-sm btn-outline-secondary add-description">
                    + Add Description
                </button>

                <button type="button"
                        class="btn btn-sm btn-danger mt-2 remove-section">
                    Remove Title
                </button>

            </div>
        </div>
    `);

            sectionIndex++;
        });

        // ADD DESCRIPTION
        $('body').on('click', '.add-description', function() {

            let wrapper = $(this).siblings('.descriptions-wrapper');
            let sectionIdx = $(this)
                .closest('.section-block')
                .find('input[name^="sections"]')
                .attr('name')
                .match(/\d+/)[0];

            let count = wrapper.find('.description-item').length;

            wrapper.append(`
        <div class="mb-2 description-item">
            <textarea
                name="sections[${sectionIdx}][descriptions][${count}][description]"
                class="form-control"
                rows="3"></textarea>

            <button type="button"
                    class="btn btn-sm btn-danger mt-1 remove-description">
                Remove Description
            </button>
        </div>
    `);
        });

        // REMOVE DESCRIPTION
        $('body').on('click', '.remove-description', function() {
            $(this).closest('.description-item').remove();
        });

        // REMOVE TITLE (WHOLE SECTION)
        $('body').on('click', '.remove-section', function() {
            $(this).closest('.section-block').remove();
        });
    </script>
@endpush
