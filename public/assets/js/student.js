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

    // Add staff / Vision Center
    $('body').on('click', '#create-student-submit', function () {
        let $loader = $(this).find('#loader-spinner');

        // Clear all previous errors
        $('#create-student-form .invalid-feedback').text('');
        $('#create-student-form .form-control').removeClass('is-invalid');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('#create-student-form').attr('action'),
            type: 'POST',
            data: $('#create-student-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    // Reload the page to show the "Edit" state
                    setTimeout(function () { window.location.reload(); }, 1500);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function (err) {
                if (err.responseJSON && err.responseJSON.errors) {
                    $.each(err.responseJSON.errors, function (key, value) {
                        let $input = $('#create-student-form [name="' + key + '"]');
                        $input.addClass('is-invalid'); // optional: Bootstrap style
                        $input.closest('.form-group').find('.invalid-feedback').text(value[0]);
                    });
                } else {
                    toastr.error('Something went wrong. Please try again.');
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
                            setTimeout(function () { window.location.href = baseUrl + '/leads/confirmed' }, 1000);
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
            html: "You want to Delete this Medical Staff ?",
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
                            setTimeout(function () { window.location.href = baseUrl + '/leads/confirmed' }, 1500);
                        }
                        else { toastr.error(res.message); }
                    }
                });
            }
        });
    });

    // Update staff
    $('body').on('click', '#update-staffs-submit', function () {
        $loader = $(this).find('#loader-spinner');
        $('body').find('.invalid-feedback').text("");

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
                    setTimeout(function () { window.location.href = baseUrl + '/leads/confirmed' }, 1500);
                }
                else { toastr.error(res.message); }
            },
            error: function (err) {
                $.each(err.responseJSON.errors, function (key, value) {
                    $('body').find('#' + key + '_err').css('display', 'block').text(value[0]);
                });
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });

    // Update Student from Confirmation
    $('body').on('click', '#update-student-submit', function () {
        let $loader = $(this).find('#loader-spinner');

        // Clear all previous errors
        $('#update-student-form .invalid-feedback').text('');
        $('#update-student-form .form-control').removeClass('is-invalid');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('#update-student-form').attr('action'),
            type: 'POST',
            data: $('#update-student-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () { window.location.href = baseUrl + '/leads/confirmed'; }, 1500);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function (err) {
                if (err.responseJSON && err.responseJSON.errors) {
                    $.each(err.responseJSON.errors, function (key, value) {
                        let $input = $('#update-student-form [name="' + key + '"]');
                        $input.addClass('is-invalid');
                        $input.closest('.form-group').find('.invalid-feedback').text(value[0]);
                    });
                } else {
                    toastr.error('Something went wrong. Please try again.');
                }
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });
});