jQuery(document).ready(function ($) {
    // Autocomplete pro německé slovo
    $('#gt-de-en-translation-input').on('input', function () {
        let query = $(this).val().trim();

        if (query.length < 2) return; // Spustíme až po 2 znacích

        $.ajax({
            url: gt_translation_data_de.ajaxurl,
            method: 'GET',
            data: {
                action: 'gt_de_en_autocomplete',
                query: query,
                _ajax_nonce: gt_translation_data_de._ajax_nonce
            },
            success: function (response) {
                if (response.success) {
                    let suggestions = response.data.suggestions;
                    let datalist = $('#de-en-translation-datalist');
                    datalist.empty();

                    suggestions.forEach(function (suggestion) {
                        datalist.append(`<option value="${suggestion}">`);
                    });
                }
            }
        });
    });

    // Překlad slova po kliknutí na tlačítko
    $('#de-en-translation-submit').on('click', function (e) {
        e.preventDefault();
        var word = $('#gt-de-en-translation-input').val().trim();


        if (word !== '') {
            $.ajax({
                url: gt_translation_data_de.ajaxurl,
                type: 'POST',
                data: {
                    action: 'gt_de_en_translation',
                    word_de: word,
                    _ajax_nonce: gt_translation_data_de._ajax_nonce
                },
                success: function (response) {
                    if (response.success) {
                        $('#de-en-translation-output').html('<tr><td>' + response.data.translation + '</td></tr>');
                        fetchWord2VecSuggestion(response.data.translation,$("#de-en-word2vec-output"))

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
jQuery(document).ready(function ($) {
    $('.gt-typer-addbtn').on('click', function () {
        let specialChar = $(this).text();
        let input = $('#gt-de-en-translation-input');
        input.val(input.val() + specialChar);
        input.focus(); // Zaměření zpět na vstupní pole
    });
});

function fetchWord2VecSuggestion(word, outputSelector) {
    $.post(gt_Word2Vec_suggestion_de.url, { // Používáme správný objekt
        _ajax_nonce: gt_Word2Vec_suggestion_de.nonce, // Používáme správný nonce
        action: "gt_word2vec",
        word: word,
    }, function (data) {
        if (data.status === "success") {
            outputSelector.html(`
                <p><strong>Similar Words:</strong> ${data.suggestions.join(", ")}</p>
            `);
        } else {
            outputSelector.html("<p><strong>Error:</strong> No similar words found.</p>");
        }
    }, "json").fail(function () {
        outputSelector.html("<p><strong>Error:</strong> Failed to fetch data.</p>");
    });
}
