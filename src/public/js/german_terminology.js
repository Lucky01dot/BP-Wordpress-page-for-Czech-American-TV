$(document).ready(function ($) {
    // Hide alphabet (make sure the alphabet is not visible initially)
    $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_ALPHABET_BOX).hide();
    $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_ALPHABET_BOX).removeClass("d-none");

    /**
     * ON CLICK
     * - Show/Hide alphabet
     */
    $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_SHOW_ALPHABET_BTN).on("click", function () {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_ALPHABET_BOX).slideUp();
            $(this).html("Show alphabet");
        } else {
            $(this).addClass("active");
            $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_ALPHABET_BOX).slideDown();
            $(this).html("Hide&nbsp alphabet");
        }
    });

    /**
     * ON CHANGE (select)
     * - Change font-case
     */
    $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_CASE_SELECT).change(function () {
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_ALPHABET_BOX).css("textTransform", $(this).val());
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_OUTPUT).css("textTransform", $(this).val());
    });

    /**
     * ON CHANGE (select)
     * - Change font family
     */
    $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_FONT_SELECT).change(function () {
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_ALPHABET_BOX).css("fontFamily", $(this).val());
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_OUTPUT).css("fontFamily", $(this).val());
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_PRINT_OUTPUT).css("fontFamily", $(this).val());
    });

    /**
     * ON KEYUP (input)
     * - Show the entered text in the result box with the desired font settings
     */
    $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_INPUT).keyup(function () {
        let text = $(this).val();

        if (text.length > 0) {
            $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_OUTPUT).html(text);
            $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_OUTPUT).css("color", "inherit");
            $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_PRINT_OUTPUT).html(text);
        } else {
            $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_OUTPUT).html("Text in selected font will appear here...");
            $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_OUTPUT).css("color", "lightgray");
            $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_PRINT_OUTPUT).html(text);
        }
    });

    /**
     * ON CLICK (button)
     * - Add german-specific character to the input text
     */
    $(".gt-typer-addbtn").on("click", function () {
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_INPUT).val($(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_INPUT).val() + ($(this).html()));
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_HANDWRITING_INPUT).keyup();
    });

    // Capitalize spec chars on shift
    $(document).keydown(function (event) {
        if (event.keyCode === 16) {
            $(".gt-typer-addbtn").each(function () {
                var character = $(this).html();
                if (character !== "ß") { //dont capitalize ß
                    $(this).html(character.toUpperCase());
                }
            });
        }
    })
        .keyup(function (event) {
            if (event.keyCode === 16) {
                $(".gt-typer-addbtn").each(function () {
                    var character = $(this).html();
                    $(this).html(character.toLowerCase());
                });
            }
        });

    /**
     * ON AUTOCOMPLETESELECT
     * - German city name to cz
     */
    $(GT_SELECTOR.GERMAN_TERMINOLOGY_GERMAN_CITY_INPUT).on("autocompleteselect", function (event, ui) {
        // Ignore if object has defined 'help' field
        if (ui.item.help)
            return false;

        $(GT_SELECTOR.GERMAN_TERMINOLOGY_GERMAN_CITY_PRINT_OUTPUT).html("German City Name: " + ui.item.value + "<br>");
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_GERMAN_CITY_CZ_OUTPUT).val(ui.item.name_cz);
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_GERMAN_CITY_PRINT_OUTPUT).append("Czech City Name: " + ui.item.name_cz + "<br>");
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_GERMAN_CITY_DISTRICT_OUTPUT).val(ui.item.district_name_en);
        $(GT_SELECTOR.GERMAN_TERMINOLOGY_GERMAN_CITY_PRINT_OUTPUT).append("District Name: " + ui.item.district_name_en + "<br>");
    });
});