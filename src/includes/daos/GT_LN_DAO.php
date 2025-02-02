<?php


/**
 * Data access object for last names.
 */
class GT_LN_DAO extends GT_DAO implements GT_I_Name_DAO {

	/**
	 * Gets number of all last names that starts with the given string.
	 *
	 * @param string $prefix Name prefix.
	 *
	 * @return int Number of names.
	 */
	public function get_number_of_names_starting_with( string $prefix ): int {
		$table = $this->db->ln_table;

		$query = $this->db->wpdb->prepare( "
			SELECT COUNT(*) FROM `{$table->_tablename_}`
			WHERE `{$table->name}` LIKE %s;
		", $prefix . '%' );

		return intval( $this->db->wpdb->get_var( $query ) );
	}

	/**
	 * Gets the name by the name field.
	 *
	 * @param string $name Name.
	 *
	 * @return object|null Object {`id`, `name`, `count`}.
	 */
	public function get_name_by_name( string $name ): ?object {
		$table = $this->db->ln_table;

		// binary collation is for exact match sensitive to case and accents
		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->name}`, `{$table->id}`, `{$table->count}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name}`=%s COLLATE utf8mb4_bin;
		", $name );

		return $this->db->wpdb->get_row( $query );
	}

	/**
	 * Gets the name by the id field.
	 *
	 * @param int $id The name id.
	 *
	 * @return object|null Object {`id`, `name`}.
	 */
	public function get_name_by_id( int $id ): ?object {
		$table = $this->db->ln_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->name}`, `{$table->id}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->id}`=%s;
		", $id );

		return $this->db->wpdb->get_row( $query );
	}

	/**
	 * Gets all names by prefix for autocomplete.
	 *
	 * @param string $prefix Prefix of name.
	 *
	 * @return array|null Array of objects {`id`, `name`}. Null on error.
	 */
	public function get_names_by_prefix( string $prefix ): ?array {
		$table = $this->db->ln_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->id}`, `{$table->name}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name}` LIKE %s COLLATE utf8mb4_general_ci
			ORDER BY CHAR_LENGTH(`{$table->name}`)
			LIMIT 10;
		", $prefix . "%" );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Gets all male names by prefix for autocomplete.
	 *
	 * @param string $prefix Prefix of name.
	 *
	 * @return array|null Array of objects {`id`, `name`}. Null on error.
	 */
	public function get_male_names_by_prefix( string $prefix ): ?array {
		$table = $this->db->ln_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->id}`, `{$table->name}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name}` LIKE %s COLLATE utf8mb4_general_ci
			  AND `{$table->name}` NOT LIKE '%Á' COLLATE utf8mb4_bin
			ORDER BY CHAR_LENGTH(`{$table->name}`)
			LIMIT 10;
		", $prefix . "%" );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Get number of records from table
	 *
	 * @return array|null array with table name and records count
	 */
	public function get_records_count(): ?array {
		$table = $this->db->ln_table;

		$query = $this->db->wpdb->prepare( "
			SELECT COUNT(*)
			FROM `{$table->_tablename_}`;
		" );

		return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
	}

	/**
	 * Inserts record to DB
	 *
	 * @param array $columns values (id,name,count)
	 * $columns count == 3
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( array $columns ): bool {

		$table = $this->db->ln_table->_tablename_;

		if ( count( $columns ) != 3 ) {
			return false;
		}

		$result = $this->db->wpdb->insert( $table, array(
			'id'    => $columns[0],
			'name'  => $columns[1],
			'count' => $columns[2]
		) );


		return $result !== false;
	}
}