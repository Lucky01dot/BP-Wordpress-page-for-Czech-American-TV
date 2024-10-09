<?php


/**
 * Class for first name translations table.
 */
class GT_FN_Translation_Table extends GT_Table {

	/**
	 * @var string Name of the id field.
	 */
	public string $id = "id";

	/**
	 * @var string Name of the name in english field.
	 */
	public string $name_en = "name_en";

	/**
	 * @var string Name of the name in czech field.
	 */
	public string $name_cz = "name_cz";

	/**
	 * @var string Name of the name in czech field.
	 */
	public string $priority = "priority";

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
			`{$this->id}` INT UNSIGNED AUTO_INCREMENT NOT NULL,
            `{$this->name_en}` varchar(64) COLLATE utf8mb4_general_ci NOT NULL,
			`{$this->name_cz}` varchar(64) NOT NULL,
			`{$this->priority}` INT NOT NULL,
			CONSTRAINT `PK_{$this->_tablename_}` PRIMARY KEY (`{$this->id}`),
			INDEX `IX_{$this->_tablename_}_{$this->name_en}` USING BTREE (`{$this->name_en}`),
			INDEX `IX_{$this->_tablename_}_{$this->name_cz}` USING BTREE (`{$this->name_cz}`)
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