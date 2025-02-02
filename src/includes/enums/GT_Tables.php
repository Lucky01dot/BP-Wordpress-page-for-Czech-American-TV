<?php

/**
 * Names of tables in the database.
 * <ul>
 *      <li> last_names - last names table
 *      <li> ln_counts - last name counts table
 *      <li> ln_translations - last name translation table
 *      <li> first_names - first names table
 *      <li> fn_counts - first name counts table
 *      <li> fn_translations - first name translation table
 *      <li> regions - regions table
 *      <li> cities - cities table
 *      <li> cities_historical - german city names table
 *      <li> diminutives - diminutive first name forms table
 *      <li> ln_explanations - explanations of last names table
 * </ul>
 */
abstract class GT_Tables {
    const EN_CZ_TRANSLATION = 'cz_en_translation';
	const FIRST_NAME_TRANSLATION =  "fn_translation";
	const FIRST_NAME_DIMINUTIVE =  "fn_diminutive";
	const LAST_NAME_EXPLANATION =  "ln_explanation";
	const REGION =  "region";
	const MEP =  "mep";
	const DISTRICT =  "district";
	const CITY =  "city";
	const LAST_NAME =  "last_name";
	const FIRST_NAME =  "first_name";
	const LAST_NAME_COUNT =  "ln_count";

	/**
	 * Get array of this enum to be able to list enumerate
	 * @return array Array of this enum
	 */
	public static function get(): array {
		$reflectionClass = new ReflectionClass(__CLASS__);
		return $reflectionClass->getConstants();
	}
}
