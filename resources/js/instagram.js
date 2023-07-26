$(function() {
    $('input[name="file_type"]').on('change', function() {
        if (this.value === 'IMAGE') {
            // $('input[name="post_type"]').val('');
            $('#reel').prop('checked', false);
            $('#reel').parent().addClass('hidden');

            $('#wall').parent().removeClass('hidden');
        }
        else if (this.value === 'VIDEO') {
            // $('input[name="post_type"]').val('');
            $('#wall').prop('checked', false);
            $('#wall').parent().addClass('hidden');

            $('#reel').parent().removeClass('hidden');
        }
    });

    $('.js-instagram-post').on('click', function() {
        const $this = $(this);
        $this.parent().find('.js-modal-button').click();

        const mediaType = $this.data('mediaType');
        const caption = $this.data('caption');
        const permalink = $this.data('permalink');
        const mediaUrl = $this.data('mediaUrl');

        if (mediaType !== 'VIDEO') {
            $('#js-modal-img').attr('src', mediaUrl);
            $('#js-modal-vid').addClass('hidden');
            $('#js-modal-img').removeClass('hidden');
        } else {
            $('#js-modal-vid').attr('src', mediaUrl);
            $('#js-modal-img').addClass('hidden');
            $('#js-modal-vid').removeClass('hidden');
        }

        $('#js-modal-caption').text(caption);
        $('#js-modal-permalink').attr('href', permalink);
        $('#js-modal-permalink').text(permalink);
    })
});