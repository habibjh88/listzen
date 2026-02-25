jQuery(document).ready(function($) {
    function setupUploader(button) {
        const container = button.closest('.field-custom');
        const input = container.find('.listzen-menu-image');
        const preview = container.find('.listzen-menu-image-preview');
        const removeBtn = container.find('.remove-listzen-menu-image');

        button.on('click', function(e) {
            e.preventDefault();

            const mediaUploader = wp.media({
                title: 'Choose Image',
                button: { text: 'Select Image' },
                multiple: false
            });

            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();
                input.val(attachment.url);
                preview.attr('src', attachment.url).show();
                removeBtn.show();
            });

            mediaUploader.open();
        });

        removeBtn.on('click', function(e) {
            e.preventDefault();
            input.val('');
            preview.hide();
            $(this).hide();
        });
    }

    $('.upload-listzen-menu-image').each(function() {
        setupUploader($(this));
    });
});