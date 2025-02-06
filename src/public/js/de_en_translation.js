jQuery(document).ready(function ($) {
    $('#de-en-translation-submit').on('click', function (e) {
        e.preventDefault();
        var word = $('#gt-de-en-translation-input').val().trim();

        if (word !== '') {
            $.ajax({
                url: gt_translation_data_de.ajaxurl, // Opraveno
                type: 'POST',
                data: {
                    action: 'gt_de_en_translation',
                    word_de: word,
                    _ajax_nonce: gt_translation_data_de._ajax_nonce // Opraveno
                },
                success: function (response) {
                    if (response.success) {
                        $('#de-en-translation-output').html('<tr><td>' + response.data.translation + '</td></tr>');
                    } else {
                        $('#de-en-translation-output').html('<tr><td>No translation found.</td></tr>');
                    }
                },
                error: function () {
                    $('#de-en-translation-output').html('<tr><td>Error retrieving translation.</td></tr>');
                }
            });
        }
    });
});
