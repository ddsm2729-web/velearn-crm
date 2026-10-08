$(document).ready(function () {

    // Show Create-Plan-Form
    $('body').on('click', '#create-plans-btn', function () {
        $.get(baseUrl + '/plans/create', function (response) {
            $('body').find('#plans-form-section').html(response).slideDown('slow');
        })
        .fail(function (xhr) {
            console.error("Failed to load content:", xhr.status, xhr.responseText);
        });
    });

    // Create Appointment Plan
    $('body').on('click', '#create-plan-submit', function () {
        $loader = $(this).find('#loader-spinner');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('body').find('#create-plan-form').attr('action'),
            type: 'POST',
            data: $('body').find('#create-plan-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if(res.status) {
                    $('body').find('#plan-form-section').html("").slideUp('slow');
                    toastr.success(res.message);
                    setTimeout(function () { window.location.reload() },900);
                }
                else { toastr.error(res.message); }
            },
            error: function (err) {
                $('body').find('#plan_name_err').css('display','block').text(err.responseJSON.errors.plan_name[0]);
                $('body').find('#plan_fee_err').css('display','block').text(err.responseJSON.errors.plan_fee[0]);
                $('body').find('#plan_gst_err').css('display','block').text(err.responseJSON.errors.plan_gst[0]);
                $('body').find('#plan_total_err').css('display','block').text(err.responseJSON.errors.plan_total[0]);
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        })
    });
    /******************************************* Appointment Plan JS END  *******************************************/

    // Show Create-Review-Form
    $('body').on('click', '#create-review-btn', function () {
        $.get(baseUrl + '/review/create', function (response) {
            $('body').find('#review-form-section').html(response).slideDown('slow');
        })
        .fail(function (xhr) {
            console.error("Failed to load content:", xhr.status, xhr.responseText);
        });
    });

    // Create Appointment Review
    $('body').on('click', '#create-review-submit', function () {
        $loader = $(this).find('#loader-spinner');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('body').find('#create-review-form').attr('action'),
            type: 'POST',
            data: $('body').find('#create-review-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if(res.status) {
                    $('body').find('#review-form-section').html("").slideUp('slow');
                    toastr.success(res.message);
                    setTimeout(function () { window.location.reload() },900);
                }
                else { toastr.error(res.message); }
            },
            error: function (err) {
                $('body').find('#review_period_err').css('display','block').text(err.responseJSON.errors.review_period[0]);
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        })
    });
    /******************************************* Appointment Review Period JS END  *******************************************/

    // Show Create-Reopen-Form
    $('body').on('click', '#create-reopen-btn', function () {
        $.get(baseUrl + '/reopen/create', function (response) {
            $('body').find('#reopen-form-section').html(response).slideDown('slow');
        })
        .fail(function (xhr) {
            console.error("Failed to load content:", xhr.status, xhr.responseText);
        });
    });

    // Create Appointment Reopen
    $('body').on('click', '#create-reopen-submit', function () {
        $loader = $(this).find('#loader-spinner');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $('body').find('#create-reopen-form').attr('action'),
            type: 'POST',
            data: $('body').find('#create-reopen-form').serialize(),
            beforeSend: function () {
                $loader.removeClass('d-none');
            },
            success: function (res) {
                if(res.status) {
                    $('body').find('#reopen-form-section').html("").slideUp('slow');
                    toastr.success(res.message);
                    setTimeout(function () { window.location.reload() },900);
                }
                else { toastr.error(res.message); }
            },
            error: function (err) {
                $('body').find('#reopen_period_err').css('display','block').text(err.responseJSON.errors.reopen_period[0]);
            },
            complete: function () {
                $loader.addClass('d-none');
            }
        })
    });
    /******************************************* Appointment Re-Open Period JS END  *******************************************/


});