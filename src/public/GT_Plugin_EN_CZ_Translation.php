<?php

class GT_Plugin_EN_CZ_Translation {

    private GT_Plugin_Public $plugin_public;
    public array $shortcodes;

    public function __construct(GT_Plugin_Public $plugin_public) {
        $this->plugin_public = $plugin_public;
        $this->shortcodes = [
            'catv_gt_cz_en_translation',
        ];
    }

    public function register() {
        // Enqueue scripts if shortcode is used
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Register shortcode
        add_shortcode('catv_gt_cz_en_translation', array( $this, 'render_shortcode'));

        // AJAX action for translation
        add_action('wp_ajax_gt_en_cz_translation', array($this,'handle_ajax_translation'));
        add_action('wp_ajax_nopriv_gt_en_cz_translation', array($this, 'handle_ajax_translation'));
    }

    public function enqueue_scripts(): void {
        if ($this->plugin_public->plugin->shortcode_check($this->shortcodes)) {
            wp_enqueue_script(
                'gt_js_cz_en_translation',
                $this->plugin_public->plugin->plugin_dir_url() . 'public/js/en_cz_translation.js',
                array('jquery', 'gt_js_request_types')
            );
        }
    }

    public function handle_ajax_translation(): void {
        check_ajax_referer(GT_PREFIX . 'nonce');

        $input_value = mb_strtoupper(trim(filter_input(INPUT_POST, 'word_cz', FILTER_SANITIZE_STRING)));
        $translations = [];

        if (!empty($input_value)) {
            $container = GT_Container::instance();
            $cz_en_translation_dao = $container->get_cz_en_transtation_dao();
            $translations = $cz_en_translation_dao->get_translations_cz_to_en($input_value);
        }

        $this->plugin_public->plugin->success($translations);
    }

    public function render_shortcode($content = null): string {
        ob_start();
        ?>
        <h2>Czech to English Translation
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-cz-en-translation" title="Show Help">?
            </button>
        </h2>
        <div id="gt-help-cz-en-translation"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>Enter a Czech word and click "Send" to get its English translation.</p>
                <p>You can copy or print the results for further use.</p>
            </div>
        </div>
        <form class="gt-word-translation-form" data-type="cz-en-translation" action="javascript:void(0);">
            <div class="row">
                <!-- Input field for Czech word -->
                <div class="col-12 col-md-4 mt-2">
                    <label for="gt-cz-en-translation-input">Czech Word:</label>
                    <input type="search" id="gt-cz-en-translation-input"
                           data-type ="cz-en-translation"
                           class="gt-cz-en-translation-input cz-en-translation-autocomplete gt-autocomplete form-control form-control-lg"
                           placeholder="Enter a word">
                </div>

                <!-- Submit button -->
                <div class="col-12 col-md-2 mt-3 mt-md-5">
                    <button id="cz-en-translation-submit" type="submit" class="w-100 btn btn-primary">
                        Send
                    </button>
                </div>

                <!-- Output area for English translation -->
                <div class="col-10 col-md-5 mt-2">
                    <label for="cz-en-translation-output">English Translation:</label>
                    <table id="cz-en-translation-output" class="cz-en-translation-output gt-cz-en-translation-result wrapped-output-table">
                        <tr><td>No word entered.</td></tr>
                    </table>
                </div>

                <!-- Print button -->
                <div class="col-2 col-md-1 mt-5 text-center">
                    <a href="javascript:void(0);" class="gt-print-btn" data-target="cz-en-translation-output">Print</a>
                </div>
            </div>
        </form>

        <?php
        return ob_get_clean();
    }

    public function autocomplete_cz_en_translation( string $value ): ?array
    {
        $container          = GT_Container::instance();
        $cz_en_translation_dao = $container->get_cz_en_transtation_dao();

        return $cz_en_translation_dao->get_word_cz_by_prefix( $value );
    }
}
