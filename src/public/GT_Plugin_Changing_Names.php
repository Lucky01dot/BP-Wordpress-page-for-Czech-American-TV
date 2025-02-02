<?php

/**
 * Class for changing names functionality.
 */
class GT_Plugin_Changing_Names {

	/**
	 * @var GT_Plugin_Public Instance of the public plugin class.
	 */
	private GT_Plugin_Public $plugin_public;

	/**
	 * @var array Array of changing names shortcodes.
	 */
	public array $shortcodes;

	/**
	 * GT_Plugin_Changing_Names constructor.
	 *
	 * @param GT_Plugin_Public $plugin_public The instance of the public plugin class.
	 */
	public function __construct( GT_Plugin_Public $plugin_public ) {
		$this->plugin_public = $plugin_public;

		$this->shortcodes = [
			'catv_gt_transcription_fname',
			'catv_gt_transcription_lname',
			'catv_gt_femvar'
		];
	}

	/**
	 * Register the plugin within Wordpress
	 */
	public function register() {
		// ------- Include JS and CSS files -------
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// ------- Shortcodes -------

		add_shortcode( 'catv_gt_transcription_fname', array( $this, 'shortcode_fn_translation' ) );
		add_shortcode( 'catv_gt_transcription_lname', array( $this, 'shortcode_ln_transcription' ) );
		add_shortcode( 'catv_gt_femvar', array( $this, 'shortcode_female_variant' ) );

		// ------- Actions -------

		// First name translations from english to czech
		add_action( 'wp_ajax_gt_changing_names', array(
			$this,
			'action_changing_names'
		) );
		add_action( 'wp_ajax_nopriv_gt_changing_names', array(
			$this,
			'action_changing_names'
		) );
	}


	//region ------- Hooks -------

	/**
	 * Enqueue scripts & CSS.
	 */
	public function enqueue_scripts() {
		if ( $this->plugin_public->plugin->shortcode_check( $this->shortcodes ) ) {
			wp_enqueue_script( 'gt_js_changing_names', $this->plugin_public->plugin->plugin_dir_url() . 'public/js/changing_names.js', array(
				'jquery',
				'gt_js_request_types'
			) );
		}
	}

	//endregion


	//region ------- Autocompletes -------

	/**
	 * Handle czech male last name for female variant autocomplete.
	 *
	 * @param string $value
	 *
	 * @return array|null
	 */
	public function autocomplete_female_variant( string $value ): ?array {
		$container = GT_Container::instance();
		$ln_dao    = $container->get_ln_dao();

		return $ln_dao->get_male_names_by_prefix( $value );
	}

	/**
	 * Handle english first name for en to cz translations autocomplete.
	 *
	 * @param string $value
	 *
	 * @return array|null
	 */
	public function autocomplete_fn_translation_en_cz( string $value ): ?array {
		$container          = GT_Container::instance();
		$fn_translation_dao = $container->get_fn_translation_dao();

		return $fn_translation_dao->get_names_en_by_prefix( $value );
	}

	/**
	 * Handle czech first name for cz to en translations autocomplete.
	 *
	 * @param string $value
	 *
	 * @return array|null
	 */
	public function autocomplete_fn_translation_cz_en( string $value ): ?array {
		$container          = GT_Container::instance();
		$fn_translation_dao = $container->get_fn_translation_dao();

		return $fn_translation_dao->get_names_cz_by_prefix( $value );
	}

	//endregion


	//region ------- Actions -------

	/**
	 * AJAX call handler for last name transcriptions, first name translations and last name female variants.
	 *
	 * It requires 1 parameter:
	 *     - 'name_en' = Name in english.
	 *
	 * The request result is array of objects {'name'} - resulting names.
	 */
	public function action_changing_names() {
		check_ajax_referer( GT_PREFIX . 'nonce' );

		$type = filter_input( INPUT_POST, 'type' );
		$name = mb_strtoupper( filter_input( INPUT_POST, 'name' ) );

		$container = GT_Container::instance();
		$result    = null;

		switch ( $type ) {
			case GT_Changing_Names_Type::FN_TRANSLATION_EN_CZ:
				$fn_translations_dao = $container->get_fn_translation_dao();
				$translations        = $fn_translations_dao->get_translations_en_to_cz( $name );

				$result = $translations;
				break;
			case GT_Changing_Names_Type::FN_TRANSLATION_CZ_EN:
				$fn_translations_dao = $container->get_fn_translation_dao();
				$translations        = $fn_translations_dao->get_translations_cz_to_en( $name);

				$result = $translations;
				break;
			case GT_Changing_Names_Type::LN_TRANSCRIPTION:
				// transcriptions
				$service = $container->get_transcription_service();
				$result  = $service->get_ln_transcriptions( $name );
				break;
			case GT_Changing_Names_Type::FEMALE_VARIANT;
				// female variant
				$female_variant_service = $container->get_female_variant_service();
				$result                 = $female_variant_service->get_last_name_female_variants( $name );
				break;
			default:
				$this->plugin_public->plugin->error( GT_Ajax_Error::INVALID_REQUEST_TYPE );
		}

		$this->plugin_public->plugin->success( $result );
	}

