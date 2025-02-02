<?php


/**
 * Data access object for first names.
 */
class GT_FN_DAO extends GT_DAO implements GT_I_Name_DAO {

	/**
	 * Gets number of all last names that starts with the given string.
	 *
	 * @param string $prefix Name prefix.
	 *
	 * @return int Number of names.
	 */
	public function get_number_of_names_starting_with( string $prefix ): int {
		$table = $this->db->fn_table;

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
	 * @return object|null Object {`id`, `name`}.
	 */
	public function get_name_by_name( string $name ): ?object {
		$table = $this->db->fn_table;

		// binary collation is for exact match sensitive to case and accents
		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->name}`, `{$table->id}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name}`=%s COLLATE utf8mb4_bin;
		", $name );

		return $this->db->wpdb->get_row( $query );
	}

	/**
	 * Inserts record to DB
	 *
	 * @param array $columns values (id, name)
	 * $columns count == 2
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( array $columns ): bool {
		$table = $this->db->fn_table->_tablename_;

		if ( count( $columns ) != 2 ) {
			return false;
		}

		$result = $this->db->wpdb->insert( $table, array(
			'id'   => $columns[0],
			'name' => $columns[1]
		) );

		return $result !== false;
	}

	/**
	 * Get number of records from table
	 *
	 * @return array|null array with table name and records count
	 */
	public function get_records_count(): ?array {
		$table = $this->db->fn_table;

		$query = $this->db->wpdb->prepare( "
			SELECT COUNT(*)
			FROM `{$table->_tablename_}`;
		" );

		return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
	}
}