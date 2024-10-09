<?php


/**
 * Data access object for regions.
 */
class GT_Region_DAO extends GT_DAO {

	/**
	 * Gets the region by the id field.
	 *
	 * @param int $id The region id.
	 *
	 * @return object|null Object {`id`, `name_cz`, `name_en`, `map_code`}.
	 */
	public function get_region_by_id( int $id ): ?object {
		$table = $this->db->region_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->id}`,
			       `{$table->name_cz}`,
			       `{$table->name_en}`,
			       `{$table->map_code}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->id}`=%d;
		", $id );

		return $this->db->wpdb->get_row( $query );
	}

	/**
	 * Gets all regions.
	 *
	 * @return array|null Array of objects `id` => {`id`, `name_cz`, `name_en`, `map_code`}.
	 */
	public function get_all_regions(): ?array {
		$table = $this->db->region_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->id}`,
			       `{$table->name_cz}`,
			       `{$table->name_en}`,
			       `{$table->map_code}`
			FROM `{$table->_tablename_}`;
		" );

		return $this->db->wpdb->get_results( $query, OBJECT_K );
	}

	/**
	 * Inserts record to DB
	 *
	 * @param array $columns values (id, name_cz, name_en, map_code)
	 * $columns count == 4
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( array $columns ): bool {
		$table = $this->db->region_table->_tablename_;

		if ( count( $columns ) != 4 ) {
			return false;
		}

		$result = $this->db->wpdb->insert( $table, array(
			'id'       => $columns[0],
			'name_cz'  => $columns[1],
			'name_en'  => $columns[2],
			'map_code' => $columns[3]
		) );

		return $result !== false;
	}

	/**
	 * Get number of records from table
	 *
	 * @return array|null array with table name and records count
	 */
	public function get_records_count(): ?array {
		$table = $this->db->region_table;

		$query = $this->db->wpdb->prepare( "
			SELECT COUNT(*)
			FROM `{$table->_tablename_}`;
		" );

		return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
	}
}