//#region WP INJECTS

__ajax_obj = __ajax_obj; // WP reference injected via `wp_localize_script`
__wp_vars = __wp_vars;

//#endregion

$(document).ready(function ($) {
    // Make sure the help text boxes are hidden initially
    $(GT_SELECTOR.HELP_TEXT_BOXES).hide();
    $(GT_SELECTOR.HELP_TEXT_BOXES).removeClass("d-none");

    // Allow right-clicks on inputs
    $(":text").contextmenu(function (event) {
        event.stopPropagation();
    });

    /**
     * ON CLICK
     * - Buttons that move the user to the map page with already searched data based on the parameters
     * It requires the selected object to have 1 parameter:
     * - 'data-target': contains ID of another input that contains the name (value)
     */
    $(GT_SELECTOR.MAP_INFO_BTNS).on("click", function () {
        let slr = $("#" + $(this).data("target"));

        let name = slr.val();
        if (name === null || name === undefined || name.length === 0) {
            return;
        }

        window.location.assign(`../map?gt-last-name=${name}`);
    });

    /**
     * ON CLICK
     * - Copy button logic
     *
     * It requires the button to have 1 parameter:
     * - 'data-target': contains the ID of another input that contains the value to copy.
     */
    $(".gt-copy-btn").on("click", function () {
        let target = $("#" + $(this).data("target"));

        $(this).append("<input type='text' id='gt-copy-input' style='position: absolute; top:-1000px;'>");

        let cpy = $("#gt-copy-input");
        cpy.val(target.val());
        cpy.select();
        document.execCommand("copy");
        cpy.remove();
    });


    /**
     * ON CLICK
     * - Print button logic
     *
     * It requires the button to have 1 parameter:
     * - 'data-target': contains the ID of another input that contains the value to copy.
     */
    $(document).on('click', '.gt-print-btn', function () {
        let target = $("#" + $(this).data("target"));
        let print_temp_slr = $("#gt-print");

        print_temp_slr.html("<img src='" + __wp_vars.logo_uri + "' alt='Czech American TV logo' class='logo'>");
        print_temp_slr.append(target.clone(true));
        /* copying map
        if ($(this).data("map")){
            print_temp_slr.append($("#gt-name-distribution-map-nmap").clone());
        }*/
        window.print();
        print_temp_slr.html("<img src='" + __wp_vars.logo_uri + "' alt='Czech American TV logo' class='logo'>");
    });

    /**
     * Toggle help/guide texts
     *
     * It requires the button to have 1 parameter:
     * - 'data-target': contains the ID of another input that contains part of the ID as a value to link it to specific text box.
     */
    $(".gt-help-btn").on("click", function () {
        let target = $("#" + $(this).data("target"));
        target.slideToggle();
    });

    /**
     * Append the structure used for printing.
     */
    let print = `\
        <div id='gt-print' class=' d-none d-print-block'>\
        <img src='${__wp_vars.logo_uri}' alt='Czech American TV' class='logo'>\
        </div>\
        <div class='d-none'>\
            <div id='${GT_SELECTOR.CHANGING_NAMES_PRINT_OUTPUT.replace("${type}", GT_CHANGING_NAME_TYPE.LN_TRANSCRIPTION).substring(1)}'></div>\
            <div id='${GT_SELECTOR.CHANGING_NAMES_PRINT_OUTPUT.replace("${type}", GT_CHANGING_NAME_TYPE.FEMALE_VARIANT).substring(1)}'></div>\
            <div id='${GT_SELECTOR.CHANGING_NAMES_PRINT_OUTPUT.replace("${type}", GT_CHANGING_NAME_TYPE.FN_TRANSLATION_EN_CZ).substring(1)}'></div>\
            <div id='${GT_SELECTOR.CHANGING_NAMES_PRINT_OUTPUT.replace("${type}", GT_CHANGING_NAME_TYPE.FN_TRANSLATION_CZ_EN).substring(1)}'></div>\
            <div id='${GT_SELECTOR.GERMAN_TERMINOLOGY_GERMAN_CITY_PRINT_OUTPUT.substring(1)}'></div>\
            <div id='${GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_PRINT_OUTPUT.substring(1)}'></div>\
            <div id='${GT_SELECTOR.NAME_DISTRIBUTION_MAP_PRINT_OUTPUT.substring(1)}'></div>\
            <div id='${GT_SELECTOR.BEHIND_THE_NAME_LN_EXPLANATION_PRINT_OUTPUT.substring(1)}'></div>\
            <div id='${GT_SELECTOR.BEHIND_THE_NAME_FN_DIMINUTIVE_PRINT_OUTPUT.substring(1)}'></div>\
            <div id='${GT_SELECTOR.NAME_TRANSLATION_PRINT_OUTPUT.substring(1)}'></div>\
            <div id='${GT_SELECTOR.CZ_EN_TRANSLATION_PRINT_OUTPUT.substring(1)}'></div>\
        </div>`;
    $("body").append(print);
});

function change(el, mouseOver) {
    if (mouseOver) {
        el.style.backgroundColor = '#0090ff';
    } else {
        el.style.backgroundColor = 'transparent';
    }
}

function copy(el) {
    var copyText = el.textContent;
    const copy = document.createElement('textarea');
    copy.value = copyText;
    document.body.appendChild(copy);
    copy.select();
    document.execCommand('copy');
    document.body.removeChild(copy);

    alert("Copied: " + copy.value);
}