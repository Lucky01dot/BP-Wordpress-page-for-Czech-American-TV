<?php


/**
 * Data access object for regions.
 */
class GT_District_DAO extends GT_DAO {

	/**
	 * Inserts record to DB
	 *
	 * @param array $columns values (id, name_cz, name_en, region_id)
	 * $columns count == 4
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( array $columns ): bool {
		$table = $this->db->district_table->_tablename_;

		if ( count( $columns ) != 4 ) {
			return false;
		}

		$result = $this->db->wpdb->insert( $table, array(
			'id'        => $columns[0],
			'name_cz'   => $columns[1],
			'name_en'   => $columns[2],
			'region_id' => $columns[3]
		) );

		return $result !== false;
	}

	/**
	 * Get number of records from table
	 *
	 * @return array|null array with table name and records count
	 */
	public function get_records_count(): ?array {
		$table = $this->db->district_table;

		$query = $this->db->wpdb->prepare( "
			SELECT COUNT(*)
			FROM `{$table->_tablename_}`;
		" );

		return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
	}
}