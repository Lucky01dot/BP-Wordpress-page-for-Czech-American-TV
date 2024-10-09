<?php


/**
 * Class for last names counts table.
 */
class GT_LN_Count_Table extends GT_Table {

	/**
	 * @var string Name of the name id field.
	 */
	public string $name_id = "name_id";

	/**
	 * @var string Name of the MEP id field.
	 */
	public string $mep_id = "mep_id";

	/**
	 * @var string Name of the count field.
	 */
	public string $count = "count";

	/**
	 * Creates this table.
	 *
	 * @param wpdb $wpdb A wpdb database context.
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function create( wpdb $wpdb ): bool {
		$query = "
        CREATE TABLE IF NOT EXISTS `{$this->_tablename_}` (
			`{$this->name_id}` INT UNSIGNED NOT NULL,
			`{$this->mep_id}` INT UNSIGNED NOT NULL,
			`{$this->count}` INT UNSIGNED NOT NULL,
			CONSTRAINT `PK_{$this->_tablename_}` PRIMARY KEY (`{$this->name_id}`, `{$this->mep_id}`)
		)
		ENGINE=InnoDB
		DEFAULT CHARSET=utf8mb4
		COLLATE=utf8mb4_czech_ci;
		";

		return $wpdb->query( $query );
	}

	/**
	 * Drops this table.
	 *
	 * @param wpdb $wpdb A wpdb database context.
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function drop( wpdb $wpdb ): bool {
		$query = "
		DROP TABLE IF EXISTS `{$this->_tablename_}`
		";

		return $wpdb->query( $query );
	}
}