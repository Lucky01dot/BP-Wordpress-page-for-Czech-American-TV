<?php

/**
 * Class GT_Plugin is the core class of this plugin.
 */
class GT_Plugin {

	/**
	 * @var string The plugin file as got from base plugin file variable __FILE__.
	 */
	private string $plugin_file;

	/**
	 * Success status text.
	 */
	const STATUS_SUCCESS = "success";

	/**
	 * Error status text.
	 */
	const STATUS_ERROR = "error";

	/**
	 * @var GT_Plugin_Public Instance of the public plugin class.
	 */
	public GT_Plugin_Public $plugin_public;

	/**
	 * @var GT_Plugin_Admin Instance of the admin plugin class.
	 */
	public GT_Plugin_Admin $plugin_admin;

	/**
	 * GT_Plugin constructor.
	 *
	 * @param string $plugin_file The base file of the plugin as got from variable __FILE__..
	 */
	public function __construct( string $plugin_file ) {
		$this->plugin_file = $plugin_file;

		$this->plugin_public = new GT_Plugin_Public( $this );

		$this->plugin_admin = new GT_Plugin_Admin( $this );
	}

	/**
	 * Register the plugin within Wordpress
	 */
	public function register() {
		register_activation_hook( $this->plugin_file, array( $this, 'activate' ) );
		register_deactivation_hook( $this->plugin_file, array( $this, 'deactivate' ) );

		// ------- Include JS and CSS files -------
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// ------- Actions -------

		// autocomplete
		add_action( 'wp_ajax_gt_autocomplete', array(
			$this,
			'action_autocomplete'
		) );
		add_action( 'wp_ajax_nopriv_gt_autocomplete', array(
			$this,
			'action_autocomplete'
		) );

		// ------- Init dependencies -------

		// Register public sub plugin
		$this->plugin_public->register();

		// Register admin construction
		$this->plugin_admin->register();


//		$gt_database_obj = new GT_Old_Database();
//		GT_Plugin_Admin::get_instance()->init( $gt_database_obj );
	}


	//region ------- Wordpress hooks -------

	/**
	 * Activates the plugin.
	 * Creates all the database tables.
	 */
	public function activate() {
		$container = GT_Container::instance();

		// init option for storing database config
		add_option( GT_OPTION_DB );

		// connect to database
		$db = $container->get_database();

		if ( ! $db->wpdb->db_connect() ) {
			die( "Can't connect to the database." );
		}

		// create database tables
		if ( ! $db->create_tables() ) {
			$db->drop_tables();
			die( "Can't setup plugin tables." );
		}
	}

	/**
	 * Deactivates the plugin.
	 * Drops all the database tables.
	 */
	public function deactivate() {
		// delete option for storing database config
		delete_option( GT_OPTION_DB );
	}

	/**
	 * Enqueue scripts & CSS that are necessary for the website as a whole.
	 */
	public function enqueue_scripts() {
		// Get WP scripts as a global plugin variable
		$wp_scripts = wp_scripts();

		if ( defined( 'GT_DEV_MODE' ) ) {
			// load dev scripts
			wp_enqueue_script( 'gt_js_dev', $this->plugin_dir_url() . 'common/js/dev.js', array( 'jquery' ) );
		}

		// ------- Scripts -------

		// Request type enums
		wp_register_script( 'gt_js_request_types', $this->plugin_dir_url() . 'common/js/enums/request_types.js', array() );
		// Selector enum
		wp_register_script( 'gt_js_selectors', $this->plugin_dir_url() . 'common/js/enums/selectors.js', array() );
		// Input autocomplete
		wp_register_script( 'gt_js_autocomplete', $this->plugin_dir_url() . 'common/js/autocomplete.js', array(
			'jquery-ui-autocomplete',
			'gt_js_selectors',
			'gt_js_request_types'
		) );

		// ------- CSS -------
		// Register JQueryUI library CSS according to WordPress' JQuery version
		wp_register_style( 'gt_css_jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css' );
	}

	//endregion


	//region ------- AJAX responses -------

