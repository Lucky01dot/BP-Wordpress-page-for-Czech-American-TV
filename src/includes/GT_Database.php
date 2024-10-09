<?php

/**
 * Class for database context.
 */
class GT_Database {
	/**
	 * @var wpdb A wpdb object of database context.
	 */
	public wpdb $wpdb;

	public GT_LN_Table $ln_table;
	public GT_FN_Table $fn_table;
	public GT_FN_Translation_Table $fn_translation_table;
	public GT_FN_Diminutive_Table $fn_diminutive_table;
	public GT_LN_Explanation_Table $ln_explanation_table;
	public GT_Region_Table $region_table;
	public GT_MEP_Table $mep_table;
	public GT_District_Table $district_table;
	public GT_City_Table $city_table;
	public GT_LN_Count_Table $ln_count_table;

	/**
	 * GT_Database constructor.
	 *
	 * @param string $host
	 * @param string $database
	 * @param string $username
	 * @param string $password
	 * @param string $table_prefix
	 */
	public function __construct( string $host, string $database, string $username, string $password, string $table_prefix = "gt_" ) {
		// Connect to DB
		$this->wpdb = new wpdb( $username, $password, $database, $host );

		// Create tables instances
		$this->fn_translation_table = new GT_FN_Translation_Table( $table_prefix . GT_Tables::FIRST_NAME_TRANSLATION );
		$this->fn_diminutive_table  = new GT_FN_Diminutive_Table( $table_prefix . GT_Tables::FIRST_NAME_DIMINUTIVE );
		$this->ln_explanation_table = new GT_LN_Explanation_Table( $table_prefix . GT_Tables::LAST_NAME_EXPLANATION );
		$this->region_table         = new GT_Region_Table( $table_prefix . GT_Tables::REGION );
		$this->mep_table            = new GT_MEP_Table( $table_prefix . GT_Tables::MEP );
		$this->district_table       = new GT_District_Table( $table_prefix . GT_Tables::DISTRICT );
		$this->city_table           = new GT_City_Table( $table_prefix . GT_Tables::CITY );
		$this->ln_table             = new GT_LN_Table( $table_prefix . GT_Tables::LAST_NAME );
		$this->fn_table             = new GT_FN_Table( $table_prefix . GT_Tables::FIRST_NAME );
		$this->ln_count_table       = new GT_LN_Count_Table( $table_prefix . GT_Tables::LAST_NAME_COUNT );

		// Turnoff errors output
		$this->wpdb->show_errors( false );
	}

	/**
	 * Returns array of tables in the order for creation.
	 *
	 * @return GT_Table[]
	 */
	protected function sorted_tables(): array {
		return [
			$this->fn_translation_table,
			$this->fn_diminutive_table,
			$this->ln_explanation_table,
			$this->region_table,
			$this->mep_table,
			$this->district_table,
			$this->city_table,
			$this->ln_table,
			$this->fn_table,
			$this->ln_count_table
		];
	}

	/**
	 * Creates all tables.
	 */
	public function create_tables(): bool {
		foreach ( $this->sorted_tables() as $table ) {
			$result = $table->create( $this->wpdb );

			if ( ! $result ) {
				return false;
			}
		}

		return true;
	}

	public function create_table( $table ): bool {
		$result = $table->create( $this->wpdb );

		if ( ! $result ) {
			return false;
		}

		return true;
	}

	/**
	 * Drops all tables.
	 */
	public function drop_tables(): bool {
		foreach ( array_reverse( $this->sorted_tables() ) as $table ) {
			$result = $table->drop( $this->wpdb );

			if ( ! $result ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param $table GT_Table table
	 *
	 * @return bool
	 */
	public function drop_table( $table ): bool {
		$result = $table->drop( $this->wpdb );

		if ( ! $result ) {
			return false;
		}

		return true;
	}

	//region ------- Transactions -------

	/**
	 * Start database transaction.
	 *
	 * @return bool True on success, false on error.
	 */
	public function start_transaction(): bool {
		return $this->wpdb->query( "START TRANSACTION" ) !== false;
	}

	/**
	 * Commit database transaction.
	 *
	 * @return bool True on success, false on error.
	 */
	public function commit_transaction(): bool {
		return $this->wpdb->query( "COMMIT" ) !== false;
	}

	/**
	 * Rollback database transaction.
	 *
	 * @return bool True on success, false on error.
	 */
	public function rollback_transaction(): bool {
		return $this->wpdb->query( "ROLLBACK" ) !== false;
	}

	//endregion


	/**
	 * Check if the underlying connection has encountered an error.
	 *
	 * @return bool
	 */
	public function has_error(): bool {
		return ! empty( $this->wpdb->last_error );
	}

	/**
	 * Get the last error.
	 *
	 * @return string
	 */
	public function get_last_error(): string {
		return $this->wpdb->last_error;
	}
}
