$(window).on('load', function () {

    // Form submits for name translation
    $(".gt-word-translation-form").on("submit", function () {
        gt_cz_en_transcription($(this).data("type"));
    });

    // Autocomplete and result selection handling if needed
    $(GT_SELECTOR.CZ_EN_TRANSLATION_AUTOCOMPLETE_INPUTS).on("autocompleteselect", function (event, ui) {
        if (ui.item.help) return false;
        $(this).val(ui.item.value);
        $(this).closest("form").submit();
    });
});

/**
 * Modified function for handling name translations
 * @param {string} type - Type of translation (e.g., 'fn-translation-en-cz', 'fn-translation-cz-en')
 */
function gt_cz_en_transcription(type) {
    let input_slr = $(GT_SELECTOR.CZ_EN_TRANSLATION_AUTOCOMPLETE_INPUTS.replace("${type}", type));
    let output_slr = $(GT_SELECTOR.CZ_EN_TRANSLATION_OUTPUT.replace("${type}", type));
    let output_print_slr = $(GT_SELECTOR.CZ_EN_TRANSLATION_PRINT_OUTPUT.replace("${type}", type));

    input_slr.prop('disabled', true);
    output_slr.html("<td>Loading...</td>");

    let value = input_slr.val();

    $.post(__ajax_obj.url, {
        _ajax_nonce: __ajax_obj.nonce,
        action: "gt_en_cz_translation",
        word_cz: value,
        type: type
    }, function (data) {
        if (data.hasOwnProperty("status") && data.status === "success") {
            _gt_name_transcription_data_process(type, value, data.results, output_slr, output_print_slr);
        } else {
            output_slr.html("<option disabled selected hidden>Error: Unable to process request.</option>");
        }
        input_slr.prop('disabled', false);
    }, "json")
        .fail(function () {
            output_slr.html("<option disabled selected hidden>Error: Unable to retrieve translation.</option>");
            input_slr.prop('disabled', false);
        });
}

/**
 * Process data for name translation and display results
 */
function _gt_name_transcription_data_process(type, value, data, output_slr, output_print_slr) {
    output_slr.html("");
    let print_str = "<h3>";

    // Adjust headers based on the translation type

    print_str += "English names for ";

    print_str += value.toUpperCase() + ": </h3>";
    output_print_slr.html(print_str);

    if ($.isEmptyObject(data)) {
        output_slr.append("<td>No results found.</td>");
    } else {
        for (let result of data) {
            output_slr.append(`<tr><td title='Click to copy' onclick='copy(this);' value='${result.word_cz}' data-id='${result.id}'>${result.word_en}</td></tr>`);
            output_print_slr.append(result.name + "<br>");
        }
    }
}
