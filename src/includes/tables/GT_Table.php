<?php

/**
 * Base class for tables.
 */
abstract class GT_Table {

	/**
	 * The name of this table.
	 *
	 * @var string $_tablename_
	 */
	public string $_tablename_;

	/**
	 * GT_Table constructor.
	 *
	 * @param string $name The name of the table.
	 */
	public function __construct( string $name ) {
		$this->_tablename_ = $name;
	}

	/**
	 * Creates this table.
	 *
	 * @param wpdb $wpdb A wpdb database context.
	 *
	 * @return bool True on success, false otherwise.
	 */
	abstract public function create( wpdb $wpdb ): bool;

	/**
	 * Drops this table.
	 *
	 * @param wpdb $wpdb A wpdb database context.
	 *
	 * @return bool True on success, false otherwise.
	 */
	abstract public function drop( wpdb $wpdb ): bool;

	/**
	 * Imports data from a CSV file on the local machine (the server).
	 * The file can be for example the one from a POST request.
	 *
	 * @param string $file_path Path to the CSV file.
	 *
	 * @return bool|int Number of affected rows on success or false on failure.
	 */
	public function import_csv( wpdb $wpdb, string $file_path, string $delimiter = ",", string $quote = "\"" ): bool {
		// TODO: implement csv importing
		return false;
	}

//	/**
//	 * Fills the database with data with CSV from the plugin data subfolder.
//	 */
//	public function fill_tables() {
//		$dir = GT_PLUGIN_ROOT_DIR; //online
//		//$dir = "D:/catvusa/wp-content/plugins/catv_genealogy_tools"; //local
//		foreach ( $this->table_names as $table ) {
//			$sql = "
//            LOAD DATA LOCAL INFILE '" . $dir . "/data/" . $table . ".csv' IGNORE INTO TABLE " . $table . "
//            FIELDS TERMINATED BY ','
//            ENCLOSED BY '\"'
//            LINES TERMINATED BY '\r\n';
//            ";
//			$this->wpdb->query($sql);
//		}
//	}

//	/**
//	 * Imports table from CSV files on user`s local machine.
//	 * @param string $filename path to the file on user`s machine
//	 * @param string $table_name name of the table to import to
//	 * @return type
//	 */
//	public function import_table($filename, $table_name) {
//		$sql = "
//            LOAD DATA LOCAL INFILE '" . $filename . "' IGNORE INTO TABLE " . $table_name . "
//            FIELDS TERMINATED BY ','
//            ENCLOSED BY '\"'
//            LINES TERMINATED BY '\r\n';
//            ";
//		if ( $this->wpdb->query($sql) === NULL ) {
//			return NULL;
//		} else {
//			$results = array();
//			$results["new_number"] = $this->wpdb->get_var("SELECT COUNT(*) FROM " . $table_name);
//			return $results;
//		}
//	}
}