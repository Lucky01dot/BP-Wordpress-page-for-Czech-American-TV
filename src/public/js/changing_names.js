$(window).on('load', function () {

    // Form submits
    $(".gt-changing-names-form").on("submit", function () {
        gt_name_transcription($(this).data("type"));
    });

    // Copy male to female
    $("#gt-changing-names-mtf").on("click", function () {
        var male = $("#gt-changing-names-ln-transcription-output").val();
        if (male !== null) {
            $("#gt-changing-names-female-variant-input").val(male);
            $('.gt-changing-names-form[data-type="female-variant"]').submit();
        }
    });

    // Find femvar on lname change if checked
    $(GT_SELECTOR.CHANGING_NAMES_OUTPUT.replace("${type}", GT_CHANGING_NAME_TYPE.LN_TRANSCRIPTION)).change(function () {
        if ($("#gt-changing-names-mtf-auto").prop("checked")) {
            $("#gt-changing-names-mtf").click();
        }
    });

    // Copy name id from options to selects
    $(GT_SELECTOR.CHANGING_NAMES_RESULTS).change(function () {
        $(this).data("id", $(this).children(":selected").data("id"));
    });

    /**
     * ON AUTOCOMPLETESELECT
     * - All autocomplete inputs on page Changing names
     */
    $(GT_SELECTOR.CHANGING_NAMES_AUTOCOMPLETE_INPUTS).on("autocompleteselect", function (event, ui) {
        // Ignore if object has defined 'help' field
        if (ui.item.help)
            return false;

        $(this).val(ui.item.value);
        $(this).closest("form").submit();
    });
});

//#region FUNCTIONS

/**
 * Based on {@see GT_CHANGING_NAME_TYPE} as {@param type}, the method sends a request and retrieve the result data into the corresponding fields.
 * @param {string} type type of request - ln-transcription, fn-translation-en-cz, fn-translation-cz-en or female-variant.
 */
function gt_name_transcription(type) {
    let input_slr = $(GT_SELECTOR.CHANGING_NAMES_INPUT.replace("${type}", type));
    let output_slr = $(GT_SELECTOR.CHANGING_NAMES_OUTPUT.replace("${type}", type));
    let output_print_slr = $(GT_SELECTOR.CHANGING_NAMES_PRINT_OUTPUT.replace("${type}", type));

    // Disable
    input_slr.prop('disabled', true);

    output_slr.html("<td>Loading...</td>");

    // Get hte user input / value
    let value = input_slr.val();

    // Call the AJAX for the result based on the value
    $.post(__ajax_obj.url, {
        _ajax_nonce: __ajax_obj.nonce,
        action: "gt_changing_names",
        name: value,
        type: type
    }, function (data) {
        // Check if the response is in a valid format...
        if (data.hasOwnProperty("status")) {
            // On successful request response...
            if (data.status === "success") {
                _gt_name_transcription_data_process(type, value, data.results, output_slr, output_print_slr);
            }
            // Otherwise, not acceptable response...
            else {
                console.log(`Name transcription process error: ${data.error_msg}`);
                output_slr.html("<option disabled selected hidden>Server error. Please, contact the developers about this issue.</option>");
            }
        }
        // Otherwise, there is unknown/not-expected response...
        else {
            output_slr.html("<option disabled selected hidden>Unknown server error. Please, contact the developers about this issue.</option>");
        }

        // Re-enable input
        input_slr.prop('disabled', false);

    }, "json")
        .fail(function () {
            output_slr.html("<option disabled selected hidden>Unable to retrieve the transcription data.</option>");
            // re enable input
            input_slr.prop('disabled', false);
        });
}

/**
 * Process successfully retrieved data
 * @private
 */
function _gt_name_transcription_data_process(type, value, data, output_slr, output_print_slr) {
    output_slr.html("");

    let print_str = "<h3>";

    switch (type) {
        case GT_CHANGING_NAME_TYPE.FN_TRANSLATION_EN_CZ:
            print_str += "Czech first names";
            break;
        case GT_CHANGING_NAME_TYPE.FN_TRANSLATION_CZ_EN:
            print_str += "English first names";
            break;
        case GT_CHANGING_NAME_TYPE.LN_TRANSCRIPTION:
            print_str += "Czech last names";
            break;
        case GT_CHANGING_NAME_TYPE.FEMALE_VARIANT:
            print_str += "Czech female last names";
            break;
    }

    print_str += " for " + value.toUpperCase() + ": </h3>";

    output_print_slr.html(print_str);

    if ($.isEmptyObject(data)) {
        // no results found
        output_slr.append("<td>No results found.</td>");
    } else {
        if (type === GT_CHANGING_NAME_TYPE.LN_TRANSCRIPTION) {
            // count total count of all resulting names
            let total_count = 0;
            for (let result in data) {
                result = data[result];
                total_count += parseInt(result.count);
            }

            for (let result in data) {
                result = data[result];
                percentage = parseInt(result.count) / total_count * 100;

                let content = result.name + " (" + percentage.toFixed(1) + " %)";

                output_slr.append("<tr><td title='Click to copy' onmouseover='change(this, true)' onmouseleave='change(this, false)' onclick='copy(this);' value='" + result.name + "' data-id='" + result.id + "'>" + content + " </td></tr>");
                output_print_slr.append(content + "<br>");
            }
        } else {
            for (let result in data) {
                result = data[result];
                output_slr.append("<tr><td title='Click to copy'  onmouseover='change(this, true)' onmouseleave='change(this, false)'  onclick='copy(this);' value='" + result.name + "' data-id='" + result.id + "'>" + result.name + "</td></tr>");
                output_print_slr.append(result.name + "<br>");
            }

        }
    }

    output_slr.change();
}

//#endregion
