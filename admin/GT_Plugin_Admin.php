<?php

/**
 * Class GT_Admin_Construction defines admin-oriented shortcode initialization and its dependencies.
 */
class GT_Plugin_Admin {

	#region PRIVATE VARS

	/**
	 * @var string Last name CSV first line
	 */
	private string $LAST_NAME_COLUMNS = "id,name,count";
	/**
	 * @var string Last name count CSV first line
	 */
	private string $LAST_NAME_COUNT_COLUMNS = "name_id,mep_id,count";
	/**
	 * @var string Last name explanation CSV first line
	 */
	private string $LAST_NAME_EXPLANATION = "name,explanation";
	/**
	 * @var string First name CSV first line
	 */
	private string $FIRST_NAME_COLUMNS = "id,name";
	/**
	 * @var string First name diminutive CSV first line
	 */
	private string $FIRST_NAME_DIMINUTIVE_COLUMNS = "name,diminutive";
	/**
	 * @var string First name translation CSV first line
	 */
	private string $FIRST_NAME_TRANSLATION_COLUMNS = "name_en,name_cz,priority";
	/**
	 * @var string District CSV first line
	 */
	private string $DISTRICT_COLUMNS = "id,name_cz,name_en,region_id";
	/**
	 * @var string MEP CSV first line
	 */
	private string $MEP_COLUMNS = "id,name_cz,name_de,region_id,lat,lng";
	/**
	 * @var string Region CSV first line
	 */
	private string $REGION_COLUMNS = "id,name_cz,name_en,map_code";
	/**
	 * @var string City CSV first line
	 */
	private string $CITY_COLUMNS = "id,name_cz,name_de,district_id,note";
    private string $EN_CZ_COLUMNS = "id,czech_word,english_translation";

	#endregion

	#region PUBLIC VARS

	/**
	 * @var GT_Plugin Instance of the core plugin class.
	 */
	public GT_Plugin $plugin;

	/**
	 * @var GT_Admin_Output_Manager Instance of admin output manager class.
	 */
	public GT_Admin_Output_Manager $plugin_admin_output_manager;

	/**
	 * @var GT_Container Instance of Container
	 */
	public GT_Container $plugin_container;
	#endregion

	#region CONSTRUCTOR

	/**
	 * GT_Plugin_Admin constructor.
	 *
	 * @param GT_Plugin $plugin The instance of the core plugin class.
	 */
	public function __construct( GT_Plugin $plugin ) {
		$this->plugin = $plugin;

		$this->plugin_admin_output_manager = new GT_Admin_Output_Manager();
		$this->plugin_container            = GT_Container::instance();
	}

	#endregion