	//endregion


	//region ------- Shortcodes -------

	/**
	 * Shortcode function for first name translations.
	 *
	 * @param string $attrs Attributes of the shortcode, unused.
	 * @param string|null $content Content inside the shortcode.
	 *
	 * @return false|string
	 */
	public function shortcode_fn_translation( string $attrs, $content = null ) {
		ob_start();

		?>
        <h2>First Name Translation
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-changing-names-fn-translation" title="Show Help">?
            </button>
        </h2>
        <div id="gt-help-changing-names-fn-translation"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>
                    Enter a first name you want to find the Czech variant for and hit send.
                    The results will show all the possible variants and a relative percentage
                    for given results compared to all of them.
                    You can also input a name from your clipboard by pressing
                    <kbd>CTRL</kbd> + <kbd>V</kbd> on Windows,
                    <kbd>⌘</kbd> + <kbd>V</kbd> on Mac,
                    or long pressing on mobile devices.
                </p>
                <p>
                    You can click on "Copy" to
                    copy the selected name into your clipboard.
                </p>
                <p>
                    You can click on "Print" to print the results.
                </p>
                <h5 class="font-weight-bold">Note</h5>
                <p>
                    The data used is from Czech Republic's <a href="https://www.mvcr.cz/mvcren/" class="gt-link-ext"
                                                              target="_blank"> Ministry of the Interior</a>
                    and may not be up to date and completely accurate. Please also note that only names with more than
                    10 occurrences in Czech Republic can be found.
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
        <form class="gt-changing-names-form" data-type="fn-translation-en-cz" action="javascript:void(0);">
            <div class="row">
                <div class="col-12 col-md-4 mt-2">
                    <label for="gt-changing-names-fn-translation-en-cz-input">English First Name:</label>
                    <input type="search" id="gt-changing-names-fn-translation-en-cz-input"
                           data-type="changing-names-fn-translation-en-cz"
                           class="gt-changing-names-fn-translation-en-cz-input gt-changing-names-autocomplete gt-autocomplete form-control form-control-lg"
                           placeholder="Enter a first name.">
                </div>
                <div class="col-12 col-md-2 mt-3 mt-md-5">
                    <button id="gt-changing-names-fn-translation-en-cz-btn" type="submit" class="w-100 btn btn-primary">
                        Send
                    </button>
                </div>
                <div class="col-10 col-md-5 mt-2">
                    <label for="gt-changing-names-fn-translation-en-cz-output">Czech First Name:</label>
                    <table id="gt-changing-names-fn-translation-en-cz-output"
                            class="gt-changing-names-fn-translation-en-cz-output wrapped-output-table gt-changing-names-result">
                        <tr><td>No name entered.</td></tr>
                    </table>
                </div>
                <div class="col-2 col-md-1 mt-5 text-center">
<!--                    <a href="javascript:void(0);" class="gt-copy-btn"-->
<!--                       data-target="gt-changing-names-fn-translation-en-cz-output">Copy</a>-->
                    <a href="javascript:void(0);" class="gt-print-btn ml-1"
                       data-target="gt-changing-names-fn-translation-en-cz-print">Print</a>
                </div>
            </div>
        </form>

        <h4>Vice versa</h4>

        <form class="gt-changing-names-form" data-type="fn-translation-cz-en" action="javascript:void(0);">
            <div class="row">
                <div class="col-12 col-md-4 mt-2">
                    <label for="gt-changing-names-fn-translation-cz-en-input">Czech First Name:</label>
                    <input type="search" id="gt-changing-names-fn-translation-cz-en-input"
                           data-type="changing-names-fn-translation-cz-en"
                           class="gt-changing-names-fn-translation-cz-en-input gt-changing-names-autocomplete gt-autocomplete form-control form-control-lg"
                           placeholder="Enter a first name.">
                </div>
                <div class="col-12 col-md-2 mt-3 mt-md-5">
                    <button id="gt-changing-names-fn-translation-cz-en-btn" type="submit" class="w-100 btn btn-primary">
                        Send
                    </button>
                </div>
                <div class="col-10 col-md-5 mt-2">
                    <label for="gt-changing-names-fn-translation-cz-en-output">English First Name:</label>
                    <table id="gt-changing-names-fn-translation-cz-en-output"
                            class="gt-changing-names-fn-translation-cz-en-output wrapped-output-table gt-changing-names-result">
                        <tr><td>No name entered.</td></tr>
                    </table>
                </div>
                <div class="col-2 col-md-1 mt-5 text-center">
<!--                    <a href="javascript:void(0);" class="gt-copy-btn"-->
<!--                       data-target="gt-changing-names-fn-translation-cz-en-output">Copy</a>-->
                    <a href="javascript:void(0);" class="gt-print-btn ml-1"
                       data-target="gt-changing-names-fn-translation-cz-en-print">Print</a>
                </div>
            </div>
        </form>
		<?php

		return ob_get_clean();
	}

