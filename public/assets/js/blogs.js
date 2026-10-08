$(document).ready(function () {
    // Add webinar 
    $('body').on('click', '#create-blog-submit', function () {

        let form = $('#create-blog-form')[0];
        let formData = new FormData(form);

        $('.invalid-feedback').text('');
        $('.form-control').removeClass('is-invalid');

        $.ajax({
            url: $('#create-blog-form').attr('action'),
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
                    window.location.href = baseUrl + '/blogs-info';
                }, 1500);
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        $('#' + key + '_err').text(value[0]);
                    });
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
    $('body').on('click', '.status-blog', function () {

        let el = $(this);
        let url = el.data('href');
        let img = el.find('img');

        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            text: "You want to change blog status?",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "No"
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: 'POST',
                    success: function (res) {

                        if (res.status) {
                            toastr.success(res.message);

                            // 🔥 CHANGE IMAGE WITHOUT RELOAD
                            if (res.new_status === 'published') {
                                img.attr('src', '/assets/images/icon-toggle-on.png');
                            } else {
                                img.attr('src', '/assets/images/icon-toggle-off.png');
                            }

                        } else {
                            toastr.error(res.message);
                        }
                    },
                    error: function () {
                        toastr.error('Something went wrong');
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
                            setTimeout(function () { window.location.href = baseUrl + '/blogs-info' }, 1500);
                        }
                        else { toastr.error(res.message); }
                    }
                });
            }
        });
    });

    // Update webinar
    $('body').on('click', '#edit-blog-submit', function () {
        let form = document.getElementById('edit-blog-form');
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
                        window.location.href = baseUrl + '/blogs-info';
                    }, 1500);
                } else {
                    toastr.error(res.message);
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (key, value) {
                        let input = $('[name="' + key + '"]');
                        input.addClass('is-invalid');
                        $('#' + key + '_err').text(value[0]);
                    });
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