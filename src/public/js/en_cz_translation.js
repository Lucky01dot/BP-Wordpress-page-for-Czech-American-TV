$(window).on('load', function () {
    // Form submits for word translation
    $(".gt-word-translation-form").on("submit", function (e) {
        e.preventDefault(); // Zabraňte výchozímu odeslání formuláře
        gt_cz_en_transcription($(this).data("type"));
    });

    // Autocomplete and result selection handling
    $(GT_SELECTOR.CZ_EN_TRANSLATION_AUTOCOMPLETE_INPUTS).on("autocompleteselect", function (event, ui) {
        if (ui.item.help) return false; // Ignore help items
        $(this).val(ui.item.value);
        $(this).closest("form").submit();
    });
});



/**
 * Handle word translations
 * @param {string} type - Type of translation (e.g., 'fn-translation-en-cz', 'fn-translation-cz-en')
 */
function gt_cz_en_transcription(type) {
    const inputSelector = $(GT_SELECTOR.CZ_EN_TRANSLATION_AUTOCOMPLETE_INPUTS.replace("${type}", type));
    const outputSelector = $(GT_SELECTOR.CZ_EN_TRANSLATION_OUTPUT.replace("${type}", type));
    const outputPrintSelector = $(GT_SELECTOR.CZ_EN_TRANSLATION_PRINT_OUTPUT.replace("${type}", type));
    const word2VecOutputSelector = $("#cz-en-word2vec-output");

    // Disable input and show loading
    inputSelector.prop('disabled', true);
    outputSelector.html("<td>Loading...</td>");
    word2VecOutputSelector.html("<td>Loading Word2Vec...</td>"); // Přidáme loading i pro Word2Vec

    const value = inputSelector.val();

    // ✅ Fetch Word2Vec Suggestion IMMEDIATELY


    $.post(__ajax_obj.url, {
        _ajax_nonce: __ajax_obj.nonce,
        action: "gt_en_cz_translation",
        word_cz: value,
        type: type
    }, function (data) {
        if (data.status === "success") {
            _gt_name_transcription_data_process(type, value, data.results, outputSelector, outputPrintSelector);
        } else {
            outputSelector.html("<td>Error: Unable to process request.</td>");
        }
        inputSelector.prop('disabled', false);
    }, "json").fail(function () {
        outputSelector.html("<td>Error: Unable to retrieve translation.</td>");
        inputSelector.prop('disabled', false);
    });
}

/**
 * Fetch Word2Vec suggestion for a translated word
 * @param {string} word - Translated word
 * @param {jQuery} outputSelector - Output selector for Word2Vec suggestion
 */
function fetchWord2VecSuggestion(word, outputSelector) {
    $.post(gt_Word2Vec_suggestion_cz.url, { // Používáme správný objekt
        _ajax_nonce: gt_Word2Vec_suggestion_cz.nonce, // Používáme správný nonce
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



/**
 * Process and display translation results
 * @param {string} type - Type of translation
 * @param {string} value - Input value
 * @param {Array} data - Translation results
 * @param {jQuery} outputSelector - Output table selector
 * @param {jQuery} outputPrintSelector - Print output selector
 */
function _gt_name_transcription_data_process(type, value, data, outputSelector, outputPrintSelector) {
    outputSelector.html(""); // Clear output


    if ($.isEmptyObject(data)) {
        outputSelector.append("<tr><td>No results found.</td></tr>");
    } else {
        data.forEach(result => {
            let translatedWord = result.word_en || result.word_la; // Rozpoznání správného klíče
            outputSelector.append(
                `<tr>
                    <td title='Click to copy' onclick='copy(this);' data-id='${result.id}'>${translatedWord}</td>
                </tr>`
            );
            if(translatedWord){
                fetchWord2VecSuggestion(translatedWord, $("#cz-en-word2vec-output"));
            }

        });
    }
}
