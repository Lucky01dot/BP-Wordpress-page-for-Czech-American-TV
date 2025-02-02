<?php

/**
 * Class for name counts map functionality.
 */
class GT_Plugin_Name_Distribution {

	/**
	 * @var GT_Plugin_Public Instance of the public plugin class.
	 */
	private GT_Plugin_Public $plugin_public;

	/**
	 * @var array Array of changing names shortcodes.
	 */
	public array $shortcodes;

	/**
	 * GT_Plugin_Behind_The_Name constructor.
	 *
	 * @param GT_Plugin_Public $plugin_public The instance of the public plugin class.
	 */
	public function __construct( GT_Plugin_Public $plugin_public ) {
		$this->plugin_public = $plugin_public;

		$this->shortcodes = [
			'catv_gt_map'
		];
	}

	/**
	 * Register the plugin within Wordpress
	 */
	public function register() {
		// ------- Include JS and CSS files -------
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// ------- Shortcodes -------
		add_shortcode( 'catv_gt_map', array( $this, 'shortcode_map' ) );

		// ------- Actions -------
		add_action( 'wp_ajax_gt_name_distribution_map_details', array(
			$this,
			'action_name_distribution_map_details'
		) );
		add_action( 'wp_ajax_nopriv_gt_name_distribution_map_details', array(
			$this,
			'action_name_distribution_map_details'
		) );

		// Last name info
		add_action( 'wp_ajax_gt_name_distribution_last_name_info', array( $this, 'action_last_name_info' ) );
		add_action( 'wp_ajax_nopriv_gt_name_distribution_last_name_info', array( $this, 'action_last_name_info' ) );
	}


	//region ------- Hooks -------

	/**
	 * Enqueue scripts & CSS.
	 */
	public function enqueue_scripts() {
		if ( $this->plugin_public->plugin->shortcode_check( $this->shortcodes ) ) {
			wp_enqueue_script( 'gt_js_map_detail_parameters', $this->plugin_public->plugin->plugin_dir_url() . 'public/js/enums/map_detail_parameters.js' );
			wp_enqueue_script( 'gt_js_map_chart', "https://www.gstatic.com/charts/loader.js" );
			wp_enqueue_script( 'gt_js_map_api', "https://maps.googleapis.com/maps/api/js?key=" . GT_GMAP_API_KEY );
			wp_enqueue_script( 'gt_js_map', $this->plugin_public->plugin->plugin_dir_url() . 'public/js/map.js', array(
				'jquery',
				'gt_js_map_chart',
				'gt_js_map_api'
			) );
			wp_enqueue_script( 'gt_js_details', $this->plugin_public->plugin->plugin_dir_url() . 'public/js/map_details.js', array(
				'jquery',
				'gt_js_autocomplete',
				'gt_js_map',
				'gt_js_map_detail_parameters'
			) );
		}
	}

	//endregion


	//region ------- Autocompletes -------

	/**
	 * Handle last name for map of names distribution autocomplete.
	 *
	 * @param string $value
	 *
	 * @return array|null
	 */
	public function autocomplete_ln( string $value ): ?array {
		$container = GT_Container::instance();
		$ln_dao    = $container->get_ln_dao();

		return $ln_dao->get_names_by_prefix( $value );
	}

	/**
	 * Handle municipalities with extended powers (MEP) for map of names distribution autocomplete.
	 *
	 * @param string $value
	 *
	 * @return array|null
	 */
	public function autocomplete_mep( string $value ): ?array {
		$container = GT_Container::instance();
		$mep_dao   = $container->get_mep_dao();

		return $mep_dao->get_meps_by_name_prefix( $value );
	}

	//endregion


	//region ------- Actions -------

