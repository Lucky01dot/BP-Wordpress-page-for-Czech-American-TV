<?php


/**
 * Class for municipalities with extended powers (MEP) table.
 */
class GT_MEP_Table extends GT_Table {

	/**
	 * @var string Name of the id field.
	 */
	public string $id = "id";

	/**
	 * @var string Name of the czech name field.
	 */
	public string $name_cz = "name_cz";

	/**
	 * @var string Name of the german name field.
	 */
	public string $name_de = "name_de";

	/**
	 * @var string Name of the region id field.
	 */
	public string $region_id = "region_id";

	/**
	 * @var string Name of the latitude field.
	 */
	public string $lat = "lat";

	/**
	 * @var string Name of the longitude field.
	 */
	public string $lng = "lng";

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
			`{$this->id}` INT UNSIGNED NOT NULL,
			`{$this->name_cz}` varchar(64) NOT NULL,
			`{$this->name_de}` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
			`{$this->region_id}` INT UNSIGNED NOT NULL,
			`{$this->lat}` FLOAT NOT NULL,
			`{$this->lng}` FLOAT NOT NULL,
			CONSTRAINT `PK_{$this->_tablename_}` PRIMARY KEY (`{$this->id}`),
			INDEX `IX_{$this->_tablename_}_{$this->name_cz}` USING BTREE (`{$this->name_cz}`),
			INDEX `IX_{$this->_tablename_}_{$this->name_de}` USING BTREE (`{$this->name_de}`)
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