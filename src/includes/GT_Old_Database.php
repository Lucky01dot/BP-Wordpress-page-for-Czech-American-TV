<?php

/**
 * TODO: refactor this in the same manner as the already refactored classes - regions etc.
 * Class for handling database operations.
 */
class GT_Old_Database {

    // TODO: foreign keys and col names?
    /**
     * A wpdb object with connection details set to genealogy db.
     * @var wpdb 
     */
    public $wpdb;

	/**
	 * GT_Database constructor.
	 */
    public function __construct() {
        // Connect to DB
        $this->wpdb = new wpdb(GT_DB_USER, GT_DB_PASSWORD, GT_DB_DATABASE, GT_DB_HOST);
    }

    #region QUERIES

	/**
	 * Deletes all created tables.
	 */
	function drop_tables() {
		foreach ( $this->table_names as $table ) {
			$this->wpdb->query("DROP TABLE IF EXISTS " . $table);
		}
	}

	/**
	 * Creates the database structure.
	 */
	public function create_tables() {
		//create last name db
		$this->wpdb->query($this->get_names_sql(GT_Tables::LAST_NAMES));
		//create first name db
		$this->wpdb->query($this->get_names_sql(GT_Tables::FIRST_NAMES));
		//create regions db
		$this->wpdb->query($this->regions_sql());
		//create cities db
		$this->wpdb->query($this->cities_sql());
		//create last name count db
		$this->wpdb->query($this->names_count_sql(GT_Tables::LAST_NAME_COUNTS));
		$this->wpdb->query($this->names_count_sql(GT_Tables::LAST_NAMES));
		//create first name count db
		$this->wpdb->query($this->names_count_sql(GT_Tables::FIRST_NAME_COUNTS));
		$this->wpdb->query($this->names_count_sql(GT_Tables::FIRST_NAMES));
		//create last name translation db
		$this->wpdb->query($this->get_names_translations_sql(GT_Tables::LAST_NAME_TRANSLATIONS));
		$this->wpdb->query($this->get_names_translations_sql(GT_Tables::LAST_NAMES));
		//create first name translation db
		$this->wpdb->query($this->get_names_translations_sql(GT_Tables::FIRST_NAME_TRANSLATIONS));
		$this->wpdb->query($this->get_names_translations_sql(GT_Tables::FIRST_NAMES));
		//create historical cities db
		$this->wpdb->query($this->cities_his_sql());
		//create diminutives table
		$this->wpdb->query($this->diminutives_sql());
		//create ln explanations table
		$this->wpdb->query($this->ln_explanations_sql());
	}

