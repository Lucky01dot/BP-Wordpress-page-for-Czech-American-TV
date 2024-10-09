<?php


/**
 * Data access object for cities and their german names.
 */
class GT_City_DAO extends GT_DAO {

	/**
	 * Gets all cities by german or czech name prefix for autocomplete.
	 *
	 * @param string $prefix Prefix of german name.
	 *
	 * @return array|null Array of objects {`name_cz`, `name_de`, `district_name_en`}. Null on error.
	 */
	public function get_city_by_prefix( string $prefix ): ?array {
		$city_table     = $this->db->city_table;
		$district_table = $this->db->district_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$city_table->_tablename_}`.`{$city_table->name_cz}` AS `name_cz`,
			       `{$city_table->_tablename_}`.`$city_table->name_de` AS `name_de`,
			       `{$district_table->_tablename_}`.`{$district_table->name_en}` AS `district_name_en`
			FROM `{$city_table->_tablename_}`
			LEFT JOIN `{$district_table->_tablename_}`
			ON `{$city_table->_tablename_}`.`{$city_table->district_id}`=`{$district_table->_tablename_}`.`{$district_table->id}`
			WHERE `{$city_table->_tablename_}`.`{$city_table->name_cz}` LIKE %s COLLATE utf8mb4_general_ci
			OR `{$city_table->_tablename_}`.`{$city_table->name_de}` LIKE %s COLLATE utf8mb4_general_ci
			ORDER BY CHAR_LENGTH(`{$city_table->_tablename_}`.`{$city_table->name_de}`)
			LIMIT 10;
		", $prefix . "%", $prefix . "%" );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Inserts record to DB
	 *
	 * @param array $columns values (id, name_cz, name_de, district_id, note)
	 * $columns count == 5
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( array $columns ): bool {
		$table = $this->db->city_table->_tablename_;

		if ( count( $columns ) != 5 ) {
			return false;
		}

		$result = $this->db->wpdb->insert( $table, array(
			'id'          => $columns[0],
			'name_cz'     => $columns[1],
			'name_de'     => $columns[2],
			'district_id' => $columns[3],
			'note'        => $columns[4]
		) );

		return $result !== false;
	}

	/**
	 * Get number of records from table
	 *
	 * @return array|null array with table name and records count
	 */
	public function get_records_count(): ?array {
		$table = $this->db->city_table;

		$query = $this->db->wpdb->prepare( "
			SELECT COUNT(*)
			FROM `{$table->_tablename_}`;
		" );

		return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
	}

}