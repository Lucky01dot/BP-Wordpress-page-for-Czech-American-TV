<?php


/**
 * Class GT_Config serves for accessing the plugin configuration.
 */
class GT_Config {

	// ------- DATABASE CONFIG KEYS -------
	const KEY_DB_HOST = "host";
	const KEY_DB_DATABASE = "database";
	const KEY_DB_USERNAME = "username";
	const KEY_DB_PASSWORD = "password";
	const KEY_DB_TABLE_PREFIX = "table_prefix";


	// Page name PATHS
	public string $PAGE_CHANGING_NAMES = '/genealogy/changing-names';
	public string $PAGE_BEHIND_THE_NAME = '/genealogy/behind-the-name';
	public string $PAGE_GERMAN_TERMINOLOGY = '/genealogy/german-czech';
	public string $PAGE_NAMES_MAP = '/genealogy/map';
	public string $PAGE_TUTORIAL = '/genealogy/tutorial';


	/**
	 * GT_Config constructor.
	 */
	public function __construct() {
	}

	//region ------- Database -------

	/**
	 * Gets the plugin database config from wordpress database options table.
	 *
	 * @return array|null The plugin database config with keys: `host`, `name`, `username`, `password` and `table_prefix`.
	 */
	function get_db_config(): ?array {

		// get database config from global constants
		$config = [
			self::KEY_DB_HOST         => GT_DB_HOST,
			self::KEY_DB_DATABASE     => GT_DB_DATABASE,
			self::KEY_DB_USERNAME     => GT_DB_USERNAME,
			self::KEY_DB_PASSWORD     => GT_DB_PASSWORD,
			self::KEY_DB_TABLE_PREFIX => GT_DB_TABLE_PREFIX,
		];

		// TODO: replace global constants with the following
		// get plugin db config from wordpress db
		//$config = get_option( GT_OPTION_DB );


		if ( ! $config ) {
			return null;
		}

		return $config;
	}

	/**
	 * Sets the plugin database config in wordpress database options table.
	 *
	 * @param string $host
	 * @param string $name
	 * @param string $username
	 * @param string $password
	 * @param string $table_prefix
	 *
	 * @return array|null
	 */
	function set_db_config( string $host, string $name, string $username, string $password, string $table_prefix ): ?array {
		update_option( GT_OPTION_DB, [
			self::KEY_DB_HOST         => $host,
			self::KEY_DB_DATABASE     => $name,
			self::KEY_DB_USERNAME     => $username,
			self::KEY_DB_PASSWORD     => $password,
			self::KEY_DB_TABLE_PREFIX => $table_prefix,
		] );
	}

	//endregion
}