	/**
	 * AJAX call handler for name distribution details.
	 *
	 * It requires 3 parameters:
	 *     - 'name_id' = Name ID (default empty string).
	 *     - 'mep_id' = Municipality with Extended Powers (MEP) ID (default empty string).
	 *     - 'region_id' = Region ID.
	 */
	public function action_name_distribution_map_details() {
		check_ajax_referer( GT_PREFIX . 'nonce' );

		$name_id   = esc_sql( filter_input( INPUT_POST, 'name_id', FILTER_SANITIZE_NUMBER_INT ) );
		$mep_id    = esc_sql( filter_input( INPUT_POST, 'mep_id', FILTER_SANITIZE_NUMBER_INT ) );
		$region_id = esc_sql( filter_input( INPUT_POST, 'region_id', FILTER_SANITIZE_NUMBER_INT ) );

		$container                 = GT_Container::instance();
		$name_distribution_service = $container->get_name_distribution_service();

		$result = null;

		if ( $name_id !== "" && $mep_id !== "" ) {
			// Name and city and region
			$result = $name_distribution_service->get_name_in_mep_info( $name_id, $mep_id );
		} else if ( $name_id !== "" && $mep_id === "" && $region_id !== "0" ) {
			// Name and region
			$result = $name_distribution_service->get_name_in_region_info( $name_id, $region_id );
		} else if ( $name_id !== "" && $mep_id === "" && $region_id === "0" ) {
			// Only name
			$result = $name_distribution_service->get_name_info( $name_id );
		} else if ( $name_id === "" && $mep_id !== "" ) {
			// Only MEP
			$result = $name_distribution_service->get_mep_info( $mep_id );
		} else if ( $name_id === "" && $mep_id === "" && $region_id !== "0" ) {
			// Only region
			$result = $name_distribution_service->get_region_info( $region_id );
		} else {
			$this->plugin_public->plugin->error( GT_Ajax_Error::INVALID_REQUEST_TYPE );
		}

		if ( $result == null ) {
			$this->plugin_public->plugin->error( GT_Ajax_Error::UNABLE_TO_LOAD_DATA );
		}

		$this->plugin_public->plugin->success( $result );
	}

	/**
	 * AJAX call handler for last name info.
	 *
	 * It requires 1 parameter:
	 *     - 'name' = Czech last name.
	 */
	public function action_last_name_info() {
		check_ajax_referer( GT_PREFIX . 'nonce' );

		$name = filter_input( INPUT_GET, 'name' );

		$container = GT_Container::instance();
		$ln_dao    = $container->get_ln_dao();

		$result = $ln_dao->get_name_by_name( $name );

		if ( $result ) {
			$this->plugin_public->plugin->success( (array) $result );
		} else {
			$this->plugin_public->plugin->error( GT_Ajax_Error::LAST_NAME_NOT_EXISTS );
		}
	}

	//endregion


	//region ------- Shortcodes -------

