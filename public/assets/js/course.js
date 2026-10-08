$(document).ready(function () {
    // Change Status of webinar
    $('body').on('click', '.status-course', function () {

        let clickChangeStatus = $(this).attr('data-change-status');
        let courseId = $(this).attr('data-id');
        let urlHref = $(this).attr('data-href');

        let staticStatus = 'Disable';
        let changeStatus = 'Enable';

        if (parseInt(clickChangeStatus) === 0) {
            staticStatus = 'Enable';
            changeStatus = 'Disable';
        }

        Swal.fire({
            icon: "warning",
            title: "Are you sure?",
            html: "You want to change status from <b>" + staticStatus + "</b> to <b>" + changeStatus + "</b> ?",
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
                    data: {
                        courseId: courseId,
                        status: clickChangeStatus
                    },
                    success: function (res) {

                        if (res.status) {
                            toastr.success(res.message);

                            // reload to reflect correct icon
                            setTimeout(() => {
                                location.reload();
                            }, 800);

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
                            setTimeout(function () { window.location.href = baseUrl + '/courses' }, 1500);
                        }
                        else { toastr.error(res.message); }
                    }
                });
            }
        });
});

});