	/**
	 * Creates SQL query to create tables for first/last names.
	 *
	 * @param string $table_name name of the table to create
	 *
	 * @return string the created query
	 */
	private function get_names_sql(string $table_name): string {
		$sql = "
        CREATE TABLE IF NOT EXISTS {$table_name} (
            name_id int(11)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name varchar(64) NOT NULL,
            KEY name (name)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";
		return $sql;
	}

	/**
	 * Creates SQL query to create tables for first/last name counts.
	 * @param string $table_name name of the table to create
	 * @return string the created query
	 */
	private function names_count_sql($table_name) {
		$sql = "
        CREATE TABLE IF NOT EXISTS {$table_name} (
            name_id int(11) UNSIGNED NOT NULL,
            city_id int(11) UNSIGNED NOT NULL,
            count int(11) UNSIGNED NOT NULL,
            PRIMARY KEY  (name_id, city_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		return $sql;
	}

	/**
	 * Creates SQL query to create tables for first/last name translations.
	 * @param string $table_name name of the table to create
	 * @return string the created query
	 */
	private function get_names_translations_sql($table_name) {

		$sql = "
        CREATE TABLE IF NOT EXISTS {$table_name} (
            name_en varchar(64) NOT NULL,
            name_cz_id int(11) UNSIGNED NOT NULL,
            PRIMARY KEY  (name_en, name_cz_id)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;";

		return $sql;
	}

	/**
	 * Creates SQL query to create table for cities.
	 * @return string the created query
	 */
	private function cities_sql() {
		$sql = "
        CREATE TABLE IF NOT EXISTS %s (
            city_id int(11) UNSIGNED NOT NULL PRIMARY KEY,
            name varchar(64) NOT NULL,
            region_id int(11) NOT NULL,
            lat float NOT NULL,
            lng float NOT NULL,
            name_ger varchar(64) NOT NULL
        )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;";
		return sprintf($sql, GT_Tables::CITIES);
	}

	/**
	 * Creates SQL query to create table for historic city names.
	 * @return string the created query
	 */
	private function cities_his_sql() {
		$sql = "
        CREATE TABLE IF NOT EXISTS %s (
            ch_id int(11)  NOT NULL PRIMARY KEY,
            name_cz varchar(64) NOT NULL,
            name_ger varchar(64) NOT NULL,
            parent_id int(11) NOT NULL
        )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;";
		return sprintf($sql, GT_Tables::CITIES_HISTORICAL);
	}

	/**
	 * Creates SQL query to create table for regions.
	 * @return string the created query
	 */
	private function regions_sql() {
		$sql = "
        CREATE TABLE IF NOT EXISTS %s (
            region_id int(11)  NOT NULL PRIMARY KEY,
            name varchar(64) NOT NULL,
            map_code varchar(8) NOT NULL
        )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;";
		return sprintf($sql, GT_Tables::REGIONS);
	}

	/**
	 * Creates SQL query to create table for diminutives.
	 * @return string the created query
	 */
	private function diminutives_sql() {
		$sql = "
        CREATE TABLE IF NOT EXISTS %s (
            fn_id int(11)  NOT NULL,
            diminutive varchar(64) NOT NULL,
            PRIMARY KEY  (fn_id, diminutive)
        )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;";
		return sprintf($sql, GT_Tables::DIMINUTIVES);
	}

	/**
	 * Creates SQL query to create table for last name explanations.
	 * @return string the created query
	 */
	private function ln_explanations_sql() {
		$sql =
			"CREATE TABLE IF NOT EXISTS %s (
            name_id int(11)  NOT NULL PRIMARY KEY,
            explanation varchar(256) NOT NULL
        )ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;";
		return sprintf($sql, GT_Tables::LAST_NAME_EXPLANATIONS);
	}
    #endregion

	#region IMPORT DATA QUERY

	/**
	 * Fills the database with data with CSV from the plugin data subfolder.
	 */
	public function fill_tables() {
		$dir = GT_PLUGIN_ROOT_DIR; //online
		//$dir = "D:/catvusa/wp-content/plugins/catv_genealogy_tools"; //local
		foreach ( $this->table_names as $table ) {
			$sql = "
            LOAD DATA LOCAL INFILE '" . $dir . "/data/" . $table . ".csv' IGNORE INTO TABLE " . $table . " 
            FIELDS TERMINATED BY ','
            ENCLOSED BY '\"'
            LINES TERMINATED BY '\r\n';
            ";
			$this->wpdb->query($sql);
		}
	}

	/**
	 * Imports table from CSV files on user`s local machine.
	 * @param string $filename path to the file on user`s machine
	 * @param string $table_name name of the table to import to
	 * @return type
	 */
	public function import_table($filename, $table_name) {
		$sql = "
            LOAD DATA LOCAL INFILE '" . $filename . "' IGNORE INTO TABLE " . $table_name . " 
            FIELDS TERMINATED BY ','
            ENCLOSED BY '\"'
            LINES TERMINATED BY '\r\n';
            ";
		if ( $this->wpdb->query($sql) === NULL ) {
			return NULL;
		} else {
			$results = array();
			$results["new_number"] = $this->wpdb->get_var("SELECT COUNT(*) FROM " . $table_name);
			return $results;
		}
	}
	#endregion
}