	/**
	 * Sends a response with error status and the given error message.
	 *
	 * @param string $error_msg Error message to be sent.
	 */
	public function error( string $error_msg ) {
		$response = [
			"status"    => self::STATUS_ERROR,
			"error_msg" => $error_msg
		];

		wp_die( json_encode( $response ) );
	}

	/**
	 * Sends a response with success status and given results.
	 *
	 * @param array $results
	 */
	public function success( array $results ) {
		$response = [
			"status"  => self::STATUS_SUCCESS,
			"results" => $results
		];

		wp_die( json_encode( $response ) );
	}

	//endregion


	//region ------- Actions -------

	/**
	 * AJAX autocomplete request handler.
	 *
	 * It requires 2 parameters:
	 *     - 'value' = searched value (user input)
	 *     - 'type' = type of the request (should match {@see GT_Autocomplete_Type})
	 *
	 * The request result contents depends on request type.
	 */
	public function action_autocomplete() {
		check_ajax_referer( GT_PREFIX . 'nonce' );

		$value = filter_input( INPUT_POST, 'value' );
		$type  = filter_input( INPUT_POST, 'type' );

		$result = null;

		// Get autocomplete results by type
		switch ( $type ) {
			case GT_Autocomplete_Type::GERMAN_TERMINOLOGY_CITY:
				$result = $this->plugin_public->plugin_german_terminology->autocomplete_city( $value );
				break;
			case GT_Autocomplete_Type::BEHIND_THE_NAME_FN_DIMINUTIVE:
				$result = $this->plugin_public->plugin_behind_the_name->autocomplete_fn_diminutive( $value );
				break;
			case GT_Autocomplete_Type::BEHIND_THE_NAME_LN_EXPLANATION:
				$result = $this->plugin_public->plugin_behind_the_name->autocomplete_ln_explanation( $value );
				break;
			case GT_Autocomplete_Type::CHANGING_NAMES_FEMALE_VARIANT:
				$result = $this->plugin_public->plugin_changing_names->autocomplete_female_variant( $value );
				break;
			case GT_Autocomplete_Type::CHANGING_NAMES_FN_TRANSLATION_EN_CZ:
				$result = $this->plugin_public->plugin_changing_names->autocomplete_fn_translation_en_cz( $value );
				break;
			case GT_Autocomplete_Type::CHANGING_NAMES_FN_TRANSLATION_CZ_EN:
				$result = $this->plugin_public->plugin_changing_names->autocomplete_fn_translation_cz_en( $value );
				break;
			case GT_Autocomplete_Type::NAME_DISTRIBUTION_LN:
				$result = $this->plugin_public->plugin_name_distribution->autocomplete_ln( $value );
				break;
			case GT_Autocomplete_Type::NAME_DISTRIBUTION_MEP:
				$result = $this->plugin_public->plugin_name_distribution->autocomplete_mep( $value );
				break;
            case GT_Autocomplete_Type::TRANSLATION_EN_CZ:
                $result = $this->plugin_public->plugin_en_cz_translation->autocomplete_cz_en_translation( $value );
                break;
            case GT_Autocomplete_Type::TRANSLATION_LA_EN:
                $result = $this->plugin_public->plugin_en_cz_translation->autocomplete_la_cz_translation( $value );
                break;
			default:
				$this->error( GT_Ajax_Error::INVALID_REQUEST_TYPE );
		}

		$this->success( $result );
	}

	//endregion


	//region ------- Helper methods -------

	/**
	 * Check if any of entered shortcodes are located in the current WP post page.
	 *
	 * @param $shortcodes array Specific shortcodes to check.
	 *
	 * @return bool True if so, otherwise False.
	 */
	public function shortcode_check( array $shortcodes ): bool {
		global $post;
		foreach ( $shortcodes as $sc ) {
			if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $sc ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Gets the plugin root directory with trailing slash.
	 *
	 * @return string The plugin root directory.
	 */
	public function plugin_dir_path(): string {
		return plugin_dir_path( $this->plugin_file );
	}

	/**
	 * Gets the plugin root URL within Wordpress.
	 *
	 * @return string The plugin root URL.
	 */
	public function plugin_dir_url(): string {
		return plugin_dir_url( $this->plugin_file );
	}

	//endregion
}