	#region INITIALIZE
	/**
	 * Register the plugin within Wordpress
	 */
	public function register() {
		// ------- Include JS and CSS files -------
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// ------- Add Admin page -------
		add_action( 'admin_menu', array( $this, 'register_admin_menu_pages' ) );

		// ------- Actions -------
		add_action( 'wp_ajax_gt_ln_import', array( $this, 'action_gt_ln_import' ) );
		add_action( 'wp_ajax_nopriv_gt_ln_import', array( $this, 'action_lgt_ln_import' ) );

		add_action( 'wp_ajax_gt_ln_count_import', array( $this, 'action_gt_ln_count_import' ) );
		add_action( 'wp_ajax_nopriv_gt_ln_count_import', array( $this, 'action_gt_ln_count_import' ) );

		add_action( 'wp_ajax_gt_ln_explanation_import', array( $this, 'action_gt_ln_explanation_import' ) );
		add_action( 'wp_ajax_nopriv_gt_ln_explanation_import', array( $this, 'action_gt_ln_explanation_import' ) );

		add_action( 'wp_ajax_gt_fn_import', array( $this, 'action_gt_fn_import' ) );
		add_action( 'wp_ajax_nopriv_gt_fn_import', array( $this, 'action_gt_fn_import' ) );

		add_action( 'wp_ajax_gt_fn_translation_import', array( $this, 'action_gt_fn_translation_import' ) );
		add_action( 'wp_ajax_nopriv_gt_fn_translation_import', array( $this, 'action_gt_fn_translation_import' ) );

        add_action( 'wp_ajax_gt_fn_translation_import', array( $this, 'action_gt_fn_en_cz_import' ) );
        add_action( 'wp_ajax_nopriv_gt_fn_translation_import', array( $this, 'action_gt_fn_en_cz_import' ) );

		add_action( 'wp_ajax_gt_fn_diminutives_import', array( $this, 'action_gt_fn_diminutives_import' ) );
		add_action( 'wp_ajax_nopriv_gt_fn_diminutives_import', array( $this, 'action_gt_fn_diminutives_import' ) );

		add_action( 'wp_ajax_gt_cities_import', array( $this, 'action_gt_cities_import' ) );
		add_action( 'wp_ajax_nopriv_gt_cities_import', array( $this, 'action_gt_cities_import' ) );

		add_action( 'wp_ajax_gt_regions_import', array( $this, 'action_gt_regions_import' ) );
		add_action( 'wp_ajax_nopriv_gt_regions_import', array( $this, 'action_gt_regions_import' ) );

		add_action( 'wp_ajax_gt_districts_import', array( $this, 'action_gt_districts_import' ) );
		add_action( 'wp_ajax_nopriv_gt_districts_import', array( $this, 'action_gt_districts_import' ) );

		add_action( 'wp_ajax_gt_mep_import', array( $this, 'action_gt_mep_import' ) );
		add_action( 'wp_ajax_nopriv_gt_mep_import', array( $this, 'action_gt_mep_import' ) );

		add_action( 'wp_ajax_gt_tables_info', array( $this, 'action_gt_tables_info' ) );
		add_action( 'wp_ajax_nopriv_gt_tables_info', array( $this, 'action_gt_tables_info' ) );

		add_action( 'wp_ajax_gt_import_one_record', array( $this, 'action_gt_import_one_record' ) );
		add_action( 'wp_ajax_nopriv_gt_import_one_record', array(
			$this,
			'action_gt_import_one_record'
		) );

		add_action( 'admin_post_gt_export_table', array( $this, 'action_gt_export_table' ) );
	}

	#endregion

	#region PUBLIC METHODS

	/**
	 * Adds setting menu into WordPress.
	 */
	public function register_admin_menu_pages() {
		add_menu_page( 'Genealogy Tools"', 'Genealogy Tools', 'manage_options', 'gt_admin_info', array(
			$this->plugin_admin_output_manager,
			"main_info"
		) );
		add_submenu_page( 'gt_admin_info', 'Basic Settings', 'Basic Settings', 'manage_options', 'gt_admin_basic_settings', array(
			$this->plugin_admin_output_manager,
			"basic_settings"
		) );
		add_submenu_page( 'gt_admin_info', 'Advanced Settings', 'Advanced Settings', 'manage_options', 'gt_admin_advanced_settings', array(
			$this->plugin_admin_output_manager,
			"advanced_settings"
		) );
	}

	/**
	 * Register scripts and styles.
	 */
	public function enqueue_scripts( $hook ) {
		wp_register_script( 'gt_js_selectors', $this->plugin->plugin_dir_url() . 'common/js/enums/selectors.js', array() );
		wp_register_script( 'gt_js_admin', plugin_dir_url( __FILE__ ) . '/js/admin.js', array( 'gt_js_selectors' ), true );
		wp_enqueue_script( 'gt_js_admin' );

		// The main admin script
		wp_enqueue_script( 'gt_js_admin' );

		// Add ajax information via object reference to 'gt_js_admin' (injected variables start with double underscore)
		wp_localize_script( 'gt_js_admin', '__ajax_obj', array(
			'url'   => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( GT_PREFIX . 'nonce' )
		) );

		$wp_scripts = wp_scripts();
		// current jquery ui CSS
		wp_enqueue_style( 'gt_css_jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css' );

		wp_enqueue_style( 'gt_css_public', $this->plugin->plugin_dir_url() . 'public/css/public.css' );
		wp_enqueue_style( 'gt_css_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' );
	}

	#endregion

	#region PRIVATE METHODS

