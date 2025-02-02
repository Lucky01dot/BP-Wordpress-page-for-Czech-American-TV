<?php

/**
 * Class for tutorial functionality.
 */
class GT_Plugin_Tutorial {

	/**
	 * @var GT_Plugin_Public Instance of the public plugin class.
	 */
	private GT_Plugin_Public $plugin_public;

	/**
	 * @var array Array of changing names shortcodes.
	 */
	public array $shortcodes;

	/**
	 * GT_Plugin_Tutorial constructor.
	 *
	 * @param GT_Plugin_Public $plugin_public The instance of the public plugin class.
	 */
	public function __construct( GT_Plugin_Public $plugin_public ) {
		$this->plugin_public = $plugin_public;

		$this->shortcodes = [
			'catv_gt_tutorial'
		];
	}

	/**
	 * Register the plugin within Wordpress
	 */
	public function register() {
		// ------- Include JS and CSS files -------
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// ------- Shortcodes -------

		// Video tutorial shortcodes
		add_shortcode( 'catv_gt_tutorial', array( $this, 'shortcode_video_tutorial' ) );
	}


	//region ------- Hooks -------

	/**
	 * Enqueue scripts & CSS.
	 */
	public function enqueue_scripts() {
		if ( $this->plugin_public->plugin->shortcode_check( $this->shortcodes ) ) {
			wp_enqueue_script( 'youtube_iframe_api', 'https://www.youtube.com/iframe_api' );
			wp_enqueue_script( 'gt_js_tutorial', $this->plugin_public->plugin->plugin_dir_url() . 'public/js/tutorial.js', array(
				'jquery',
				'youtube_iframe_api'
			) );
		}
	}

	//endregion


	//region ------- Shortcodes -------

	/**
	 * Shortcode function for video tutorial.
	 *
	 * @param string $attrs Attributes of the shortcode, unused.
	 * @param string|null $content Content inside the shortcode.
	 *
	 * @return false|string
	 */
	public function shortcode_video_tutorial( string $attrs, $content = null ) {
		ob_start();

		if ( strlen( $content ) !== 0 ) {
			echo '
            <div class ="row"> 
            <div class ="col-12 text-justify">
            ' . $content . '
            </div>
            </div>';
		}

		?>
        <div class="row mt-4">
            <div class="col">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe id="gt-tutorial" class="embed-responsive-item"
                            src="https://www.youtube.com/embed/GLYass12em8?modestbranding=1&enablejsapi=1&rel=0&origin=<?php echo site_url(); ?>"
                            frameborder="0" allowfullscreen="allowfullscreen"></iframe>
                </div>
            </div>
        </div>
        <div class="row mt-4 mb-4">
            <div class="col-12">In this tutorial you can find help for the following topics. Click on the timestamp to
                rewind the tutorial to that section.
            </div>
            <div class="font-weight-bold col-6">Changing Names</div>
            <div class="col-6"><a href="javascript:void(0);" class="gt-seek-btn" data-time=52>0:52</a></div>
            <div class="font-weight-bold col-6">German "CZECH" Terminology</div>
            <div class="col-6"><a href="javascript:void(0);" class="gt-seek-btn" data-time=92>1:32</a></div>
            <div class="font-weight-bold col-6">German Handwriting</div>
            <div class="col-6"><a href="javascript:void(0);" class="gt-seek-btn" data-time=143>2:23</a></div>
            <div class="font-weight-bold col-6">Genealogy Map</div>
            <div class="col-6"><a href="javascript:void(0);" class="gt-seek-btn" data-time=244>4:04</a></div>
            <div class="font-weight-bold col-6">Displaying Help</div>
            <div class="col-6"><a href="javascript:void(0);" class="gt-seek-btn" data-time=332>5:32</a></div>
            <div class="font-weight-bold col-6">Copying & Printing</div>
            <div class="col-6"><a href="javascript:void(0);" class="gt-seek-btn" data-time=348>5:48</a></div>
        </div>
		<?php

		return ob_get_clean();
	}

	//endregion
}
