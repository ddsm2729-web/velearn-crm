$(document).ready(function () {

    $('body').on('change', '#webinar_date', function () {
        const input = $(this).val(); // e.g., "07-06-2024"

        // Split the input assuming it's in DD-MM-YYYY format
        const [day, month, year] = input.split('-');
        const dob = new Date(year, month - 1, day); // month is 0-indexed

        const today = new Date();

        let years = today.getFullYear() - dob.getFullYear();
        let months = today.getMonth() - dob.getMonth();
        let days = today.getDate() - dob.getDate();

        // Adjust days
        if (days < 0) {
            months--;
            const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
            days += prevMonth.getDate();
        }

        // Adjust months
        if (months < 0) {
            years--;
            months += 12;
        }

        let result = '';
        if (years > 0) {
            result = `${years} year${years > 1 ? 's' : ''}`;
        } else if (months > 0) {
            result = `${months} month${months > 1 ? 's' : ''}`;
        } else if (days > 0) {
            result = `${days} day${days > 1 ? 's' : ''}`;
        } else {
            result = `0 days`;
        }

        $('body').find('#age').val(result);
    });

    // Add webinar 
    $('body').on('click', '#create-webinar-submit', function () {

        let form = $('#create-webinar-form')[0];
        let formData = new FormData(form);

        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        $.ajax({
            url: $('#create-webinar-form').attr('action'),
            method: 'POST',
            data: formData,
            processData: false, // 🔥 REQUIRED
            contentType: false, // 🔥 REQUIRED
            beforeSend: function () {
                $('#loader-spinner').removeClass('d-none');
            },
            success: function (res) {
                toastr.success(res.message);
                setTimeout(() => {
                    window.location.href = baseUrl + '/webinars';
                }, 1500);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let hasVisibleError = false;
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        // Convert dot notation (sections.0.title) to bracket notation (sections[0][title])
                        let inputName = key;
                        if (key.includes('.')) {
                            let parts = key.split('.');
                            inputName = parts[0] + '[' + parts.slice(1).join('][') + ']';
                        }
                        
                        let input = $('[name="' + inputName + '"]');
                        if (input.length > 0) {
                            input.addClass('is-invalid');
                            hasVisibleError = true;
                        }
                        
                        let errSpan = $('#' + key.replace(/\./g, '_') + '_err');
                        if (errSpan.length > 0) {
                            errSpan.text(value[0]);
                            hasVisibleError = true;
                        } else if ($('#' + key + '_err').length > 0) {
                            $('#' + key + '_err').text(value[0]);
                            hasVisibleError = true;
                        } else if (key === 'sections') {
                            $('#sections_err').text(value[0]);
                            hasVisibleError = true;
                        }
                    });
                    if (!hasVisibleError) {
                        toastr.error('Please fill in all required fields including Webinar Sections.');
                    }
                } else {
                    toastr.error('Something went wrong');
                }
            },
            complete: function () {
                $('#loader-spinner').addClass('d-none');
            }
        });
    });

    // Change Status of webinar
    $('body').on('click', '.status-webinar', function () {
        var clickChangeStatus = $(this).attr('data-change-status');
        var authId = $(this).attr('data-authid');
        var webinarId = $(this).attr('data-id');
        var urlHref = $(this).attr('data-href');

        var staticStatus = 'Disable';
        var changeStatus = 'Enable';
        if (parseInt(clickChangeStatus) == 0) { staticStatus = 'Enable'; changeStatus = 'Disable'; }

        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            html: "You want to change status from " + staticStatus + " to " + changeStatus + " ?",
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: "No"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: urlHref,
                    type: 'POST',
                    data: { authId: authId, webinarId: webinarId, status: clickChangeStatus },
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.message);
                            setTimeout(function () { window.location.href = baseUrl + '/webinars' }, 1000);
                        }
                        else { toastr.error(res.message); }
                    }
                });
            }
        });
    });

    // Delete webinar
    $('body').on('click', '.delete-webinar', function () {
        var authId = $(this).attr('data-authid');
        var urlHref = $(this).attr('data-href');

        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            html: "You want to Delete this Medical webinar ?",
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: "No"
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: urlHref,
                    type: 'DELETE',
                    data: { authId: authId },
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.message);
                            setTimeout(function () { window.location.href = baseUrl + '/webinars' }, 1500);
                        }
                        else { toastr.error(res.message); }
                    }
                });
            }
        });
    });

    // Update webinar
    $('body').on('click', '#edit-webinar-submit', function () {
        let form = document.getElementById('edit-webinar-form');
        let formData = new FormData(form);
        let $loader = $('#loader-spinner');

        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        $.ajax({
            url: form.action,
            method: 'POST', // PUT via _method
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(() => {
                        window.location.href = baseUrl + '/webinars';
                    }, 1500);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let hasVisibleError = false;
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        // Convert dot notation (sections.0.title) to bracket notation (sections[0][title])
                        let inputName = key;
                        if (key.includes('.')) {
                            let parts = key.split('.');
                            inputName = parts[0] + '[' + parts.slice(1).join('][') + ']';
                        }
                        
                        let input = $('[name="' + inputName + '"]');
                        if (input.length > 0) {
                            input.addClass('is-invalid');
                            hasVisibleError = true;
                        }
                        
                        let errSpan = $('#' + key.replace(/\./g, '_') + '_err');
                        if (errSpan.length > 0) {
                            errSpan.text(value[0]);
                            hasVisibleError = true;
                        } else if ($('#' + key + '_err').length > 0) {
                            $('#' + key + '_err').text(value[0]);
                            hasVisibleError = true;
                        } else if (key === 'sections') {
                            $('#sections_err').text(value[0]);
                            hasVisibleError = true;
                        }
                    });
                    if (!hasVisibleError) {
                        toastr.error('Please fill in all required fields including Webinar Sections.');
                    }
                } else {
                    toastr.error('Something went wrong');
                }
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });
});