	/**
	 * Shortcode function for last name transcription.
	 *
	 * @param string $attrs Attributes of the shortcode, unused.
	 * @param string|null $content Content inside the shortcode.
	 *
	 * @return false|string
	 */
	public function shortcode_ln_transcription( string $attrs, $content = null ) {
		$config = GT_Container::instance()->get_config();

		ob_start();
		?>
        <h2 class="gt-print">Last Name Changes
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold  d-print-none"
                    data-target="gt-help-changing-names-ln-transcription" title="Show Help">?
            </button>
        </h2>
        <div id="gt-help-changing-names-ln-transcription"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>
                    Enter a last name you want to find the Czech variant for and hit send.
                    The results will show all the possible variants and a relative percentage
                    for given results compared to all of them.
                    You can also input a name from your clipboard by pressing
                    <kbd>CTRL</kbd> + <kbd>V</kbd> on Windows,
                    <kbd>⌘</kbd> + <kbd>V</kbd> on Mac,
                    or long pressing on mobile devices.
                </p>
                <p>
                    You can click on "Map" next to the results to automatically search for selected name on the map over
                    at our
                    <a href="<?= $config->PAGE_NAMES_MAP ?>" class="gt-link-ext" target="_blank">Genealogy Map</a>
                    page, and click on "Copy" to
                    copy the selected name into your clipboard. You can also find female variant of selected last name
                    by clicking on "Find female variant".
                    <!--                    If you check the checkbox bellow, the female variant will be automatically found on every last name search.-->
                </p>
                <p>
                    You can click on "Print" to print the results.
                </p>
                <h5 class="font-weight-bold">Note</h5>
                <p>
                    The data used is from Czech Republic's <a href="https://www.mvcr.cz/mvcren/" class="gt-link-ext"
                                                              target="_blank"> Ministry of the Interior</a>
                    and may not be up to date and completely accurate. Please also note that only names with more than
                    10 occurrences in Czech Republic can be found.
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

        <form class="gt-changing-names-form" data-type="ln-transcription" action="javascript:void(0);">
            <div class="row">
                <div class="col-12 col-md-4 mt-2">
                    <label for="gt-changing-names-ln-transcription-input">English Last Name:</label>
                    <input type="search" id="gt-changing-names-ln-transcription-input"
                           class="gt-changing-names-ln-transcription-input form-control form-control-lg"
                           placeholder="Enter a last name.">
                </div>
                <div class="col-12 col-md-2 mt-3 mt-md-5">
                    <button id="gt-changing-names-ln-transcription-btn" type="submit" class="w-100 btn btn-primary">
                        Send
                    </button>
                </div>
                <div class="col-10 col-md-5 mt-2">
                    <label for="gt-changing-names-ln-transcription-output">Czech Last Name:</label>
                    <table id="gt-changing-names-ln-transcription-output"
                            class="gt-changing-names-ln-transcription-output wrapped-output-table gt-changing-names-result gt-table">
                        <tr><td>No name entered.</td></tr>
                    </table>
                </div>
                <div class="col-2 col-md-1 mt-4 text-center">
                    <a href="javascript:void(0);" class="gt-map-info-btn ml-1"
                       data-target="gt-changing-names-ln-transcription-output"
                       data-type="lname">Map</a>
                    <br>
<!--                    <a href="javascript:void(0);" class="gt-copy-btn"-->
<!--                       data-target="gt-changing-names-ln-transcription-output">Copy</a>-->
                    <br>
                    <a href="javascript:void(0);" class="gt-print-btn ml-1"
                       data-target="gt-changing-names-ln-transcription-print">Print</a>
                </div>
            </div>
        </form>

        <div class="row mt-md-4">
            <div class="col-12 col-md-12 text-center">
                <a href="#gt-changing-names-female-variant-link" id="gt-changing-names-mtf">Find female variant for
                    selected last
                    name (below).</a> <br>
                <!--                <label for="gt-changing-names-mtf-auto">
											<input type="checkbox" id="gt-changing-names-mtf-auto">   On every search
										</label>   -->
            </div>
        </div>

		<?php
		return ob_get_clean();
	}

