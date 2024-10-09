<?php

	/**
	 * Class GT_Plugin_Public_New is for the public functionality of this plugin.
	 */
	class GT_Plugin_Public {

		/**
		 * @var GT_Plugin Instance of the core plugin class.
		 */
		public GT_Plugin $plugin;

		/**
		 * @var GT_Plugin_Changing_Names Instance of changing names plugin class.
		 */
		public GT_Plugin_Changing_Names $plugin_changing_names;

		/**
		 * @var GT_Plugin_Behind_The_Name Instance of behind the name plugin class.
		 */
		public GT_Plugin_Behind_The_Name $plugin_behind_the_name;

		/**
		 * @var GT_Plugin_German_Terminology Instance of german terminology plugin class.
		 */
		public GT_Plugin_German_Terminology $plugin_german_terminology;

		/**
		 * @var GT_Plugin_Name_Distribution Instance of name distribution plugin class.
		 */
		public GT_Plugin_Name_Distribution $plugin_name_distribution;

		/**
		 * @var GT_Plugin_Tutorial Instance of german terminology plugin class.
		 */
		public GT_Plugin_Tutorial $plugin_tutorial;

		/**
		 * @var array Array of this public plugin shortcodes.
		 */
		public array $shortcodes;

		/**
		 * GT_Plugin_Public constructor.
		 *
		 * @param GT_Plugin $plugin The instance of the core plugin class.
		 */
		public function __construct( GT_Plugin $plugin ) {
			$this->plugin = $plugin;

			$this->plugin_changing_names     = new GT_Plugin_Changing_Names( $this );
			$this->plugin_behind_the_name    = new GT_Plugin_Behind_The_Name( $this );
			$this->plugin_german_terminology = new GT_Plugin_German_Terminology( $this );
			$this->plugin_name_distribution  = new GT_Plugin_Name_Distribution( $this );
			$this->plugin_tutorial           = new GT_Plugin_Tutorial( $this );

			$this->shortcodes = [
				'catv_gt_text'
			];
		}

		/**
		 * Register the plugin within Wordpress
		 */
		public function register() {
			// ------- Include JS and CSS files -------
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// ------- Shortcodes -------

			add_shortcode( 'catv_gt_text', array( $this, 'shortcode_text' ) );

			// ----------- Init dependencies -----------
			$this->plugin_changing_names->register();
			$this->plugin_behind_the_name->register();
			$this->plugin_german_terminology->register();
			$this->plugin_name_distribution->register();
			$this->plugin_tutorial->register();
		}


		//region ------- Wordpress hooks -------

		/**
		 * Enqueue scripts & CSS.
		 */
		public function enqueue_scripts() {
			$other = $this->plugin->shortcode_check( $this->shortcodes );

			// check if sub plugins need to enqueue their scripts and css
			$changing_names     = $this->plugin->shortcode_check( $this->plugin_changing_names->shortcodes );
			$behind_the_name    = $this->plugin->shortcode_check( $this->plugin_behind_the_name->shortcodes );
			$german_terminology = $this->plugin->shortcode_check( $this->plugin_german_terminology->shortcodes );
			$name_distribution  = $this->plugin->shortcode_check( $this->plugin_name_distribution->shortcodes );
			$tutorial           = $this->plugin->shortcode_check( $this->plugin_tutorial->shortcodes );

			// check if any of the common scripts are needed
			if ( $other
			     || $changing_names
			     || $behind_the_name
			     || $german_terminology
			     || $name_distribution
			     || $tutorial ) {

				// enqueue the common scripts (used by all)

				// The main public script
				wp_enqueue_script( 'gt_js_public', $this->plugin->plugin_dir_url() . 'public/js/public.js', array(
					'jquery',
					'gt_js_request_types'
				) );
				// Add ajax information via object reference to 'gt_js_public' (injected variables start with double underscore)
				wp_localize_script( 'gt_js_public', '__ajax_obj', array(
					'url'   => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( GT_PREFIX . 'nonce' )
				) );
				wp_localize_script( 'gt_js_public', '__wp_vars', array(
					'logo_uri'  => $this->plugin->plugin_dir_url() . "images/logo.png",
					'print_uri' => $this->plugin->plugin_dir_url() . "images/print.png"
				) );

				wp_enqueue_script( 'gt_js_autocomplete', $this->plugin->plugin_dir_url() . 'public/common/js/autocomplete.js', array( 'jquery-ui-autocomplete' ) );

				$wp_scripts = wp_scripts();
				// current jquery ui CSS
				wp_enqueue_style( 'gt_css_jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/' . $wp_scripts->registered['jquery-ui-core']->ver . '/themes/smoothness/jquery-ui.css' );

				wp_enqueue_style( 'gt_css_public', $this->plugin->plugin_dir_url() . 'public/css/public.css' );
				wp_enqueue_style( 'gt_css_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' );
			}
		}

		//endregion


		//region ------- Shortcodes -------

		/**
		 * Shortcode function for general text to insert between other parts.
		 *
		 * @param string $attrs Attributes of the shortcode, unused.
		 * @param string|null $content Content inside the shortcode.
		 *
		 * @return string
		 */
		public function shortcode_text( string $attrs, $content = null ): string {
			if ( strlen( $content ) !== 0 ) {
				return '
            <div class ="row"> 
                <div class ="col-12 text-justify">
                    ' . $content . '
                </div>
            </div>';
			}

			return "";
		}

		//endregion
	}