	/**
	 * Insert CSV to database
	 *
	 * @param $dao     GT_DAO instance
	 * @param $columns string columns
	 */
	private function insert_csv( $dao, $columns ) {
		$fileName = "";
		// if there is any file
		if ( isset( $_FILES['file'] ) ) {
			if ( $_FILES["file"]["tmp_name"] ) {
				$fileName = $_FILES["file"]["tmp_name"];
			} else {
				$this->plugin->error( "Please, select CSV file" );
			}
		}

		$result = array();
		if ( isset( $_FILES["file"] ) && $_FILES["file"]["size"] > 0 ) {
			$file = fopen( $fileName, "r" );
			// Eliminating the first row of CSV file
			$head = fgetcsv( $file, 10000, ';', '"' );
			// Check file format
			if ( implode( ",", $head ) != $columns ) {
				$this->plugin->error( "Invalid CSV file format. Expected: " . $columns );
			}

			$dao->db->start_transaction();

			while ( ( $column = fgetcsv( $file ) ) !== false ) {
				// inserting values into the table
				$insert_result = $dao->insert( $column );
				if ( $insert_result === false ) {
					array_push( $result, $column );
				}
			}

			$dao->db->commit_transaction();

			fclose( $file );
			$this->plugin->success( $result );
		}
		$this->plugin->error( "" );
	}
	#endregion

	#region ACTION METHODS

	public function action_gt_ln_import() {
		$dao = $this->plugin_container->get_ln_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->ln_table );

		// create new table
		$dao->db->create_table( $dao->db->ln_table );

