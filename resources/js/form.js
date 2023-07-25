$(function() {
    $('.js-form-submit').on('click', function(e) {
        $('.js-form').submit();
        $('.js-submitting-message').removeClass('hidden');
        $('.js-loader').removeClass('hidden');

        $(this).get(0).firstChild.nodeValue = "SAVING";
        $(this).prop('disabled', true);

    })
})