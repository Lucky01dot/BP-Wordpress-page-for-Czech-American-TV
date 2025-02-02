<?php

/**
 * Class for german terminology functionality.
 */
class GT_Plugin_German_Terminology {

	/**
	 * @var GT_Plugin_Public Instance of the public plugin class.
	 */
	private GT_Plugin_Public $plugin_public;

	/**
	 * @var array Array of changing names shortcodes.
	 */
	public array $shortcodes;

	/**
	 * GT_Plugin_German_Terminology constructor.
	 *
	 * @param GT_Plugin_Public $plugin_public The instance of the public plugin class.
	 */
	public function __construct( GT_Plugin_Public $plugin_public ) {
		$this->plugin_public = $plugin_public;

		$this->shortcodes = [
			'catv_gt_ger_typer',
			'catv_gt_ger_city'
		];
	}

	/**
	 * Register the plugin within Wordpress
	 */
	public function register() {
		// ------- Include JS and CSS files -------
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// ------- Shortcodes -------

		// German shortcodes
		add_shortcode( 'catv_gt_ger_typer', array( $this, 'shortcode_german_handwriting' ) );
		add_shortcode( 'catv_gt_ger_city', array( $this, 'shortcode_german_cities' ) );
	}


	//region ------- Hooks -------

	/**
	 * Enqueue scripts & CSS.
	 */
	public function enqueue_scripts() {
		if ( $this->plugin_public->plugin->shortcode_check( $this->shortcodes ) ) {
			wp_enqueue_style( 'gt_css_fonts', $this->plugin_public->plugin->plugin_dir_url() . 'public/css/fonts.css' );
			wp_enqueue_script( 'gt_js_german_terminology', $this->plugin_public->plugin->plugin_dir_url() . 'public/js/german_terminology.js', array(
				'jquery',
				'gt_js_autocomplete'
			) );
		}
	}

	//endregion


	//region ------- Autocompletes -------

	/**
	 * Handle city name autocomplete.
	 *
	 * @param string $value
	 *
	 * @return array|null
	 */
	public function autocomplete_city( string $value ): ?array {
		$container = GT_Container::instance();
		$city_dao  = $container->get_city_dao();

		return $city_dao->get_city_by_prefix( $value );
	}

	//endregion


	//region ------- Shortcodes -------