		// insert
		$this->insert_csv( $dao, $this->LAST_NAME_COLUMNS );
	}

	public function action_gt_ln_count_import() {
		$dao = $this->plugin_container->get_ln_count_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->ln_count_table );

		// create new table
		$dao->db->create_table( $dao->db->ln_count_table );

		// insert
		$this->insert_csv( $dao, $this->LAST_NAME_COUNT_COLUMNS );
	}

	public function action_gt_ln_explanation_import() {
		$dao = $this->plugin_container->get_ln_explanation_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->ln_explanation_table );

		// create new table
		$dao->db->create_table( $dao->db->ln_explanation_table );

		// insert
		$this->insert_csv( $dao, $this->LAST_NAME_EXPLANATION );
	}

	public function action_gt_fn_import() {
		$dao = $this->plugin_container->get_fn_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->fn_table );

		// create new table
		$dao->db->create_table( $dao->db->fn_table );

		// insert
		$this->insert_csv( $dao, $this->FIRST_NAME_COLUMNS );
	}
    public function action_gt_fn_en_cz_import() {
        $dao = $this->plugin_container->get_cz_en_transtation_dao();
        // drop table records
        $dao->db->drop_table( $dao->db->fn_en_cz_translation_table );

        // create new table
        $dao->db->create_table( $dao->db->fn_en_cz_translation_table );

        // insert
        $this->insert_csv( $dao, $this->EN_CZ_COLUMNS );
    }

	public function action_gt_fn_translation_import() {
		$dao = $this->plugin_container->get_fn_translation_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->fn_translation_table );

		// create new table
		$dao->db->create_table( $dao->db->fn_translation_table );

		// insert
		$this->insert_csv( $dao, $this->FIRST_NAME_TRANSLATION_COLUMNS );
	}

	public function action_gt_fn_diminutives_import() {
		$dao = $this->plugin_container->get_fn_diminutive_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->fn_diminutive_table );

		// create new table
		$dao->db->create_table( $dao->db->fn_diminutive_table );

		// insert
		$this->insert_csv( $dao, $this->FIRST_NAME_DIMINUTIVE_COLUMNS );
	}

	public function action_gt_cities_import() {
		$dao = $this->plugin_container->get_city_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->city_table );

		// create new table
		$dao->db->create_table( $dao->db->city_table );

		// insert
		$this->insert_csv( $dao, $this->CITY_COLUMNS );
	}

	public function action_gt_regions_import() {
		$dao = $this->plugin_container->get_region_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->region_table );

		// create new table
		$dao->db->create_table( $dao->db->region_table );

		// insert
		$this->insert_csv( $dao, $this->REGION_COLUMNS );
	}

	public function action_gt_districts_import() {
		$dao = $this->plugin_container->get_district_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->district_table );

		// create new table
		$dao->db->create_table( $dao->db->district_table );

		// insert
		$this->insert_csv( $dao, $this->DISTRICT_COLUMNS );
	}

	public function action_gt_mep_import() {
		$dao = $this->plugin_container->get_mep_dao();
		// drop table records
		$dao->db->drop_table( $dao->db->mep_table );

		// create new table
		$dao->db->create_table( $dao->db->mep_table );

		// insert
		$this->insert_csv( $dao, $this->MEP_COLUMNS );
	}

	public function action_gt_tables_info() {
		$mep_dao            = $this->plugin_container->get_mep_dao();
		$region_dao         = $this->plugin_container->get_region_dao();
		$district_dao       = $this->plugin_container->get_district_dao();
		$city_dao           = $this->plugin_container->get_city_dao();
		$ln_dao             = $this->plugin_container->get_ln_dao();
		$ln_count_dao       = $this->plugin_container->get_ln_count_dao();
		$ln_explanation_dao = $this->plugin_container->get_ln_explanation_dao();
		$fn_dao             = $this->plugin_container->get_fn_dao();
		$fn_translation_dao = $this->plugin_container->get_fn_translation_dao();
		$fn_diminutives_dao = $this->plugin_container->get_fn_diminutive_dao();
        $fn_en_cz_translation_dao = $this->plugin_container->get_cz_en_transtation_dao();

		$result = array();
		array_push( $result, $mep_dao->get_records_count() );
		array_push( $result, $region_dao->get_records_count() );
		array_push( $result, $district_dao->get_records_count() );
		array_push( $result, $city_dao->get_records_count() );
		array_push( $result, $ln_dao->get_records_count() );
		array_push( $result, $ln_count_dao->get_records_count() );
		array_push( $result, $ln_explanation_dao->get_records_count() );
		array_push( $result, $fn_dao->get_records_count() );
		array_push( $result, $fn_translation_dao->get_records_count() );
		array_push( $result, $fn_diminutives_dao->get_records_count() );
        array_push($result, $fn_en_cz_translation_dao->get_records_count() );

		$this->plugin->success( $result );
	}

	public function action_gt_import_one_record() {
		$insert_result = array();
		$name          = esc_sql( filter_input( INPUT_POST, 'name_cz', FILTER_SANITIZE_STRING ) );
		$explanation   = esc_sql( filter_input( INPUT_POST, 'explanation', FILTER_SANITIZE_STRING ) );
		$diminutive    = esc_sql( filter_input( INPUT_POST, 'diminutive', FILTER_SANITIZE_STRING ) );
		$name_en       = esc_sql( filter_input( INPUT_POST, 'name_en', FILTER_SANITIZE_STRING ) );
		$priority      = filter_input( INPUT_POST, 'priority', FILTER_SANITIZE_NUMBER_INT );

		if ( $name != "" && $explanation != "" ) {
			$dao           = $this->plugin_container->get_ln_explanation_dao();
			$insert_result = $dao->insert( array( strtoupper( $name ), $explanation ) );
		} else if ( $name != "" && $diminutive != "" ) {
			$dao           = $this->plugin_container->get_fn_diminutive_dao();
			$insert_result = $dao->insert( array( strtoupper( $name ), $diminutive ) );
		} else if ( $name != "" && $name_en != "" ) {
			$dao           = $this->plugin_container->get_fn_translation_dao();
			$insert_result = $dao->insert( array( strtoupper( $name_en ), strtoupper( $name ), $priority ) );
		} else {
			$this->plugin->error( "Invalid inputs" );
		}

		$this->plugin->success( [] );
	}

	public function action_gt_export_table() {
		$table       = esc_sql( filter_input( INPUT_POST, 'table', FILTER_SANITIZE_STRING ) );
		$dao         = $this->plugin_container->get_fn_diminutive_dao();
		$wp_filename = $table . "_" . time() . ".csv";
		$columns     = "";

		if ( $table == "ln_explanation" ) {
			$dao     = $this->plugin_container->get_ln_explanation_dao();
			$columns = $this->LAST_NAME_EXPLANATION;
		} else if ( $table == "fn_diminutive" ) {
			$dao     = $this->plugin_container->get_fn_diminutive_dao();
			$columns = $this->FIRST_NAME_DIMINUTIVE_COLUMNS;
		} else if ( $table == "fn_translation" ) {
			$dao     = $this->plugin_container->get_fn_translation_dao();
			$columns = $this->FIRST_NAME_TRANSLATION_COLUMNS;
		} else {
			$this->plugin->error( "Invalid inputs" );
		}
		$all_records = $dao->get_all_records();

		// Clean object
		ob_end_clean();

		// Set file download headers
		header( "Content-Type: application/csv;" );
		header( "Content-Disposition: attachment; filename=" . $wp_filename );

		// Open file
		$file = fopen( 'php://output', 'w' );

		//write the header
		$header_row = explode( ",", $columns );
		fputcsv( $file, $header_row );

		// loop for insert data into CSV file
		foreach ( $all_records as $record ) {
			fputcsv( $file, $record );
		}
	}
	#endregion
}