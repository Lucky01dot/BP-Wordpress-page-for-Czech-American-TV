<?php


/**
 * Data access object for last name counts.
 */
class GT_LN_Count_DAO extends GT_DAO {

	/**
	 * Gets top 10 popular names by MEP id.
	 *
	 * @param int $mep_id The id of MEP.
	 *
	 * @return array|null Array of objects {`id`(name id), `name`, `count`}.
	 */
	public function get_popular_names_by_mep( int $mep_id ): ?array {
		$name_table  = $this->db->ln_table;
		$count_table = $this->db->ln_count_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$name_table->_tablename_}`.`{$name_table->id}` as `id`,
			       `{$name_table->_tablename_}`.`{$name_table->name}` as `name`,
			       `{$count_table->_tablename_}`.`{$count_table->count}` as `count`
			FROM `{$count_table->_tablename_}`
			LEFT JOIN `{$name_table->_tablename_}`
			    ON `{$count_table->_tablename_}`.`{$count_table->name_id}`=`{$name_table->_tablename_}`.`{$name_table->id}`
			WHERE `{$count_table->_tablename_}`.`{$count_table->mep_id}`=%d
			ORDER BY `{$count_table->_tablename_}`.`{$count_table->count}` DESC
			LIMIT 10;
		", $mep_id );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Gets counts in MEPs by name id.
	 *
	 * @param int $name_id The id of name.
	 *
	 * @return array|null Array of objects `mep_id` => {`mep_id`, `count`}.
	 */
	public function get_counts_in_meps_by_name( int $name_id ): ?array {
		$table = $this->db->ln_count_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->mep_id}`, `{$table->count}`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name_id}`=%d
			ORDER BY `{$table->count}` DESC;
		", $name_id );

		return $this->db->wpdb->get_results( $query, OBJECT_K );
	}

	/**
	 * Gets counts in regions by name id.
	 *
	 * @param int $name_id The id of name.
	 *
	 * @return array|null Array of objects `region_id` => {`region_id`, `count`}.
	 */
	public function get_counts_in_regions_by_name( int $name_id ): ?array {
		$count_table = $this->db->ln_count_table;
		$mep_table   = $this->db->mep_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$mep_table->_tablename_}`.`{$mep_table->region_id}` AS `region_id`,
			       SUM(`{$count_table->_tablename_}`.`{$count_table->count}`) AS `count`
			FROM `{$count_table->_tablename_}`
			LEFT JOIN `{$mep_table->_tablename_}`
			    ON `{$count_table->_tablename_}`.`{$count_table->mep_id}`=`{$mep_table->_tablename_}`.`{$mep_table->id}`
			WHERE `{$count_table->_tablename_}`.`{$count_table->name_id}`=%d
			GROUP BY `{$mep_table->_tablename_}`.`{$mep_table->region_id}`
			ORDER BY `{$count_table->_tablename_}`.`{$count_table->count}` DESC;
		", $name_id );

		return $this->db->wpdb->get_results( $query, OBJECT_K );
	}

	/**
	 * Gets count in region by name id and region id.
	 *
	 * @param int $name_id The id of name.
	 * @param int $region_id The id of region.
	 *
	 * @return object|null Object {`count`}.
	 */
	public function get_counts_in_region_by_name_and_region( int $name_id, int $region_id ): ?object {
		$count_table = $this->db->ln_count_table;
		$mep_table   = $this->db->mep_table;

		$query = $this->db->wpdb->prepare( "
			SELECT SUM(`{$count_table->_tablename_}`.`{$count_table->count}`) AS `count`
			FROM `{$count_table->_tablename_}`
			LEFT JOIN `{$mep_table->_tablename_}`
			    ON `{$count_table->_tablename_}`.`{$count_table->mep_id}`=`{$mep_table->_tablename_}`.`{$mep_table->id}`
			WHERE `{$count_table->_tablename_}`.`{$count_table->name_id}`=%d
			  AND `{$mep_table->_tablename_}`.`{$mep_table->region_id}`=%d
			GROUP BY `{$mep_table->_tablename_}`.`{$mep_table->region_id}`;
		", $name_id, $region_id );

		$result = $this->db->wpdb->get_row( $query );

		if ( $result === null ) {
			$result = (object) [ "count" => 0 ];
		}

		return $result;
	}

	/**
	 * Gets counts in MEPs by name id and region id.
	 *
	 * @param int $name_id The id of name.
	 * @param int $region_id The id of region.
	 *
	 * @return array|null Array of objects `mep_id` => {`mep_id`, `count`}.
	 */
	public function get_counts_in_meps_by_name_and_region( int $name_id, int $region_id ): ?array {
		$count_table = $this->db->ln_count_table;
		$mep_table   = $this->db->mep_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$mep_table->_tablename_}`.`{$mep_table->id}` AS `mep_id`,
				SUM(`{$count_table->_tablename_}`.`{$count_table->count}`) AS `count`
			FROM `{$count_table->_tablename_}`
			LEFT JOIN `{$mep_table->_tablename_}`
			    ON `{$count_table->_tablename_}`.`{$count_table->mep_id}`=`{$mep_table->_tablename_}`.`{$mep_table->id}`
			WHERE `{$count_table->_tablename_}`.`{$count_table->name_id}`=%d
			  AND `{$mep_table->_tablename_}`.`{$mep_table->region_id}`=%d
			GROUP BY `{$mep_table->_tablename_}`.`{$mep_table->id}`;
		", $name_id, $region_id );

		return $this->db->wpdb->get_results( $query, OBJECT_K );
	}

	/**
	 * Gets count in MEP by name id and MEP id.
	 *
	 * @param int $name_id The id of name.
	 * @param int $mep_id The id of MEP.
	 *
	 * @return object|null Object {`count`}.
	 */
	public function get_counts_in_mep_by_name_and_mep( int $name_id, int $mep_id ): ?object {
		$count_table = $this->db->ln_count_table;

		$query = $this->db->wpdb->prepare( "
			SELECT SUM(`{$count_table->_tablename_}`.`{$count_table->count}`) AS `count`
			FROM `{$count_table->_tablename_}`
			WHERE `{$count_table->_tablename_}`.`{$count_table->name_id}`=%d
			  AND `{$count_table->_tablename_}`.`{$count_table->mep_id}`=%d
			GROUP BY `{$count_table->_tablename_}`.`{$count_table->mep_id}`;
		", $name_id, $mep_id );

		$result = $this->db->wpdb->get_row( $query );

		if ( $result === null ) {
			$result = (object) [ "count" => 0 ];
		}

		return $result;
	}

	/**
	 * Inserts record to DB
	 *
	 * @param array $columns values (name_id, mep_id, count)
	 * $columns count == 3
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( array $columns ): bool {
		$table = $this->db->ln_count_table->_tablename_;

		if ( count( $columns ) != 3 ) {
			return false;
		}

		$result = $this->db->wpdb->insert( $table, array(
			'name_id' => $columns[0],
			'mep_id'  => $columns[1],
			'count'   => $columns[2]
		) );

		return $result !== false;
	}

	/**
	 * Get number of records from table
	 *
	 * @return array|null array with table name and records count
	 */
	public function get_records_count(): ?array {
		$table = $this->db->ln_count_table;

		$query = $this->db->wpdb->prepare( "
			SELECT COUNT(*)
			FROM `{$table->_tablename_}`;
		" );

		return array( $table->_tablename_, $this->db->wpdb->get_row( $query ) );
	}

//		public function insert2( string $path ): ?int {
//			$count_table = $this->db->ln_count_table;
//
//			$query = $this->db->wpdb->prepare( "
//				LOAD DATA LOCAL INFILE %s
//		        REPLACE INTO TABLE %s FIELDS TERMINATED BY ','
//		        ENCLOSED BY '' LINES TERMINATED BY '\r\n' IGNORE 1 LINES
//			", $path, $count_table);
//
//			$result = $this->db->wpdb->query( $query );
//
//			return $result;
//		}
}