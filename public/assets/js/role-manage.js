$(document).ready(function () {

    // Show Create-Role-Form
    $('body').on('click', '#create-role-btn', function () {
        $.get(baseUrl + '/roles/create', function (response) {
            $('body').find('#role-form-section').html(response).slideDown('slow');
        })
        .fail(function (xhr) {
            console.error("Failed to load content:", xhr.status, xhr.responseText);
        });
    });

    // Create Role
    $('body').on('click', '#create-role-submit', function () {
        $loader = $(this).find('#loader-spinner');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('body').find('#create-role-form').attr('action'),
            type: 'POST',
            data: $('body').find('#create-role-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if(res.status) {
                    $('body').find('#role-form-section').html("").slideUp('slow');
                    toastr.success(res.message);
                    setTimeout(function () { window.location.reload() },900);
                }
                else { toastr.error(res.message); }
            },
            error: function (err) {
                $loader.addClass('d-none');
                let msg = err.responseJSON?.errors?.role_name?.[0]
                    || err.responseJSON?.message
                    || 'Something went wrong. Please try again.';
                $('body').find('#role_name_err').css('display','block').text(msg);
                toastr.error(msg);
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        })
    });

    // Delete Roles
    $('body').on('click', '.delete-role', function () {
        Swal.fire({
            icon: "warning",
            title: "Are you sure,",
            html: "You want to delete this Role ?",
            showDenyButton: true,
            confirmButtonText: "Yes",
            denyButtonText: "No"
        }).then((result) => {
            /* If isConfirmed */
            if (result.isConfirmed) {
                var contentRef = $(this);
                var urlHref = contentRef.attr('data-href');
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
                        if(res.status) {
                            toastr.success(res.message);
                            setTimeout(function () { window.location.reload() },900);
                        }
                        else { toastr.error(res.message); }
                    },
                    complete: function () {
                        contentRef.find('span').removeClass('spinner-border').removeClass('spinner-border-sm');
                    }
                });
            }
        }); 
    });

    // Edit Roles
    $('body').on('click', '.edit-role', function () {
        var contentRef = $(this);
        var urlHref = contentRef.attr('data-href');

        $.get(urlHref, function (response) {
            $('body').find('#role-form-section').html(response).slideDown('slow');
        })
        .fail(function (xhr) {
            console.error("Failed to load content:", xhr.status, xhr.responseText);
        });
    });

    // Update Roles
    $('body').on('click', '#update-role-submit', function () {
        $loader = $(this).find('#loader-spinner');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('body').find('#update-role-form').attr('action'),
            type: 'POST',
            data: $('body').find('#update-role-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if(res.status) {
                    $('body').find('#role-form-section').html("").slideUp('slow');
                    toastr.success(res.message);
                    setTimeout(function () { window.location.reload() },900);
                }
                else { toastr.error(res.message); }
            },
            error: function (err) {
                $loader.addClass('d-none');
                let msg = err.responseJSON?.errors?.role_name?.[0]
                    || err.responseJSON?.message
                    || 'Something went wrong. Please try again.';
                $('body').find('#role_name_err').css('display','block').text(msg);
                toastr.error(msg);
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        })
    });

    // Manage Role Permissions
    $('body').on('click', '.manage-role-permission', function () {
        var manageRolePermissionUrl = $(this).attr('data-href');
        window.top.location.href = manageRolePermissionUrl;
    });

    // Assign Permission to specific role
    $('body').on('click', '#manage-permission-submit', function () {
        $loader = $(this).find('#loader-spinner');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('body').find('#manage-permission-form').attr('action'),
            type: 'POST',
            data: $('body').find('#manage-permission-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if(res.status) {
                    toastr.success(res.message);
                    setTimeout(function () { window.top.location.href = baseUrl + '/roles' },900);
                }
                else { toastr.error(res.message); }
            },
            error: function (err) {
                $('html, body').animate({ scrollTop: 0 });
                $('body').find('#permission_assign_err').css('display','block').text(err.responseJSON.errors.permission_assign[0]);
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        })
    });

    // Cancel Edit Specialization
    $('body').on('click', '#cancel-role-open', function () {
        $('body').find('#role-form-section').html('').slideUp('slow');
    });
});