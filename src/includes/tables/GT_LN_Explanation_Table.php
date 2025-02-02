<?php


/**
 * Class for last name explanations table.
 */
class GT_LN_Explanation_Table extends GT_Table {

	/**
	 * @var string Name of the id field.
	 */
	public string $id = "id";

	/**
	 * @var string Name of the name field.
	 */
	public string $name = "name";

	/**
	 * @var string Name of the explanation field.
	 */
	public string $explanation = "explanation";

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
			`{$this->name}` varchar(64) NOT NULL,
			`{$this->explanation}` varchar(256) NOT NULL,
			CONSTRAINT `PK_{$this->name}` PRIMARY KEY (`{$this->id}`),
			INDEX `IX_{$this->_tablename_}_{$this->name}` USING BTREE (`{$this->name}`)
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