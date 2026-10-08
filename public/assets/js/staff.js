$(document).ready(function () {

    $('body').on('change', '#birth_date', function () {
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

    // Add staff
    $('body').on('click', '#create-staff-submit', function () {
        let $loader = $(this).find('#loader-spinner');

        // Clear all previous errors
        $('#create-staff-form .invalid-feedback').text('').hide();
        $('#create-staff-form .form-control').removeClass('is-invalid');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('#create-staff-form').attr('action'),
            type: 'POST',
            data: $('#create-staff-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () { window.location.href = baseUrl + '/staffs'; }, 1500);
                } else {
                    toastr.error(res.message || 'Something went wrong.');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.errors || {};
                    $.each(errors, function (key, value) {
                        let $input = $('#create-staff-form [name="' + key + '"]');
                        $input.addClass('is-invalid');
                        $('#' + key + '_err').css('display', 'block').text(value[0]);
                        toastr.error(value[0]);
                    });
                } else if (xhr.status === 419) {
                    toastr.error('Session expired. Please refresh the page.');
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });

    // Change Status of staff
    $('body').on('click', '.status-staff', function () {
        var clickChangeStatus = $(this).attr('data-change-status');
        var authId = $(this).attr('data-authid');
        var staffsId = $(this).attr('data-id');
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
                    data: { authId: authId, staffsId: staffsId, status: clickChangeStatus },
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.message);
                            setTimeout(function () { window.location.href = baseUrl + '/staffs' }, 1000);
                        }
                        else { toastr.error(res.message); }
                    }
                });
            }
        });
    });

    // Delete staff
    $('body').on('click', '.delete-staff', function () {
        var authId = $(this).attr('data-authid');
        var urlHref = $(this).attr('data-href');

        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            html: "You want to Delete this Staff ?",
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
                            setTimeout(function () { window.location.href = baseUrl + '/staffs' }, 1500);
                        }
                        else { toastr.error(res.message); }
                    }
                });
            }
        });
    });

    // Update staff
    $('body').on('click', '#update-staffs-submit', function () {
        let $loader = $(this).find('#loader-spinner');

        // Clear all previous errors
        $('#update-staffs-form .invalid-feedback').text('').hide();
        $('#update-staffs-form .form-control').removeClass('is-invalid');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('body').find('#update-staffs-form').attr('action'),
            type: 'POST',
            data: $('body').find('#update-staffs-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () { window.location.href = baseUrl + '/staffs' }, 1500);
                } else {
                    toastr.error(res.message || 'Something went wrong.');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON?.errors || {};
                    $.each(errors, function (key, value) {
                        let $input = $('#update-staffs-form [name="' + key + '"]');
                        $input.addClass('is-invalid');
                        $('#' + key + '_err').css('display', 'block').text(value[0]);
                        toastr.error(value[0]);
                    });
                } else if (xhr.status === 419) {
                    toastr.error('Session expired. Please refresh the page.');
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });
});