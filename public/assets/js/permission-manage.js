$(document).ready(function () {

    // Show Create-Permission-Form
    $('body').on('click', '#create-permission-btn', function () {
        $.get(baseUrl + '/permissions/create', function (response) {
            $('body').find('#permission-form-section').html(response).slideDown('slow');
        })
            .fail(function (xhr) {
                console.error("Failed to load content:", xhr.status, xhr.responseText);
            });
    });

    // Create Role
    $('body').on('click', '#create-permission-submit', function () {
        let $form = $('#create-permission-form');
        let $loader = $('#loader-spinner');

        // Clear previous errors
        $('#permission_name_err').text('').hide();
        $('#group_name_err').text('').hide();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if (res.status) {
                    toastr.success(res.message);
                    setTimeout(() => window.location.reload(), 900);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function (err) {
                let errors = err.responseJSON?.errors;
                if (errors) {
                    if (errors.permission_name) {
                        $('#permission_name_err').show().text(errors.permission_name[0]);
                    }
                    if (errors.group_name) {
                        $('#group_name_err').show().text(errors.group_name[0]);
                    }
                }
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        });
    });

    // Delete Permission
    $('body').on('click', '.delete-permission', function () {
        var contentRef = $(this);
        var urlHref = contentRef.attr('data-href');

        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            html: "You want to delete this Permission?",
            showDenyButton: true,
            confirmButtonText: "Yes, Delete",
            denyButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: urlHref,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function () {
                        contentRef.find('span').addClass('spinner-border').addClass('spinner-border-sm');
                    },
                    success: function (res) {
                        if (res.status) {
                            toastr.success(res.message);
                            setTimeout(function () { window.location.reload() }, 900);
                        } else {
                            toastr.error(res.message);
                        }
                    },
                    complete: function () {
                        contentRef.find('span').removeClass('spinner-border').removeClass('spinner-border-sm');
                    }
                });
            }
        });
    });

    // Edit Roles
    $('body').on('click', '.edit-permission', function () {
        var contentRef = $(this);
        var urlHref = contentRef.attr('data-href');

        $.get(urlHref, function (response) {
            $('body').find('#permission-form-section').html(response).slideDown('slow');
        })
            .fail(function (xhr) {
                console.error("Failed to load content:", xhr.status, xhr.responseText);
            });
    });

    // Update Roles
    $('body').on('click', '#update-permission-submit', function () {
        $loader = $(this).find('#loader-spinner');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('body').find('#update-permission-form').attr('action'),
            type: 'POST',
            data: $('body').find('#update-permission-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if (res.status) {
                    $('body').find('#permission-form-section').html("").slideUp('slow');
                    toastr.success(res.message);
                    setTimeout(function () { window.location.reload() }, 900);
                }
                else { toastr.error(res.message); }
            },
            error: function (err) {
                $loader.addClass('d-none');
                let msg = err.responseJSON?.errors?.permission_name?.[0]
                    || err.responseJSON?.message
                    || 'Something went wrong. Please try again.';
                $('body').find('#permission_name_err').css('display', 'block').text(msg);
                toastr.error(msg);
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        })
    });

    /*$('body').on('click', '.permission-checkbox', function () {
        var permissionName = $(this).attr('data-permission');
        var $accordionToggle = $('#' + permissionName);

        if ($(this).is(':checked')) {
            $accordionToggle.prop('checked', true); // Open accordion
        } else {
            $accordionToggle.prop('checked', false); // Close accordion
        }
    });*/
});