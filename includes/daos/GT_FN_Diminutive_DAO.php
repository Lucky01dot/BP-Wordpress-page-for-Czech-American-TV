<?php


/**
 * Data access object for first name diminutives (czech names).
 */
class GT_FN_Diminutive_DAO extends GT_DAO {

	/**
	 * Gets all first names by prefix for autocomplete.
	 *
	 * @param string $prefix Prefix of name.
	 *
	 * @return array|null Array of objects {`name`}. Null on error.
	 */
	public function get_names_by_prefix( string $prefix ): ?array {
		$table = $this->db->fn_diminutive_table;

		$query = $this->db->wpdb->prepare( "
			SELECT DISTINCT `{$table->name}` COLLATE utf8mb4_bin AS `name`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name}` LIKE %s COLLATE utf8mb4_general_ci
			ORDER BY CHAR_LENGTH(`{$table->name}`)
			LIMIT 10;
		", $prefix . "%" );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Gets all first names diminutives by name.
	 *
	 * @param string $name The first name.
	 *
	 * @return array|null Array of objects {`diminutive`}. Null on error.
	 */
	public function get_diminutives_by_name( string $name ): ?array {
		$table = $this->db->fn_diminutive_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->diminutive}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name}`=%s COLLATE utf8mb4_bin;
		", $name );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Inserts record to DB
	 *
	 * @param array $columns values (name, diminutive)
	 * $columns count == 2
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( array $columns ): bool {
		$table = $this->db->fn_diminutive_table->_tablename_;

		if ( count( $columns ) != 2 ) {
			return false;
		}

		$result = $this->db->wpdb->insert( $table, array(
			'name'       => $columns[0],
			'diminutive' => $columns[1]
		) );

		return $result !== false;
	}

	/**
	 * Get number of records from table
	 *
	 * @return array|null array with table name and records count
	 */
	public function get_records_count(): ?array {
		$table = $this->db->fn_diminutive_table;

		$query = $this->db->wpdb->prepare( "
			SELECT COUNT(*)
			FROM `{$table->_tablename_}`;
		" );

		return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
	}

	/**
	 * Get all records
	 * @return array|null
	 */
	public function get_all_records(): ?array {
		$result = array();
		$table  = $this->db->fn_diminutive_table;

		$query = "
			SELECT *
			FROM `{$table->_tablename_}`;
		";

		$all_records = $this->db->wpdb->get_results( $query );

		// reformat
		foreach ( $all_records as $record ) {
			array_push( $result, array( 'name' => $record->name, 'diminutive' => $record->diminutive ) );
		}

		return $result;
	}
}