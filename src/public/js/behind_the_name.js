$(window).on('load', function () {

    /**
     * ON AUTOCOMPLETESELECT
     * - Diminutives
     */
    $(GT_SELECTOR.BEHIND_THE_NAME_FN_DIMINUTIVE_INPUT).on("autocompleteselect", function (event, ui) {
        // Ignore if object has defined 'help' field
        if (ui.item.help)
            return false;

        process_diminutives(ui.item.name);
    });

    /**
     * ON AUTOCOMPLETESELECT
     * - Name meanings
     */
    $(GT_SELECTOR.BEHIND_THE_NAME_LN_EXPLANATION_INPUT).on("autocompleteselect", function (event, ui) {
        // Ignore if object has defined 'help' field
        if (ui.item.help)
            return false;

        process_name_meanings(ui.item.name);
        //disable input
    });
});

//#region FUNCTIONS

/**
 * Process data to request results for diminutives of a specific entry
 * @param name First name
 */
function process_diminutives(name) {
    let input_slr = $(GT_SELECTOR.BEHIND_THE_NAME_FN_DIMINUTIVE_INPUT);
    let html_output_slr = $(GT_SELECTOR.BEHIND_THE_NAME_FN_DIMINUTIVE_OUTPUT);
    let html_output_for_printing_slr = $(GT_SELECTOR.BEHIND_THE_NAME_FN_DIMINUTIVE_PRINT_OUTPUT);
    let action = "gt_behind_the_name_fn_diminutives";

    _process_request(action, "Diminutive forms", name, input_slr, html_output_slr, html_output_for_printing_slr);
}

/**
 * Process data to request results for name meaning of a specific entry
 * @param name Name
 */
function process_name_meanings(name) {
    let input_slr = $(GT_SELECTOR.BEHIND_THE_NAME_LN_EXPLANATION_INPUT);
    let html_output_slr = $(GT_SELECTOR.BEHIND_THE_NAME_LN_EXPLANATION_OUTPUT);
    let html_output_for_printing_slr = $(GT_SELECTOR.BEHIND_THE_NAME_LN_EXPLANATION_PRINT_OUTPUT);
    let action = "gt_behind_the_name_ln_explanations";

    _process_request(action, "Name meaning", name, input_slr, html_output_slr, html_output_for_printing_slr);
}

/**
 * AJAX call for retrieving diminutives/explanations etc. data
 * @param action AJAX action
 * @param label Label that identifies calling function
 * @param name The name.
 * @param input_slr Input selector
 * @param html_output_slr Output selector
 * @param html_output_for_printing_slr Print output selector
 */
function _process_request(action, label, name, input_slr, html_output_slr, html_output_for_printing_slr) {
    let heading = `${label} for `;

    input_slr.prop('disabled', true);

    $.post(__ajax_obj.url, {
        _ajax_nonce: __ajax_obj.nonce,
        action: action,
        name: name,
    }, function (data) {
        // Check if the response is in a valid format...
        if (data.hasOwnProperty("status")) {
            // On successful request response...
            if (data.status === "success") {
                html_output_slr.html("");

                if (data.results.length === 0) {
                    html_output_slr.html("<tr><td>No results found.</td></tr>");
                } else {
                    html_output_for_printing_slr.html(`<h3>${heading}${name.toUpperCase()}: </h3>`);
                    for (let result in data.results) {
                        result = data.results[result];
                        html_output_slr.append("<tr><td title='Click to copy' onmouseover='change(this, true)' onmouseleave='change(this, false)' onclick='copy(this);'>" + result + "</td></tr>");
                        html_output_for_printing_slr.append(result + "<br>");
                    }
                }
            }
            // Otherwise, not acceptable response...
            else {
                console.log(`Name translation process error: ${data.error_msg}`);
            }
        }
        // Otherwise, there is unknown/not-expected response...
        else {
            html_output_slr.html("<tr><td>Unknown server error. Please, constant the developers about this issue.</td></tr>");
        }

        // Re-enable input
        input_slr.prop('disabled', false);

    }, "json")
        .fail(function () {
            html_output_slr.html(`<tr><td>Unable to retrieve the ${label.toLowerCase()}.</td></tr>`);
            // Re-enable input
            input_slr.prop('disabled', false);
        });
}

//#endregion
