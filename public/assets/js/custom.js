$(document).ready(function () {
    // Initialize Select2
    if ($('.select2').length > 0) {
        $('.select2').select2({
            width: '100%',
            placeholder: 'Select an option'
        });
    }

    // Clear validation error on input change globally
    $(document).on('input change', '.is-invalid', function () {
        $(this).removeClass('is-invalid');
        
        let $target = $(this);
        if ($(this).hasClass('select2-hidden-accessible')) {
            $target = $(this).next('.select2-container');
        }
        
        let $feedback = $target.next('.invalid-feedback');
        
        // If not immediately next, look in siblings
        if ($feedback.length === 0) {
            $feedback = $target.siblings('.invalid-feedback');
        }
        
        if ($feedback.length > 0) {
            if ($feedback.attr('id')) {
                $feedback.css('display', 'none').text('');
            } else {
                $feedback.remove();
            }
        }
    });
});