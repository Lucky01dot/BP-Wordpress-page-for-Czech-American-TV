/**
 * Enum of all available request types for ajax calls (autocompletes) across the entire GT plugin.
 * @type {{string}}
 *
 * This enum must match with the JS enum defined in `includes/enums/GT_Autocomplete_Type.php` !!!
 */
const GT_AUTOCOMPLETE_TYPE = {
    GERMAN_TERMINOLOGY_GERMAN_CITY: "german-terminology-german-city",
    BEHIND_THE_NAME_FN_DIMINUTIVE: "behind-the-name-fn-diminutive",
    BEHIND_THE_NAME_LN_EXPLANATION: "behind-the-name-ln-explanation",
    CHANGING_NAMES_FEMALE_VARIANT: "changing-names-female-variant",
    CHANGING_NAMES_FN_TRANSLATION_EN_CZ: "changing-names-fn-translation-en-cz",
    CHANGING_NAMES_FN_TRANSLATION_CZ_EN: "changing-names-fn-translation-cz-en",
    NAME_DISTRIBUTION_MEP: "name-distribution-mep",
    NAME_DISTRIBUTION_LN: "name-distribution-ln",
};

/**
 * Enum of changing name types.
 * @type {{string}}
 *
 * This enum must match with the constants defined in `includes/enums/GT_Changing_Names_Type.php` !!!
 */
const GT_CHANGING_NAME_TYPE = {
    FN_TRANSLATION_EN_CZ: "fn-translation-en-cz",
    FN_TRANSLATION_CZ_EN: "fn-translation-cz-en",
    LN_TRANSCRIPTION: "ln-transcription",
    FEMALE_VARIANT: "female-variant",
};