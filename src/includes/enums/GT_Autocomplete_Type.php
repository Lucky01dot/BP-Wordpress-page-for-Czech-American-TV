<?php

/**
 * Class GT_Autocomplete_Type is the enum of all available request types for ajax autocompletes across the entire GT plugin.
 * This enum must match with the JS enum defined in `common/js/request_types.js` !!!
 */
abstract class GT_Autocomplete_Type {
	const GERMAN_TERMINOLOGY_CITY = "german-terminology-german-city";
	const BEHIND_THE_NAME_LN_EXPLANATION = "behind-the-name-ln-explanation";
	const BEHIND_THE_NAME_FN_DIMINUTIVE = "behind-the-name-fn-diminutive";
	const CHANGING_NAMES_FEMALE_VARIANT = "changing-names-female-variant";
	const CHANGING_NAMES_FN_TRANSLATION_EN_CZ = "changing-names-fn-translation-en-cz";
	const CHANGING_NAMES_FN_TRANSLATION_CZ_EN = "changing-names-fn-translation-cz-en";
	const NAME_DISTRIBUTION_MEP = "name-distribution-mep";
	const NAME_DISTRIBUTION_LN = "name-distribution-ln";
    const TRANSLATION_EN_CZ = "cz-en-translation";
    const TRANSLATION_LA_EN = "la-en-translation";
}