	/**
	 * Shortcode function for german handwriting comparison.
	 *
	 * @param string $attrs Attributes of the shortcode, unused.
	 * @param string|null $content Content inside the shortcode.
	 *
	 * @return false|string
	 */
	public function shortcode_german_handwriting( string $attrs, $content = null ) {
		ob_start();

		?>
        <h2>German Handwriting
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-german-terminology-handwriting" title="Show Help">?
            </button>
        </h2>
        <div id="gt-help-german-terminology-handwriting"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>
                    Enter any text into the input field. It will display in the font and letter case selected bellow.
                    If you need to add German-specific characters, you can click on a button with the appropriate
                    letter. Holding <kbd>SHIFT</kbd> on computers allows you to enter uppercase versions of those
                    letters.
                </p>
                <p>
                    You can click on "Copy" next to any field to copy it into your clipboard,
                    click on "Map" next to the parent/city region to display it on map and show more details about it
                    or click on "Print" next to any field to print the results.
                </p>
                <h5 class="font-weight-bold">Note</h5>
                <p>
                    Most of these fonts are usually handwritten and may look a little different depending on the person
                    writing the text.
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
            <div class="col-12 col-md-8 form-group">
                <label for="gt-german-terminology-handwriting-input">Text:</label>
                <input type="text" id="gt-german-terminology-handwriting-input" class="form-control form-control-lg">
            </div>
            <div class="col-12 col-md-4">
                <label>Special characters:</label>
                <div class="row justify-content-between">
                    <button class="btn btn-primary gt-typer-addbtn">ä</button>
                    <button class="btn btn-primary gt-typer-addbtn d-md-none">Ä</button>
                    <button class="btn btn-primary gt-typer-addbtn">ö</button>
                    <button class="btn btn-primary gt-typer-addbtn d-md-none">Ö</button>
                    <button class="btn btn-primary gt-typer-addbtn">ü</button>
                    <button class="btn btn-primary gt-typer-addbtn d-md-none">Ü</button>
                    <button class="btn btn-primary gt-typer-addbtn">ß</button>
                </div>
            </div>
        </div>
        <br>
        <div class="row justify-content-center">
            <div class="col-12  text-justify" id="gt-german-terminology-handwriting-output">
                Text in selected font will appear here.
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md mt-2">
                <label for="gt-german-terminology-handwriting-fontselect">Font:</label>
                <select id="gt-german-terminology-handwriting-fontselect" class="form-control">
                    <option value="inherit">Default</option>
                    <option value="kurrent" class="font-weight-bold">Kurrent</option>
                    <option value="schwabacher" class="font-weight-bold">Schwabacher</option>
                    <option value="latein" class="font-weight-bold">Latin Cursive</option>
                    <option value="amptmann">Amptmann</option>
                    <option value="contgen">Contgen</option>
                    <option value="friedolin">Friedolin</option>
                    <option value="greifswalder">Greifswalder</option>
                    <option value="leipzig">Leipzig</option>
                    <option value="rudelskopf">Rudelskopf</option>

                </select>
            </div>
            <div class="col-10 col-md mt-2">
                <label for="gt-german-terminology-handwriting-caseselect">Case:</label>
                <select id="gt-german-terminology-handwriting-caseselect" class="form-control">
                    <option value="inherit">Default</option>
                    <option value="lowercase">Lowercase</option>
                    <option value="uppercase">Uppercase</option>
                    <option value="capitalize">Capitalize First Letter</option>
                </select>
            </div>
            <div class="col-2 col-md-1 mt-5 text-center">
                <a href="javascript:void(0);" class="gt-print-btn"
                   data-target="gt-german-terminology-handwriting-print">Print</a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 col-md-10 text-center"> Not sure which font to use? <br> Click this button to show the
                entire alphabet in selected font and letter case.
            </div>
            <div class="col-12 col-md-2">
                <button type="button" id="gt-german-terminology-handwriting-show-alphabet-btn" class="btn btn-primary">
                    Show alphabet
                </button>
            </div>
        </div>
        <br>
        <div id="gt-german-terminology-handwriting-alphabetbox" class="row d-none">
			<?php

			$alphabet = "aäbcdefghijklmnoöpqrsßtuüvwxyz";
			for ( $i = 0; $i < mb_strlen( $alphabet ); $i ++ ) {
				echo "<div class='col-2'>" . mb_substr( $alphabet, $i, 1 ) . "</div>";
			}

			?>
        </div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Shortcode function for historic german names of cities.
	 *
	 * @param string $attrs Attributes of the shortcode, unused.
	 * @param string|null $content Content inside the shortcode.
	 *
	 * @return false|string
	 */
	public function shortcode_german_cities( string $attrs, $content = null ) {
		ob_start();

		?>
        <h2>German City Names
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-german-terminology-german-cities" title="Show Help">?
            </button>
        </h2>

        <div id="gt-help-german-terminology-german-cities"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>
                    Start entering a city name in German or Czech you want to find the translation for and select one
                    from the autocomplete list.
                    The list's format is <span class="font-weight-bold">Czech name (German name) - District </span>.
                    After selecting, the Czech translation and the district (in which the city is located) will display
                    bellow.
                </p>
                <h5 class="font-weight-bold">Note</h5>
                <p>
                    The data used is from Wikipedia's List of historical German and Czech names for places in the Czech
                    Republic
                    - you can find the whole list on Czech Wikipedia
                    <a href="https://cs.wikipedia.org/wiki/Seznam_n%C4%9Bmeck%C3%BDch_n%C3%A1zv%C5%AF_obc%C3%AD_a_osad_v_%C4%8Cesku"
                       class="gt-link-ext" target="_blank">here</a>
                    or a less detailed English version
                    <a href="https://en.wikipedia.org/wiki/List_of_historical_German_and_Czech_names_for_places_in_the_Czech_Republic"
                       class="gt-link-ext" target="_blank">here</a>
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
        <div class="row justify-content-center">
            <div class="col-10 col-md-7 mt-2">
                <label for="gt-german-terminology-german-city-input">German Name:</label>
                <input type="search" id="gt-german-terminology-german-city-input" data-type="german-terminology-german-city"
                       class="gt-autocomplete form-control form-control-lg">
            </div>
            <div class="col-2 col-md-1 mt-4 pt-2">
                <a href="javascript:void(0);" class="gt-copy-btn" data-target="gt-german-terminology-german-city-input">Copy</a>
                <a href="javascript:void(0);" class="gt-print-btn ml-1"
                   data-target="gt-german-terminology-german-city-print">Print</a>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-10 col-md-5 mt-2">
                <label for="gt-german-terminology-german-city-cz-output">Czech Name:</label>
                <input type="text" id="gt-german-terminology-german-city-cz-output" class="form-control form-control-lg"
                       readonly="readonly">
            </div>
            <div class="col-2 col-md-1 mt-4 pt-2">
                <a href="javascript:void(0);" class="gt-copy-btn"
                   data-target="gt-german-terminology-german-city-cz-output">Copy</a>
                <a href="javascript:void(0);" class="gt-print-btn ml-1"
                   data-target="gt-german-terminology-german-city-print">Print</a>
            </div>
            <div class="col-10 col-md-5 mt-2">
                <label for="gt-german-terminology-german-city-district-output">District:</label>
                <input type="text" id="gt-german-terminology-german-city-district-output"
                       class="form-control form-control-lg" readonly="readonly">
            </div>
            <div class="col-2 col-md-1 mt-4">
                <a href="javascript:void(0);" class="gt-copy-btn"
                   data-target="gt-german-terminology-german-city-district-output">Copy</a>
                <a href="javascript:void(0);" class="gt-print-btn ml-1"
                   data-target="gt-german-terminology-german-city-print">Print</a>
            </div>
        </div>
        <br>
		<?php

		return ob_get_clean();
	}

	//endregion
}