	/**
	 * Shortcode function for names map.
	 *
	 * @param string $attrs Attributes of the shortcode, unused.
	 * @param string|null $content Content inside the shortcode.
	 *
	 * @return false|string
	 */
	function shortcode_map( string $attrs, $content = null ) {
		$config = GT_Container::instance()->get_config();

		ob_start();

		?>
        <input type="hidden" id="gt-name-distribution-map-redirect-last-name"
               value="<?= filter_input( INPUT_GET, 'gt-last-name' ) ?>">

        <h2>Distribution Map &amp; Details
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-name-distribution-map" title="Show Help">?
            </button>
        </h2>
        <div id="gt-help-name-distribution-map"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>
                    Start entering a last name or a city you want to find the distribution for and select one from the
                    autocomplete list or select one of the regions.
                    Only one type of search can be displayed at a time, the displayed being highlighted in blue.
                </p>
                <p>
                    All searches ignore diacritics (so for example searching for Novak will show Novák as a result too)
                    and city search supports German names of cities (eg. entering Pilsen will show Plzeň in the
                    results).
                </p>
                <h5 class="font-weight-bold">Details</h5>
                <p>
                    After selecting a city / name from the autocomplete a list with details will appear bellow.
                </p>
                <p>
                    For names, the list shows number of people with given name in each region.
                    After clicking on the region, it also displays number of people in some of the larger cities located
                    in that region.
                    You can click on the printer icon next to the name to print the distribution info.
                </p>
                <p>
                    For cities, details will show the region that city belongs to, its German name,
                    links to more information here on CATV website and wikipedia and
                    5 most popular first and last names in that city.
                    For regions, details will show links to more information here on CATV website and wikipedia
                    and bigger cities in that region and links to their details.
                </p>
                <h5 class="font-weight-bold">Map</h5>
                <p>
                    For first and last names, you can choose between a region map and a city map -
                    the region map shows a heat map of selected for each region, the city map shows every city with
                    given name on an interactive map.
                    For cities only the city map is available, showing a marker with the selected city.
                    For Regions only the region map is available, showing the selected region's borders.
                </p>
                <h5 class="font-weight-bold">Note</h5>
                <p>
                    The data used is from Czech Republic's <a href="https://www.mvcr.cz/mvcren/" class="gt-link-ext"
                                                              target="_blank"> Ministry of the Interior</a>
                    and may not be up to date and completely accurate. Please also note that only larger cities are
                    supported -
                    if you are looking for a translation of a Czech city name to / from historical German names, try our
                    <a href="<?= $config->PAGE_GERMAN_TERMINOLOGY ?>" class="gt-link-ext" target="_blank"> German City /
                        Village Name
                        Translation</a>.
                </p>
            </div>
        </div>
		<?php

		if ( strlen( $content ) !== 0 ) {
			echo '
            <div class ="row"> 
            <div class ="col-12 text-justify">
            ' . $content . '
            </div>
            </div>';
		}

		?>
        <div class="row" id="gt-name-distribution-map-inputs">
            <div class="input-group mb-3">
                <input type="search" id="gt-name-distribution-map-ln-input"
                       data-type="<?= GT_Autocomplete_Type::NAME_DISTRIBUTION_LN ?>"
                       class="gt-name-distribution-map-input gt-autocomplete form-control" placeholder="Last name"
                       aria-label="Last name" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button id="clean_name" class="btn btn-outline-secondary" type="button">Clean</button>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="search" id="gt-name-distribution-map-mep-input"
                       data-type="<?= GT_Autocomplete_Type::NAME_DISTRIBUTION_MEP ?>"
                       class="gt-name-distribution-map-input gt-autocomplete form-control" placeholder="City"
                       aria-label="City" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button id="clean_city" class="btn btn-outline-secondary" type="button">Clean</button>
                </div>
            </div>

            <div class="input-group">
                <select id="gt-name-distribution-map-region-input" class="gt-name-distribution-map-input custom-select">
                    <option selected value="0">Region...</option>
                    <option value="1">South Bohemian Region</option>
                    <option value="2">South Moravia Region</option>
                    <option value="3">Karlovy Vary Region</option>
                    <option value="4">Hradec Kralove Region</option>
                    <option value="5">Liberec Region</option>
                    <option value="6">Moravian-Silesian Region</option>
                    <option value="7">Olomouc Region</option>
                    <option value="8">Pardubice Region</option>
                    <option value="9">Pilsen Region</option>
                    <option value="10">Central Bohemia Region</option>
                    <option value="11">Usti Region</option>
                    <option value="12">Vysocina Region</option>
                    <option value="13">Zlin Region</option>
                    <option value="14">City of Prague</option>
                </select>
                <div class="input-group-append">
                    <button id="gt-name-distribution-map-region-clean-btn" class="btn btn-outline-secondary"
                            type="button">Clean
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-5">
                <h3 id="gt-name-distribution-map-status"></h3>
                <div id="gt-name-distribution-map-display">
                </div>
            </div>
            <div class="col-12 col-md-7">
                <div class="float-right mt-5">
                    <label class="radio-inline"><input type="radio" name="map-type"
                                                       id="gt-name-distribution-map-region-checkbox"
                                                       checked>Regions</label>
                    <label class="radio-inline"><input type="radio" name="map-type"
                                                       id="gt-name-distribution-map-mep-checkbox">City</label>
                </div>
                <h3>
                    Map
                </h3>
                <div id="gt-name-distribution-map-nmap" class="w-100">
                </div>
                <div id="gt-name-distribution-map-gmap" class="d-none w-100">
                </div>
            </div>
        </div>
		<?php

		return ob_get_clean();
	}

	//endregion
}
