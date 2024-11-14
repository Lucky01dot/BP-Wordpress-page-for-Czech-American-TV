<?php


/**
 * Interface for first and last names DAOs.
 */
interface GT_I_Name_DAO {

	/**
	 * Gets number of all names that starts with the given string.
	 *
	 * @param string $prefix Name prefix.
	 *
	 * @return int Number of names.
	 */
	public function get_number_of_names_starting_with( string $prefix ): int;

	/**
	 * Gets the name by the name field.
	 *
	 * @param string $name Name.
	 *
	 * @return object|null Object {`id`, `name`}.
	 */
	public function get_name_by_name( string $name ): ?object;
}