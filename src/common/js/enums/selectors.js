/**
 * Enum of all key selectors used across the entire GT plugin.
 * All plugin dependent IDs (optionally very important class selectors) should be defined here to do not loose track about them.
 * You can use this to find or use these selectors simpler in HTML templates.
 * @type {{string}}
 */
const GT_SELECTOR = {
    /**
     * Autocomplete defined mainly on input tags
     */
    AUTOCOMPLETE_INPUTS: ".gt-autocomplete",

    /**
     * Info BTN that moves you to the map page and based on the parameters, data will be shown
     */
    MAP_INFO_BTNS: ".gt-map-info-btn",

    /**
     * Help text buttons to show/hide {@see HELP_TEXT_BOXES}
     */
    HELP_TEXT_BTNS: ".gt-help-btn",

    /**
     * Help text boxes that should be be visible initially on pages, but can be toggle by help buttons {@see HELP_TEXT_BTNS}.
     */
    HELP_TEXT_BOXES: ".gt-help-text",


    //#region DIMINUTIVE
    /**
     * Diminutive input selector
     */
    BEHIND_THE_NAME_FN_DIMINUTIVE_INPUT: "#gt-behind-the-name-fn-diminutive-input",
    /**
     * Diminutive meaning output selector - where the html output will be printed
     */
    BEHIND_THE_NAME_FN_DIMINUTIVE_OUTPUT: "#gt-behind-the-name-fn-diminutive-output",
    /**
     * Diminutive output selector - where the html output will be printed
     * - but this is a special output not visible to users, but it is a structure for printer
     */
    BEHIND_THE_NAME_FN_DIMINUTIVE_PRINT_OUTPUT: "#gt-behind-the-name-fn-diminutive-print",
    //#endregion


    //#region NAME EXPLANATION
    /**
     * Name meaning input selector
     */
    BEHIND_THE_NAME_LN_EXPLANATION_INPUT: "#gt-behind-the-name-ln-explanation-input",
    /**
     * Name Name meaning output selector - where the html output will be printed
     */
    BEHIND_THE_NAME_LN_EXPLANATION_OUTPUT: "#gt-behind-the-name-ln-explanation-output",
    /**
     * Name meaning output selector - where the html output will be printed
     * - but this is a special output not visible to users, but it is a structure for printer
     */
    BEHIND_THE_NAME_LN_EXPLANATION_PRINT_OUTPUT: "#gt-behind-the-name-ln-explanation-print",
    //#endregion


    //#region TRANSLATION
    // TODO: remove
    /**
     * Name translation input selector
     */
    NAME_TRANSLATION_INPUT: "#gt-translation-fname-input",
    /**
     * Name translation output selector - where the html output will be printed
     */
    NAME_TRANSLATION_OUTPUT: "#gt-translation-fname",
    /**
     * Name translation output selector - where the html output will be printed
     * - but this is a special output not visible to users, but it is a structure for printer
     */
    NAME_TRANSLATION_PRINT_OUTPUT: "#gt-print-translation-fname",
    //#endregion


    //#region TUTORIAL
    /**
     * Video tutorial YT iframe
     */
    GT_TUTORIAL_VIDEO: "#gt-tutorial",
    /**
     * Seek buttons for the video tutorial
     */
    GT_TUTORIAL_SEEK_BTNS: ".gt-seek-btn",
    //#endregion




    //region ------- CHANGING NAMES -------
    /**
     * Input for the transcription - interpolation needs to be replaced before use
     */
    CHANGING_NAMES_INPUT: "#gt-changing-names-${type}-input", // replace the interpolation afterwards
    /**
     * Output for the transcription - interpolation needs to be replaced before use
     * - where the html output will be printed
     */
    CHANGING_NAMES_OUTPUT: "#gt-changing-names-${type}-output",
    /**
     * Print output for the transcription - interpolation needs to be replaced before use
     * - where the html output will be printed
     * - but this is a special output not visible to users, but it is a structure for printer
     */
    CHANGING_NAMES_PRINT_OUTPUT: "#gt-changing-names-${type}-print",
    /**
     * It is the same as {@see CHANGING_NAMES_OUTPUT}, but as a class selector - not specific.
     */
    CHANGING_NAMES_RESULTS: ".gt-changing-names-result",
    /**
     * Selector for all autocomplete inputs on Changing names.
     * It was added to provide autocomplete functionality and not to mess with already existing selectors
     * and submit system based on type.
     */
    CHANGING_NAMES_AUTOCOMPLETE_INPUTS: ".gt-changing-names-autocomplete",
    //endregion


    //#region GERMAN TERMINOLOGY
    /**
     * Box containing alphabet
     * - this selector is used to be able to select the box and show/hide it.
     */
    GERMAN_TERMINOLOGY_HANDWRITING_ALPHABET_BOX: "#gt-german-terminology-handwriting-alphabetbox",
    /**
     * The button used to show/hide the {@see GERMAN_TERMINOLOGY_HANDWRITING_ALPHABET_BOX} box.
     */
    GERMAN_TERMINOLOGY_HANDWRITING_SHOW_ALPHABET_BTN: "#gt-german-terminology-handwriting-show-alphabet-btn",
    /**
     * Selector to the select containing font case options
     */
    GERMAN_TERMINOLOGY_HANDWRITING_CASE_SELECT: "#gt-german-terminology-handwriting-caseselect",
    /**
     * Selector to the select containing font variants
     */
    GERMAN_TERMINOLOGY_HANDWRITING_FONT_SELECT: "#gt-german-terminology-handwriting-fontselect",
    /**
     * The user input for the handwriting font-visual transformation
     */
    GERMAN_TERMINOLOGY_HANDWRITING_INPUT: "#gt-german-terminology-handwriting-input",
    /**
     * Print output box for the input {@see GERMAN_TERMINOLOGY_HANDWRITING_INPUT}
     * - where the html output will be printed
     */
    GERMAN_TERMINOLOGY_HANDWRITING_OUTPUT: "#gt-german-terminology-handwriting-output",
    /**
     * Print output box for the input {@see GERMAN_TERMINOLOGY_HANDWRITING_INPUT}
     * - where the html output will be printed
     * - but this is a special output not visible to users, but it is a structure for printer
     */
    GERMAN_TERMINOLOGY_HANDWRITING_PRINT_OUTPUT: "#gt-german-terminology-handwriting-print",
    /**
     * City german name to CZ input
     */
    GERMAN_TERMINOLOGY_GERMAN_CITY_INPUT: "#gt-german-terminology-german-city-input",
    /**
     * City german name to CZ output
     * - where the html output will be printed
     * - but this is a special output not visible to users, but it is a structure for printer
     */
    GERMAN_TERMINOLOGY_GERMAN_CITY_PRINT_OUTPUT: "#gt-german-terminology-german-city-print",
    /**
     * City german name to CZ output
     * - where the html output will be printed
     */
    GERMAN_TERMINOLOGY_GERMAN_CITY_CZ_OUTPUT: "#gt-german-terminology-german-city-cz-output",
    /**
     * City german name to CZ output of parent (region)
     * - where the html output will be printed
     */
    GERMAN_TERMINOLOGY_GERMAN_CITY_DISTRICT_OUTPUT: "#gt-german-terminology-german-city-district-output",
    //#endregion


    //#region MAP
    /**
     * Checkbox for region map
     */
    NAME_DISTRIBUTION_MAP_REGION_CHECKBOX: "#gt-name-distribution-map-region-checkbox",
    /**
     * Checkbox for city map
     */
    NAME_DISTRIBUTION_MAP_MEP_CHECKBOX: "#gt-name-distribution-map-mep-checkbox",
    /**
     * Google map div (regions)
     */
    NAME_DISTRIBUTION_MAP_GMAP_DIV: "#gt-name-distribution-map-gmap",
    /**
     * non-google map div (cities)
     */
    NAME_DISTRIBUTION_NMAP_DIV: "#gt-name-distribution-map-nmap",
    //#endregion


    //#region MAP DETAILS
    /**
     * Wrapper div to print HTML formatted details into.
     */
    NAME_DISTRIBUTION_MAP_DETAILS_DIV: "#gt-name-distribution-map-display",
    /**
     * Print output box
     * - where the html output will be printed
     * - but this is a special output not visible to users, but it is a structure for printer
     */
    NAME_DISTRIBUTION_MAP_PRINT_OUTPUT: "#gt-name-distribution-map-print",
    /**
     * Box for printing status message (e.g. 'Loading...')
     */
    NAME_DISTRIBUTION_MAP_STATUS_MSG_BOX: "#gt-name-distribution-map-status",
    /**
     * All the inputs for map details selection
     */
    NAME_DISTRIBUTION_MAP_INPUTS: ".gt-name-distribution-map-input",
    /**
     * Specific input for map details selection - interpolation needs to be replaced before use
     * - type: {@see GT_NAME_DISTRIBUTION_MAP_PARAMETER}
     */
    NAME_DISTRIBUTION_MAP_INPUT: "#gt-name-distribution-map-${type}-input",
    /**
     * Input (hidden) that contains name required for map redirection for map details on map page load
     */
    NAME_DISTRIBUTION_MAP_REDIRECT_LAST_NAME: "#gt-name-distribution-map-redirect-last-name",

    //#endregion

    //#region ADMIN PANEL

    /**
     * Admin panel - Last name form selector
     */
    LN_IMPORT_FORM: ".gt-ln-import",
    /**
     * Admin panel - Last name file input selector
     */
    LN_IMPORT_FILE_NAME: "#gt-ln-import-file",
    /**
     * Admin panel - Last name status field selector
     */
    LN_IMPORT_INFO: "#gt-ln-import-info",
    /**
     * Admin panel - Last name table selector
     */
    LN_IMPORT_TABLE: "gt-ln-import-table",

    /**
     * Admin panel - Last name count form selector
     */
    LN_COUNT_IMPORT_FORM: ".gt-ln-count-import",
    /**
     * Admin panel - Last name count file input selector
     */
    LN_COUNT_IMPORT_FILE_NAME: "#gt-ln-count-import-file",
    /**
     * Admin panel - Last name count status field selector
     */
    LN_COUNT_IMPORT_INFO: "#gt-ln-count-import-info",
    /**
     * Admin panel - Last name count table selector
     */
    LN_COUNT_IMPORT_TABLE: "gt-ln-count-import-table",

    /**
     * Admin panel - Last name explanation form selector
     */
    LN_EXPLANATION_IMPORT_FORM: ".gt-ln-explanation-import",
    /**
     * Admin panel - Last name explanation form for importing one record selector
     */
    LN_EXPLANATION_IMPORT_ONE_FORM: ".gt-ln-explanation-import-one",
    /**
     * Admin panel - Last name explanation - name selector
     */
    LN_EXPLANATION_IMPORT_ONE_NAME: "#gt-ln-explanation-one-name",
    /**
     * Admin panel - Last name explanation - explanation value selector
     */
    LN_EXPLANATION_IMPORT_ONE_EXPLANATION: "#gt-ln-explanation-one-explanation",
    /**
     * Admin panel - Last name explanation file input selector
     */
    LN_EXPLANATION_IMPORT_FILE_NAME: "#gt-ln-explanation-import-file",
    /**
     * Admin panel - Last name explanation status field selector
     */
    LN_EXPLANATION_IMPORT_INFO: "#gt-ln-explanation-import-info",
    /**
     * Admin panel - Last name explanation table selector
     */
    LN_EXPLANATION_IMPORT_TABLE: "gt-ln-explanation-import-table",

    /**
     * Admin panel - First name form selector
     */
    FN_IMPORT_FORM: ".gt-fn-import",
    /**
     * Admin panel - First name file input selector
     */
    FN_IMPORT_FILE_NAME: "#gt-fn-import-file",
    /**
     * Admin panel - First name status field selector
     */
    FN_IMPORT_INFO: "#gt-fn-import-info",
    /**
     * Admin panel - First name table selector
     */
    FN_IMPORT_TABLE: "gt-fn-import-table",

    /**
     * Admin panel - First name diminutives form selector
     */
    FN_DIMINUTIVES_IMPORT_FORM: ".gt-fn-diminutives-import",
    /**
     * Admin panel - First name diminutives form for importing one record selector
     */
    FN_DIMINUTIVES_IMPORT_ONE_FORM: ".gt-fn_diminutive-import-one",
    /**
     * Admin panel - First name diminutives name value selector
     */
    FN_DIMINUTIVES_IMPORT_ONE_NAME: "#gt-fn_diminutive-one-name",
    /**
     * Admin panel - First name diminutives diminutive value selector
     */
    FN_DIMINUTIVES_IMPORT_ONE_DIMINUTIVE: "#gt-fn_diminutive-one-diminutive",
    /**
     * Admin panel - First name diminutives file input selector
     */
    FN_DIMINUTIVES_IMPORT_FILE_NAME: "#gt-fn-diminutives-import-file",
    /**
     * Admin panel - First name diminutives status field selector
     */
    FN_DIMINUTIVES_IMPORT_INFO: "#gt-fn-diminutives-import-info",
    /**
     * Admin panel - First name diminutives table selector
     */
    FN_DIMINUTIVES_IMPORT_TABLE: "gt-fn-diminutives-import-table",

    /**
     * Admin panel - First name translation form selector
     */
    FN_TRANSLATION_IMPORT_FORM: ".gt-fn-translations-import",
    /**
     * Admin panel - First name translation form for importing one record selector
     */
    FN_TRANSLATION_IMPORT_ONE_FORM: ".gt-fn_translation-import-one",
    /**
     * Admin panel - First name translation - czech name value selector
     */
    FN_TRANSLATION_IMPORT_ONE_NAME: "#gt-fn_translation-one-name",
    /**
     * Admin panel - First name translation - english name value selector
     */
    FN_TRANSLATION_IMPORT_ONE_NAME_EN: "#gt-fn_translation-one-name-en",
    /**
     * Admin panel - First name translation - english name value selector
     */
    FN_TRANSLATION_IMPORT_ONE_PRIORITY: "#gt-fn_translation-one-priority",
    /**
     * Admin panel - First name translation file input selector
     */
    FN_TRANSLATION_IMPORT_FILE_NAME: "#gt-fn-translations-import-file",
    /**
     * Admin panel - First name translation status field selector
     */
    FN_TRANSLATION_IMPORT_INFO: "#gt-fn-translations-import-info",
    /**
     * Admin panel - First name translation table selector
     */
    FN_TRANSLATION_IMPORT_TABLE: "gt-fn-translations-import-table",

    /**
     * Admin panel - City form selector
     */
    CITY_IMPORT_FORM: ".gt-city-import",
    /**
     * Admin panel - City file input selector
     */
    CITY_IMPORT_FILE_NAME: "#gt-city-import-file",
    /**
     * Admin panel - City status field selector
     */
    CITY_IMPORT_INFO: "#gt-city-import-info",
    /**
     * Admin panel - City table selector
     */
    CITY_IMPORT_TABLE: "gt-city-import-table",

    /**
     * Admin panel - Region form selector
     */
    REGION_IMPORT_FORM: ".gt-region-import",
    /**
     * Admin panel - Region file input selector
     */
    REGION_IMPORT_FILE_NAME: "#gt-region-import-file",
    /**
     * Admin panel - Region status field selector
     */
    REGION_IMPORT_INFO: "#gt-region-import-info",
    /**
     * Admin panel - Region table selector
     */
    REGION_IMPORT_TABLE: "gt-region-import-table",

    /**
     * Admin panel - District form selector
     */
    DISTRICT_IMPORT_FORM: ".gt-district-import",
    /**
     * Admin panel - District file input selector
     */
    DISTRICT_IMPORT_FILE_NAME: "#gt-district-import-file",
    /**
     * Admin panel - District status field selector
     */
    DISTRICT_IMPORT_INFO: "#gt-district-import-info",
    /**
     * Admin panel - District table selector
     */
    DISTRICT_IMPORT_TABLE: "gt-district-import-table",

    /**
     * Admin panel - MEP form selector
     */
    MEP_IMPORT_FORM: ".gt-mep-import",
    /**
     * Admin panel - MEP file input selector
     */
    MEP_IMPORT_FILE_NAME: "#gt-mep-import-file",
    /**
     * Admin panel - MEP status field selector
     */
    MEP_IMPORT_INFO: "#gt-mep-import-info",
    /**
     * Admin panel - MEP table selector
     */
    MEP_IMPORT_TABLE: "gt-mep-import-table",



    /**
     * CZ_EN translation form selector
     */
    CZ_EN_TRANSLATION_IMPORT_FORM: ".gt-cz-en-translation-table-import",


    /**
     * Admin panel - First name translation form for importing one record selector
     */
    CZ_EN_TRANSLATION_IMPORT_ONE_FORM: ".gt-cz-en-translation-import-one",
    /**
     * Admin panel - First name translation - czech name value selector
     */
    CZ_EN_TRANSLATION_IMPORT_ONE_WORD: "#gt-cz-en-translation-one-word",
    /**
     * Admin panel - First name translation - english name value selector
     */
    CZ_EN_TRANSLATION_IMPORT_ONE_WORD_EN: "#gt-cz-en-translation-one-word-en",

    /**
     * Admin panel - First name translation file input selector
     */
    CZ_EN_TRANSLATION_IMPORT_FILE_NAME: "#gt-cz-en-translations-import-file",
    /**
     * Admin panel - First name translation status field selector
     */
    CZ_EN_TRANSLATION_IMPORT_INFO: "#gt-cz-en-translations-import-info",
    /**
     * Admin panel - First name translation table selector
     */
    CZ_EN_TRANSLATION_IMPORT_TABLE: "gt-cz-en-translations-import-table",

    CZ_EN_IMPORT_FORM: ".gt-cz-en-import",
    //#endregion



    //region ------- CZ_EN translation -------
    /**
     * Input for the transcription - interpolation needs to be replaced before use
     */
    CZ_EN_TRANSLATION_INPUT: "#gt-cz-en-translation-input", // replace the interpolation afterwards

    CZ_EN_TRANSLATION_INPUTS: ".gt-cz-en-translation-input",
    /**
     * Output for the transcription - interpolation needs to be replaced before use
     * - where the html output will be printed
     */
    CZ_EN_TRANSLATION_OUTPUT: "#cz-en-translation-output",
    /**
     * Print output for the transcription - interpolation needs to be replaced before use
     * - where the html output will be printed
     * - but this is a special output not visible to users, but it is a structure for printer
     */
    CZ_EN_TRANSLATION_PRINT_OUTPUT: ".gt-print-btn",
    /**
     * It is the same as {@see CHANGING_NAMES_OUTPUT}, but as a class selector - not specific.
     */
    CZ_EN_TRANSLATION_RESULTS: ".gt-cz-en-translation-result",
    /**
     * Selector for all autocomplete inputs on Changing names.
     * It was added to provide autocomplete functionality and not to mess with already existing selectors
     * and submit system based on type.
     */
    CZ_EN_TRANSLATION_AUTOCOMPLETE_INPUTS: ".cz-en-translation-autocomplete",
    //endregion

};
