/**
 * @type {number} Minimum required autocomplete characters to trigger whisperer.
 */
const MIN_REQ_AC_CHARS = 2;

$(document).ready(function ($) {

    /**
     * Set default settings for any gt-autocompleted input.
     */
    $(GT_SELECTOR.AUTOCOMPLETE_INPUTS).autocomplete({
        source: [{value: "", label: `Please enter at least ${MIN_REQ_AC_CHARS} characters.`, help: true}],
        delay: 500,
        minLength: 0
    });

    //#region EVENTS

    /**
     * ON FOCUS
     * - Enable autocomplete whisperer.
     */
    $(GT_SELECTOR.AUTOCOMPLETE_INPUTS).focus(function () {
        $(this).autocomplete("option", "disabled", false);
        $(this).trigger('input');
    });

    /**
     * ON INPUT
     * - Trigger events when user inputs data.
     */
    $(GT_SELECTOR.AUTOCOMPLETE_INPUTS).on('input', function () {
        gt_autocomplete($(this));
    });

    /**
     * ON FOCUSOUT
     * - Disable autocomplete whisperer on losing focus.
     */
    $(GT_SELECTOR.AUTOCOMPLETE_INPUTS).on('focusout', function () {
        $(this).autocomplete("option", "disabled", true);
        gt_autocomplete($(this));
    });

    //#endregion

});

//#region FUNCTIONS

/**
 * The base autocomplete functionality for the plugin's user inputs
 * -
 * It requires input field (HTML: input) with 2 required values that are parsed in further tasks:
 *     - value of the input field as a searched query/token
 *     - data-type attribute that indicates the type of the request
 *       based on which is the request processed by a specific method to retrieve the correct data.
 *
 * @param field Input field reference
 */
function gt_autocomplete(field) {
    let value = field.val();
    let type = field.data("type");
    let ac_results = [];

    // If the required length is not met...
    if (value.length < MIN_REQ_AC_CHARS) {
        ac_results.push({value: value, label: `Please enter at least ${MIN_REQ_AC_CHARS} characters.`, help: true});
        _gt_autocomplete_set_and_search(field, ac_results);
    }
    // Otherwise, get data for the whisperer...
    else {
        // Autocomplete AJAX CALL
        $.post(__ajax_obj.url, {
            _ajax_nonce: __ajax_obj.nonce,
            action: "gt_autocomplete",
            value: value,
            type: type
        }, function (data) {
            // Check if the response is in a valid format...
            if (data.hasOwnProperty("status")) {
                // On successful request response...
                if (data.status === "success") {
                    if (data.results.length === 0)
                        ac_results.push({value: value, label: 'No results found.', help: true});
                    else
                        ac_results = _gt_autocomplete_process_data(data.results, type);
                }
                // Otherwise, not acceptable response...
                else {
                    console.log(`Autocomplete error: ${data.error_msg}`);
                }
            }
            // Otherwise, there is unknown/not-expected response...
            else {
                ac_results.push({value: value, label: 'Unknown server error. Please, constant the developers about this issue.', help: true});
            }

            _gt_autocomplete_set_and_search(field, ac_results);

        }, "json")

            // On failure...
            .fail(function () {
                ac_results.push({value: value, label: 'Unable to retrieve searched results.', help: true});
                _gt_autocomplete_set_and_search(field, ac_results);
            });
    }
}

/**
 * Process the dataw into object arrays that are used in autocomplete sources in further use.
 * @param data The data to process
 * @param type  Type of the autocomplete request to be able to choose the data processing method
 * @returns {[object]} Array of objects that are used as a source for autocomplete
 * - The return object in the array should always contain field 'value' (raw value) and 'label' (formatted value)
 * @private
 */
function _gt_autocomplete_process_data(data, type) {
    let results = [];

    for (let i = 0; i < data.length; ++i) {
        switch (type) {
            case GT_AUTOCOMPLETE_TYPE.GERMAN_TERMINOLOGY_GERMAN_CITY:
                results.push({
                    value: data[i].name_de,
                    label: `${data[i].name_cz} (${data[i].name_de}) - ${data[i].district_name_en}`,
                    name_cz: data[i].name_cz,
                    district_name_en: data[i].district_name_en
                });
                break;
            case GT_AUTOCOMPLETE_TYPE.BEHIND_THE_NAME_FN_DIMINUTIVE:
                results.push({
                    value: data[i].name,
                    label: `${data[i].name}`,
                    name: data[i].name
                });
                break;
            case GT_AUTOCOMPLETE_TYPE.BEHIND_THE_NAME_LN_EXPLANATION:
                results.push({
                    value: data[i].name,
                    label: `${data[i].name}`,
                    name: data[i].name
                });
                break;
            case GT_AUTOCOMPLETE_TYPE.CHANGING_NAMES_FEMALE_VARIANT:
                results.push({
                    value: data[i].name,
                    label: `${data[i].name}`,
                    name: data[i].name
                });
                break;
            case GT_AUTOCOMPLETE_TYPE.CHANGING_NAMES_FN_TRANSLATION_EN_CZ:
                results.push({
                    value: data[i].name,
                    label: `${data[i].name}`,
                    name: data[i].name
                });
                break;
            case GT_AUTOCOMPLETE_TYPE.CHANGING_NAMES_FN_TRANSLATION_CZ_EN:
                results.push({
                    value: data[i].name,
                    label: `${data[i].name}`,
                    name: data[i].name
                });
                break;
            case GT_AUTOCOMPLETE_TYPE.NAME_DISTRIBUTION_LN:
                results.push({
                    value: data[i].name,
                    label: `${data[i].name}`,
                    id: data[i].id
                });
                break;
            case GT_AUTOCOMPLETE_TYPE.NAME_DISTRIBUTION_MEP:
                results.push({
                    value: data[i].name_cz,
                    label: `${data[i].name_cz} (${data[i].name_de})`,
                    id: data[i].id,
                    name_de: data[i].name_de
                });
                break;
            case GT_AUTOCOMPLETE_TYPE.TRANSLATION_EN_CZ:
                results.push({
                    value: data[i].word_cz,
                    label: `${data[i].word_cz}`,
                    word_cz: data[i].word_cz,

                });
                break;
            case GT_AUTOCOMPLETE_TYPE.TRANSLATION_LA_EN:
                results.push({
                    value: data[i].word_la,
                    label: `${data[i].word_la}`,
                    word_la : data[i].word_la,
                })
                break;
            default:
                break;
        }
    }

    return results;
}

/**
 * Set the source options to input autocomplete and invoke search method
 * @param field Input field reference
 * @param optionSource Source options to set to the autocomplete input
 * @private
 */
function _gt_autocomplete_set_and_search(field, optionSource) {
    field.autocomplete("option", "source", optionSource);
    field.autocomplete("search", ""); // Invoke the search method
}

//#endregion
