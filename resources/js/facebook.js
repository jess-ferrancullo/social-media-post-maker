$(function() {
    $('input[name="upload"]').on('change', function() {
        if (this.value === 'link') {
            $('.js-link-input').removeClass('hidden');
        } else {
            $('.js-link-input').addClass('hidden');
        }
    });
});