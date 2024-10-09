<?php


/**
 * Data access object for municipalities with extended powers (MEP).
 */
class GT_MEP_DAO extends GT_DAO {

	/**
	 * Gets all MEPs by name prefix for autocomplete.
	 *
	 * @param string $prefix Prefix of name.
	 *
	 * @return array|null Array of objects {`id`, `name_cz`, `name_de`}. Null on error.
	 */
	public function get_meps_by_name_prefix( string $prefix ): ?array {
		$table = $this->db->mep_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->id}`, `{$table->name_cz}`, `{$table->name_de}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name_cz}` LIKE %s COLLATE utf8mb4_general_ci
			OR `{$table->name_de}` LIKE %s
			ORDER BY CHAR_LENGTH(`{$table->name_cz}`), CHAR_LENGTH(`{$table->name_de}`)
			LIMIT 10;
		", $prefix . "%", $prefix . "%" );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Gets the MEP by the id field.
	 *
	 * @param int $id The MEP id.
	 *
	 * @return object|null Object {`id`, `name_cz`, `name_de`, `lat`, `lng`, `region_id`}.
	 */
	public function get_mep_by_id( int $id ): ?object {
		$table = $this->db->mep_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->id}`,
			       `{$table->name_cz}`,
			       `{$table->name_de}`,
			       `{$table->lat}`,
			       `{$table->lng}`,
			       `{$table->region_id}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->id}`=%d;
		", $id );

		return $this->db->wpdb->get_row( $query );
	}

	/**
	 * Gets all MEPs.
	 *
	 * @return array|null Array of objects `id` => {`id`, `name_cz`, `name_de`, `lat`, `lng`, `region_id`}.
	 */
	public function get_all_meps(): ?array {
		$table = $this->db->mep_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->id}`,
			       `{$table->name_cz}`,
			       `{$table->name_de}`,
			       `{$table->lat}`,
			       `{$table->lng}`,
			       `{$table->region_id}`
			FROM `{$table->_tablename_}`;
		" );

		return $this->db->wpdb->get_results( $query, OBJECT_K );
	}

	/**
	 * Gets MEPs by region id.
	 *
	 * @param int $region_id
	 *
	 * @return array|null Array of objects `id` => {`id`, `name_cz`, `name_de`, `lat`, `lng`, `region_id`}.
	 */
	public function get_meps_by_region( int $region_id ): ?array {
		$table = $this->db->mep_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->id}`,
			       `{$table->name_cz}`,
			       `{$table->name_de}`,
			       `{$table->lat}`,
			       `{$table->lng}`,
			       `{$table->region_id}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->region_id}`=%d ;
		", $region_id );

		return $this->db->wpdb->get_results( $query, OBJECT_K );
	}

	/**
	 * Gets MEPs by region id and ordered by last name frequency
	 *
	 * @param int $region_id
	 *
	 * @return array|null Array of objects `id` => {`id`, `name_cz`, `name_de`, `lat`, `lng`, `region_id`}.
	 */
	public function get_meps_by_region_order_by_name_frequency( int $region_id ): ?array {
		$gt_mep      = $this->db->mep_table;
		$gt_ln_count = $this->db->ln_count_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$gt_mep->name_cz}`,
			       `{$gt_mep->name_de}`,
			       `{$gt_mep->id}`,
			       `{$gt_mep->lat}`,
			       `{$gt_mep->lng}`,
			       `{$gt_mep->region_id}`,
			       SUM(`{$gt_ln_count->_tablename_}`.`{$gt_ln_count->count}`) AS NAME_COUNT
			FROM `{$gt_mep->_tablename_}`
			LEFT JOIN `{$gt_ln_count->_tablename_}`
			ON `{$gt_ln_count->_tablename_}`.`{$gt_ln_count->mep_id}` = `{$gt_mep->_tablename_}`.`{$gt_mep->id}`
			WHERE `{$gt_mep->_tablename_}`.`{$gt_mep->region_id}`=%d 
			GROUP BY `{$gt_mep->_tablename_}`.{$gt_mep->name_cz}
			ORDER BY NAME_COUNT DESC
			LIMIT 10;
		", $region_id );

		return $this->db->wpdb->get_results( $query, OBJECT_K );
	}

	/**
	 * Inserts record to DB
	 *
	 * @param array $columns values (id, name_cz, name_de, region_id, lat, lng)
	 * $columns count == 6
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( array $columns ): bool {
		$table = $this->db->mep_table->_tablename_;

		if ( count( $columns ) != 6 ) {
			return false;
		}

		$result = $this->db->wpdb->insert( $table, array(
			'id'        => $columns[0],
			'name_cz'   => $columns[1],
			'name_de'   => $columns[2],
			'region_id' => $columns[3],
			'lat'       => $columns[4],
			'lng'       => $columns[5]
		) );

		return $result !== false;
	}

	/**
	 * Get number of records from table
	 *
	 * @return array|null array with table name and records count
	 */
	public function get_records_count(): ?array {
		$table = $this->db->mep_table;

		$query = $this->db->wpdb->prepare( "
			SELECT COUNT(*)
			FROM `{$table->_tablename_}`;
		" );

		return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
	}
}