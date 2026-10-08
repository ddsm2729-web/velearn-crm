$(document).ready(function () {
    // Add company
    $('body').on('click', '#create-company-submit', function () {
        let $loader = $(this).find('#loader-spinner');

        // Clear all previous errors
        $('#create-company-form .invalid-feedback').text('');
        $('#create-company-form .form-control').removeClass('is-invalid');
        $('#create-company-form .form-select').removeClass('is-invalid');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('#create-company-form').attr('action'),
            type: 'POST',
            data: $('#create-company-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () { window.location.href = baseUrl + '/company-database'; }, 1500);
                } else {
                    toastr.error(res.message || 'Validation Failed');
                    if (res.errors) {
                        $.each(res.errors, function (key, value) {
                            let $input = $('#create-company-form [name="' + key + '"]');
                            $input.addClass('is-invalid');
                            $('#' + key + '_err').text(value[0]);
                        });
                    }
                }
            },
            error: function (err) {
                toastr.error('Something went wrong. Please try again.');
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });

    // Update company
    $('body').on('click', '#update-company-submit', function () {
        let $loader = $(this).find('#loader-spinner');

        // Clear all previous errors
        $('#update-company-form .invalid-feedback').text('');
        $('#update-company-form .form-control').removeClass('is-invalid');
        $('#update-company-form .form-select').removeClass('is-invalid');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('#update-company-form').attr('action'),
            type: 'PUT',
            data: $('#update-company-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(function () { window.location.href = baseUrl + '/company-database'; }, 1500);
                } else {
                    toastr.error(res.message || 'Validation Failed');
                    if (res.errors) {
                        $.each(res.errors, function (key, value) {
                            let $input = $('#update-company-form [name="' + key + '"]');
                            $input.addClass('is-invalid');
                            $('#' + key + '_err').text(value[0]);
                        });
                    }
                }
            },
            error: function (err) {
                toastr.error('Something went wrong. Please try again.');
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });

    // Delete company
    $('body').on('click', '.delete-company', function () {
        var urlHref = $(this).attr('data-href');

        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            html: "You want to Delete this Company ?",
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
                    type: 'DELETE',
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.message);
                            setTimeout(function () { window.location.reload(); }, 1000);
                        }
                        else { toastr.error(res.message); }
                    }
                });
            }
        });
    });
});
