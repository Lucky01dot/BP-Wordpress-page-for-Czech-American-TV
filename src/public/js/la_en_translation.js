$(window).on('load', function () {
    // Form submits for word translation
    $(".gt-word-translation-form_la_en").on("submit", function (e) {
        e.preventDefault(); // Zabraňte výchozímu odeslání formuláře
        gt_la_cz_en_translation($(this).data("type"));
    });

    // Autocomplete and result selection handling
    $(GT_SELECTOR.LA_EN_TRANSLATION_AUTOCOMPLETE_INPUTS).on("autocompleteselect", function (event, ui) {
        if (ui.item.help) return false; // Ignore help items
        $(this).val(ui.item.value);
        $(this).closest("form").submit();
    });
});

/**
 * Handle Latin to Czech to English translation
 * @param {string} type - Type of translation
 */
function gt_la_cz_en_translation(type) {
    const inputSelector = $(GT_SELECTOR.LA_EN_TRANSLATION_AUTOCOMPLETE_INPUTS.replace("${type}", type));
    const outputSelector = $(GT_SELECTOR.LA_EN_TRANSLATION_OUTPUT.replace("${type}", type));
    const outputPrintSelector = $(GT_SELECTOR.LA_EN_TRANSLATION_PRINT_OUTPUT.replace("${type}", type));

    // Disable input and show loading
    inputSelector.prop('disabled', true);
    outputSelector.html("<td>Loading...</td>");

    const value = inputSelector.val();

    // Step 1: Get Czech translation from database
    $.post(__ajax_obj.url, {
        _ajax_nonce: __ajax_obj.nonce,
        action: "gt_la_cz_translation",
        word_la: value,
        type: type
    }, function (data) {
        if (data.status === "success" && data.results.length > 0) {
            const firstCzechTranslation = data.results[0].word_cz;

            // Step 2: Translate Czech to English
            $.post(__ajax_obj.url, {
                _ajax_nonce: __ajax_obj.nonce,
                action: "gt_la_cz_en_translation",
                word_cz: firstCzechTranslation
            }, function (response) {
                if (response.success) {
                    outputSelector.html(
                        `<tr>
                            <td title='Click to copy' onclick='copy(this);'>${response.data.translation}</td>
                        </tr>`
                    );
                } else {
                    outputSelector.html("<td>Error: Unable to retrieve English translation.</td>");
                }
                inputSelector.prop('disabled', false);
            }, "json").fail(function () {
                outputSelector.html("<td>Error: Unable to process English translation.</td>");
                inputSelector.prop('disabled', false);
            });

        } else {
            outputSelector.html("<td>No Czech translation found.</td>");
            inputSelector.prop('disabled', false);
        }
    }, "json").fail(function () {
        outputSelector.html("<td>Error: Unable to retrieve Czech translation.</td>");
        inputSelector.prop('disabled', false);
    });
}
