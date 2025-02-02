<?php

/**
 * Class for behind the name functionality.
 */
class GT_Plugin_Behind_The_Name {

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
			'catv_gt_translation_lname',
			'catv_gt_translation_diminutive',
		];
	}

	/**
	 * Register the plugin within Wordpress
	 */
	public function register() {
		// ------- Include JS and CSS files -------
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// ------- Shortcodes -------

		add_shortcode( 'catv_gt_translation_lname', array( $this, 'shortcode_ln_explanation' ) );
		add_shortcode( 'catv_gt_translation_diminutive', array( $this, 'shortcode_fn_diminutive' ) );

		// ------- Actions -------

		// First name diminutives
		add_action( 'wp_ajax_gt_behind_the_name_fn_diminutives', array( $this, 'action_fn_diminutives' ) );
		add_action( 'wp_ajax_nopriv_gt_behind_the_name_fn_diminutives', array( $this, 'action_fn_diminutives' ) );

		// Last name explanations
		add_action( 'wp_ajax_gt_behind_the_name_ln_explanations', array( $this, 'action_ln_explanations' ) );
		add_action( 'wp_ajax_nopriv_gt_behind_the_name_ln_explanations', array( $this, 'action_ln_explanations' ) );
	}


	//region ------- Hooks -------

	/**
	 * Enqueue scripts & CSS.
	 */
	public function enqueue_scripts() {
		if ( $this->plugin_public->plugin->shortcode_check( $this->shortcodes ) ) {
			wp_enqueue_script( 'gt_js_behind_the_name', $this->plugin_public->plugin->plugin_dir_url() . 'public/js/behind_the_name.js', array(
				'jquery',
				'gt_js_public',
				'gt_js_autocomplete',
				'gt_js_selectors',
				'gt_js_request_types'
			) );
		}
	}

	//endregion


	//region ------- Autocompletes -------

	/**
	 * Handle first name for diminutives autocomplete.
	 *
	 * @param string $value
	 *
	 * @return array|null
	 */
	public function autocomplete_fn_diminutive( string $value ): ?array {
		$container         = GT_Container::instance();
		$fn_diminutive_dao = $container->get_fn_diminutive_dao();

		return $fn_diminutive_dao->get_names_by_prefix( $value );
	}

	/**
	 * Handle last name for explanations autocomplete.
	 *
	 * @param string $value
	 *
	 * @return array|null
	 */
	public function autocomplete_ln_explanation( string $value ): ?array {
		$container          = GT_Container::instance();
		$ln_explanation_dao = $container->get_ln_explanation_dao();

		return $ln_explanation_dao->get_names_by_prefix( $value );
	}

	//endregion


	//region ------- Actions -------

	/**
	 * AJAX call handler for first name diminutives.
	 *
	 * It requires 1 parameter:
	 *     - 'name' = The first name.
	 *
	 * The request result is array of resulting diminutives.
	 */
	public function action_fn_diminutives() {
		check_ajax_referer( GT_PREFIX . 'nonce' );

		$name = filter_input( INPUT_POST, 'name' );

		$container          = GT_Container::instance();
		$fn_diminutives_dao = $container->get_fn_diminutive_dao();

		$results = $fn_diminutives_dao->get_diminutives_by_name( $name );

		$this->plugin_public->plugin->success( array_column( $results, 'diminutive' ) );
	}

	/**
	 * AJAX call handler for last name explanations.
	 *
	 * It requires 1 parameter:
	 *     - 'name' = The last name.
	 *
	 * The request result is array of resulting explanations.
	 */
	public function action_ln_explanations() {
		check_ajax_referer( GT_PREFIX . 'nonce' );

		$name = filter_input( INPUT_POST, 'name' );

		$container          = GT_Container::instance();
		$fn_explanation_dao = $container->get_ln_explanation_dao();

		$results = $fn_explanation_dao->get_explanations_by_name( $name );

		$this->plugin_public->plugin->success( array_column( $results, 'explanation' ) );
	}

	//endregion


	//region ------- Shortcodes -------

	/**
	 * Shortcode function for last name explanation.
	 *
	 * @param string $attrs Attributes of the shortcode, unused.
	 * @param string|null $content Content inside the shortcode.
	 *
	 * @return false|string
	 */
	public function shortcode_ln_explanation( string $attrs, $content = null ) {
		ob_start();

		?>
        <h2>Last Name Meanings
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-behind-the-name-ln-explanation" title="Show Help">?
            </button>
        </h2>

        <div id="gt-help-behind-the-name-ln-explanation"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>
                    Enter a Czech last name you want to find the meaning of and select one from the autocomplete.
                    The results will display origin / meaning of the selected last name.
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
                    You can click on "Print" to print the name meaning.
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
        <div class="row">
            <div class="col-12 col-md-4 mt-4">
                <div class="row">
                    <div class="col-12">
                        <label for="gt-behind-the-name-ln-explanation-input">Czech Last Name:</label>
                        <input type="search" id="gt-behind-the-name-ln-explanation-input"
                               data-type="behind-the-name-ln-explanation"
                               class="gt-name-distribution-map-input gt-autocomplete form-control form-control-lg"
                               placeholder="Enter a last name.">
                    </div>
                    <div class="col-12 mt-4 d-none d-md-inline text-center">
<!--                        <a href="javascript:void(0);" class="gt-copy-btn"-->
<!--                           data-target="gt-behind-the-name-ln-explanation-output">Copy name meaning</a>-->
                        <br>
                        <a href="javascript:void(0);" class="gt-print-btn"
                           data-target="gt-behind-the-name-ln-explanation-print">Print name
                            meaning</a>
                    </div>
                </div>

            </div>
            <div class="col-12 col-md-8 mt-4">
                <label for="gt-behind-the-name-ln-explanation-output">Last Name Meaning:</label>
                <table id="gt-behind-the-name-ln-explanation-output"
                       class="wrapped-output-table">
                    <tr><td>Meaning of the selected last name will be shown here.</td></tr>
                </table>
            </div>
            <div class="col-12 mt-4 d-inline d-md-none text-center">
<!--                <a href="javascript:void(0);" class="gt-copy-btn"-->
<!--                   data-target="gt-behind-the-name-ln-explanation-output">Copy name meaning</a>-->
                <br>
                <a href="javascript:void(0);" class="gt-print-btn"
                   data-target="gt-behind-the-name-ln-explanation-print">Print name meaning</a>
            </div>
        </div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Shortcode function for diminutives.
	 *
	 * @param string $attrs Attributes of the shortcode, unused.
	 * @param string|null $content Content inside the shortcode.
	 *
	 * @return false|string
	 */
	public function shortcode_fn_diminutive( string $attrs, $content = null ) {
		ob_start();

		?>
        <h2>Diminutive Forms
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-behind-the-name-diminutive" title="Show Help">?
            </button>
        </h2>
        <div id="gt-help-behind-the-name-diminutive"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>
                    Enter a Czech first name or its English translation
                    you want to find the diminutive form for and select one from the autocomplete.
                    The results will display all the possible diminutive forms.
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
                    You can click on "Print" to print all the diminutive forms.
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
        <form class="gt-behind-the-name-fn-diminutive-form" data-type="diminutive" action="javascript:void(0);">
            <div class="row">
                <div class="col-12 col-md-4 mt-2">
                    <label for="gt-behind-the-name-fn-diminutive-input">Czech First Name:</label>
                    <input type="search" id="gt-behind-the-name-fn-diminutive-input"
                           data-type="behind-the-name-fn-diminutive"
                           class="gt-name-distribution-map-input gt-autocomplete form-control form-control-lg"
                           placeholder="Enter a first name.">
                </div>
                <div class="col-10 col-md-6 mt-2">
                    <label for="gt-behind-the-name-fn-diminutive-output">Diminutive Forms:</label>
                    <table id="gt-behind-the-name-fn-diminutive-output"
                           class="wrapped-output-table">
                        <tr><td>Diminutive forms will be shown here.</td></tr>
                    </table>
                </div>
                <div class="col-2 col-md-2 mt-5 text-center">
                    <a href="javascript:void(0);" class="gt-print-btn"
                       data-target="gt-behind-the-name-fn-diminutive-print">Print All</a>
                </div>
            </div>
        </form>
		<?php

		return ob_get_clean();
	}

	//endregion
}
