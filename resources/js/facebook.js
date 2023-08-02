$(function() {
    $('input[name="upload"]').on('change', function() {
        const linkInput = '.js-link-input';
        const videoUpload = '.js-video-upload';
        const imageUpload = '.js-image-upload';

        if (this.value === 'link') {
            toggleInputs(linkInput, videoUpload, imageUpload);
        } else if (this.value === 'video') {
            toggleInputs(videoUpload, linkInput, imageUpload);
        } else if (this.value === 'image') {
            toggleInputs(imageUpload, linkInput, videoUpload);
        } else {
            toggleInputs('', imageUpload, linkInput, videoUpload);
        }
    });

    function toggleInputs(toEnable, toDisable1, toDisable2, toDisable3 = '') {
        $(toDisable1).find('input').prop('disabled', true);
        $(toDisable2).find('input').prop('disabled', true);
        
        $(toDisable1).addClass('hidden');
        $(toDisable2).addClass('hidden');
        
        if (toEnable !== '') {
            $(toEnable).find('input').prop('disabled', false);
            $(toEnable).removeClass('hidden');
        }
        if (toDisable3 !== '') {
            $(toDisable3).find('input').prop('disabled', true);
            $(toDisable3).addClass('hidden');
        }
    }
});