	/**
	 * Shortcode function for female last name variant.
	 *
	 * @param string $attrs Attributes of the shortcode, unused.
	 * @param string|null $content Content inside the shortcode.
	 *
	 * @return false|string
	 */
	public function shortcode_female_variant( string $attrs, $content = null ) {
		$config = GT_Container::instance()->get_config();

		ob_start();

		?>
        <h2 id="gt-changing-names-female-variant-link">Czech Female Last Names
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-changing-names-female-variant" title="Show Help">?
            </button>
        </h2>
        <div id="gt-help-changing-names-female-variant"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>
                    Enter a Czech male last name you want to find the female variant for and hit send.
                    The results will display all the possible variants and a relative percentage
                    for given result compared to all of them. You can also input a name from your clipboard by pressing
                    <kbd>CTRL</kbd> + <kbd>V</kbd> on Windows,
                    <kbd>⌘</kbd> + <kbd>V</kbd> on Mac,
                    or long pressing on mobile devices.
                </p>
                <p>
                    You can click on "Map" next to any of the results to automatically search for selected name on the
                    map over at our
                    <a href="<?= $config->PAGE_NAMES_MAP ?>" class="gt-link-ext" target="_blank">Genealogy Map</a>
                    page, click on "Copy" to
                    copy the selected name into your clipboard.
                </p>
                <p>
                    You can click on "Print" to print the results.
                </p>
                <h5 class="font-weight-bold">Note</h5>
                <p>
                    The data used is from Czech Republic's <a href="https://www.mvcr.cz/mvcren/" class="gt-link-ext"
                                                              target="_blank"> Ministry of the Interior</a>
                    and may not be up to date and completely accurate. Please also note that only names with more than
                    10 occurrences in Czech Republic can be found.
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
        <form class="gt-changing-names-form" data-type="female-variant" action="javascript:void(0);">
            <div class="row">
                <div class="col-12 col-md-4 mt-2">
                    <label for="gt-changing-names-female-variant-input">Male Czech Last Name:</label>
                    <input type="search" id="gt-changing-names-female-variant-input"
                           data-type="changing-names-female-variant"
                           class="gt-changing-names-female-variant-input gt-changing-names-autocomplete gt-autocomplete form-control form-control-lg"
                           placeholder="Enter a male last name.">
                </div>
                <div class="col-12 col-md-2 mt-3 mt-md-5">
                    <button id="gt-changing-names-female-variant-btn" type="submit" class="w-100 btn btn-primary">Send
                    </button>
                </div>
                <div class="col-10 col-md-5 mt-2">
                    <label for="gt-changing-names-female-variant-output">Female Czech Last Name:</label>
                    <table id="gt-changing-names-female-variant-output"
                            class="czech-female-last-names-output wrapped-output-table gt-changing-names-result">
                        <tr><td>No name entered.</td></tr>
                    </table>
                </div>
                <div class="col-2 col-md-1 mt-4 text-center">
                    <a href="javascript:void(0);" class="gt-map-info-btn ml-1"
                       data-target="gt-changing-names-female-variant-output"
                       data-type="lname">Map</a>
                    <br>
<!--                    <a href="javascript:void(0);" class="gt-copy-btn"-->
<!--                       data-target="gt-changing-names-female-variant-output">Copy</a>-->
                    <br>
                    <a href="javascript:void(0);" class="gt-print-btn ml-1"
                       data-target="gt-changing-names-female-variant-print">Print</a>
                </div>
            </div>
        </form>
		<?php

		return ob_get_clean();
	}

	//endregion
}
