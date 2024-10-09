<?php


/**
 * Data access object for last name translations.
 */
class GT_FN_Translation_DAO extends GT_DAO {

	/**
	 * Gets all english names by prefix for autocomplete.
	 *
	 * @param string $prefix Prefix of english name.
	 *
	 * @return array|null Array of objects {`name`}. Null on error.
	 */
	public function get_names_en_by_prefix( string $prefix ): ?array {
		$table = $this->db->fn_translation_table;

		$query = $this->db->wpdb->prepare( "
			SELECT DISTINCT `{$table->name_en}` as `name`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name_en}` LIKE %s
			ORDER BY CHAR_LENGTH(`{$table->name_en}`)
			LIMIT 10;
		", $prefix . "%" );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Gets all czech names by prefix for autocomplete.
	 *
	 * @param string $prefix Prefix of czech name.
	 *
	 * @return array|null Array of objects {`name`}. Null on error.
	 */
	public function get_names_cz_by_prefix( string $prefix ): ?array {
		$table = $this->db->fn_translation_table;

		// general_ci collation is to ignore differences in accents (JIRI == JIŘÍ)
		$query = $this->db->wpdb->prepare( "
			SELECT DISTINCT `{$table->name_cz}` as `name`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name_cz}` LIKE %s COLLATE utf8mb4_general_ci
			ORDER BY CHAR_LENGTH(`{$table->name_cz}`)
			LIMIT 10;
		", $prefix . "%" );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Gets all czech translations from given english name.
	 *
	 * @param string $name_en Name in english.
	 *
	 * @return array|null Array of objects {`name`}. Null on error.
	 */
	public function get_translations_en_to_cz( string $name_en ): ?array {
		$table = $this->db->fn_translation_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->name_cz}` AS `name`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name_en}`=%s
			ORDER BY `{$table->priority}`;
		", $name_en );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Gets all english translations from given czech name.
	 *
	 * @param string $name_cz Name in czech.
	 *
	 * @return array|null Array of objects {`name`}. Null on error
	 */
	public function get_translations_cz_to_en( string $name_cz ): ?array {
		$table = $this->db->fn_translation_table;

		$query = $this->db->wpdb->prepare( "
			SELECT `{$table->name_en}` AS `name`
			FROM `{$table->_tablename_}`
			WHERE `{$table->name_cz}`=%s COLLATE utf8mb4_bin
			ORDER BY `{$table->priority}`;
		", $name_cz );

		return $this->db->wpdb->get_results( $query );
	}

	/**
	 * Inserts record to DB
	 *
	 * @param array $columns values ( name_en, name_cz, priority )
	 * $columns count == 2
	 *
	 * @return bool True on success, false otherwise.
	 */
	public function insert( array $columns ): bool {
		$table = $this->db->fn_translation_table->_tablename_;

		if ( count( $columns ) != 3 ) {
			return false;
		}

		$result = $this->db->wpdb->insert( $table, array(
			'name_en'  => $columns[0],
			'name_cz'  => $columns[1],
			'priority' => $columns[2]
		) );

		return $result !== false;
	}

	/**
	 * Get number of records from table
	 *
	 * @return array|null array with table name and records count
	 */
	public function get_records_count(): ?array {
		$table = $this->db->fn_translation_table;

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
		$table  = $this->db->fn_translation_table;

		$query = "
			SELECT *
			FROM `{$table->_tablename_}`;
		";

		$all_records = $this->db->wpdb->get_results( $query );

		// reformat
		foreach ( $all_records as $record ) {
			array_push( $result, array( 'name_en' => $record->name_en, 'name_cz' => $record->name_cz, 'priority' => $record->priority ) );
		}

		return $result;
	}
}