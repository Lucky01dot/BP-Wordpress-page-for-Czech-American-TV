<?php

class GT_Plugin_EN_CZ_Translation {

    private GT_Plugin_Public $plugin_public;
    public array $shortcodes;

    public function __construct(GT_Plugin_Public $plugin_public) {
        $this->plugin_public = $plugin_public;
        $this->shortcodes = [
            'catv_gt_cz_en_translation',
            'catv_gt_de_en_translation',
            'catv_gt_la_en_translation'
        ];
    }

    public function register() {
        // Enqueue scripts if shortcode is used
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

        // Register shortcode
        add_shortcode('catv_gt_cz_en_translation', array( $this, 'render_shortcode_cz_aj'));
        add_shortcode('catv_gt_de_en_translation', array( $this, 'render_shortcode_de_en'));
        add_shortcode('catv_gt_la_en_translation', array( $this, 'render_shortcode_la_en'));


        // AJAX action for translation
        add_action('wp_ajax_gt_en_cz_translation', array($this,'handle_ajax_translation'));
        add_action('wp_ajax_nopriv_gt_en_cz_translation', array($this, 'handle_ajax_translation'));

        // AJAX action for translation
        add_action('wp_ajax_gt_la_cz_translation', array($this,'handle_ajax_translation_la_cz'));
        add_action('wp_ajax_nopriv_gt_la_cz_translation', array($this, 'handle_ajax_translation_la_cz'));

        add_action('wp_ajax_gt_la_cz_en_translation', array($this,'handle_ajax_translation_la_cz_en'));
        add_action('wp_ajax_nopriv_gt_la_cz_en_translation', array($this, 'handle_ajax_translation_la_cz_en'));

        // AJAX action for translation (DE → EN)
        add_action('wp_ajax_gt_de_en_translation', array($this, 'handle_ajax_translation_de_en'));
        add_action('wp_ajax_nopriv_gt_de_en_translation', array($this, 'handle_ajax_translation_de_en'));

        // AJAX action for autocomplete (DE → EN)
        add_action('wp_ajax_gt_de_en_autocomplete', array($this, 'handle_ajax_autocomplete_de_en'));
        add_action('wp_ajax_nopriv_gt_de_en_autocomplete', array($this, 'handle_ajax_autocomplete_de_en'));

        // Registrace AJAX akce
        add_action('wp_ajax_gt_word2vec', array($this, 'handle_ajax_word2vec'));
        add_action('wp_ajax_nopriv_gt_word2vec', array($this, 'handle_ajax_word2vec_cz_en'));




    }

    public function enqueue_scripts(): void {
        if ($this->plugin_public->plugin->shortcode_check($this->shortcodes)) {
            wp_enqueue_script(
                'gt_js_en_cz_translation',
                $this->plugin_public->plugin->plugin_dir_url() . 'public/js/en_cz_translation.js',
                array('jquery', 'gt_js_request_types')
            );
            wp_enqueue_script(
                'gt_js_de_en_translation',
                $this->plugin_public->plugin->plugin_dir_url() . 'public/js/de_en_translation.js',
                array('jquery', 'gt_js_request_types')
            );
            wp_enqueue_script(
                'gt_js_la_en_translation',
                $this->plugin_public->plugin->plugin_dir_url() . 'public/js/la_en_translation.js',
                array('jquery', 'gt_js_request_types')
            );


            wp_localize_script('gt_js_de_en_translation', 'gt_translation_data_de', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                '_ajax_nonce' => wp_create_nonce(GT_PREFIX . 'nonce')
            ));

            wp_localize_script('gt_js_en_cz_translation', 'gt_Word2Vec_suggestion_cz', array(
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('gt_word2vec'),
            ));

            wp_localize_script('gt_js_de_en_translation', 'gt_Word2Vec_suggestion_de', array(
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('gt_word2vec'),
            ));

            wp_localize_script('gt_js_la_en_translation', 'gt_Word2Vec_suggestion_la', array(
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('gt_word2vec'),
            ));










        }
    }
    public function handle_ajax_word2vec(): void {
        check_ajax_referer('gt_word2vec', '_ajax_nonce');

        $word = isset($_POST['word']) ? sanitize_text_field($_POST['word']) : '';

        if (empty($word)) {
            error_log("[ERROR] No word provided.");
            wp_send_json(['status' => 'error', 'message' => 'No word provided.']);
            return;
        }

        error_log("[INFO] Fetching similar words for: " . $word);
        error_log("[DEBUG] AJAX Request: word={$word}");

        // Získání podobných slov z Word2Vec FastAPI serveru
        $similar_words = $this->fetch_word2vec_suggestions($word);

        wp_send_json([
            'status' => 'success',
            'suggestions' => $similar_words,
        ]);
    }

    /**
     * Volání Word2Vec API na vlastním serveru
     */
    private function fetch_word2vec_suggestions(string $word): array {
        $api_url = "http://127.0.0.1:5000/word2vec?word=" . urlencode($word);

        error_log("[DEBUG] API Request: " . $api_url); // Logování API URL

        $response = wp_remote_get($api_url, ['timeout' => 15]);

        if (is_wp_error($response)) {
            error_log("[ERROR] Word2Vec API request failed.");
            return ["No suggestion"];
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        error_log("[DEBUG] API Response: " . print_r($body, true)); // Log odpovědi

        return $body['suggestions'] ?? ["No suggestion"];
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
    public function handle_ajax_translation_la_cz(): void {
        check_ajax_referer(GT_PREFIX . 'nonce');

        $input_value = mb_strtoupper(trim(filter_input(INPUT_POST, 'word_la', FILTER_SANITIZE_STRING)));

        $translations = [];

        if (!empty($input_value)) {
            $container = GT_Container::instance();
            $la_cz_translation_dao = $container->get_la_cz_translation_dao();

            $translations = $la_cz_translation_dao->get_translations_la_to_cz($input_value);


        }





        $this->plugin_public->plugin->success($translations);


    }
    public function handle_ajax_translation_la_cz_en(): void {
        check_ajax_referer(GT_PREFIX . 'nonce');

        $input_value = trim(filter_input(INPUT_POST, 'word_cz', FILTER_SANITIZE_STRING));
        $translations = 'No translation found.';
        

        if (!empty($input_value)) {
            $api_url = 'https://api.mymemory.translated.net/get?q=' . urlencode($input_value) . '&langpair=cs|en';

            $response = wp_remote_get($api_url);

            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $body = json_decode(wp_remote_retrieve_body($response), true);
                $translations = $body['responseData']['translatedText'] ?? 'No translation found.';
            }
        }

        wp_send_json_success(['translation' => $translations]);
        wp_die(); // DŮLEŽITÉ!!!
    }








    public function handle_ajax_translation_de_en(): void {
        check_ajax_referer(GT_PREFIX . 'nonce');

        $input_value = trim(filter_input(INPUT_POST, 'word_de', FILTER_SANITIZE_STRING));
        $translations = 'No translation found.';

        if (!empty($input_value)) {
            $api_url = 'https://api.mymemory.translated.net/get?q=' . urlencode($input_value) . '&langpair=de|en';

            $response = wp_remote_get($api_url);

            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $body = json_decode(wp_remote_retrieve_body($response), true);
                $translations = $body['responseData']['translatedText'] ?? 'No translation found.';
            }
        }

        wp_send_json_success(['translation' => $translations]);
        wp_die(); // DŮLEŽITÉ!!!
    }
    public function handle_ajax_autocomplete_de_en(): void {
        check_ajax_referer(GT_PREFIX . 'nonce');

        $input_value = trim(filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING));
        $suggestions = [];

        if (!empty($input_value)) {
            $api_url = 'https://api.mymemory.translated.net/get?q=' . urlencode($input_value) . '&langpair=de|en';

            $response = wp_remote_get($api_url);

            if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                $body = json_decode(wp_remote_retrieve_body($response), true);
                if (!empty($body['matches'])) {
                    foreach ($body['matches'] as $match) {
                        $suggestions[] = $match['segment'];
                    }
                }
            }
        }

        wp_send_json_success(['suggestions' => $suggestions]);
        wp_die();
    }




    public function render_shortcode_cz_aj($content = null): string {
        ob_start();
        ?>
        <h2>Czech to English
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

                <!-- Word2Vec -->
                <div class="col-10 col-md-5 mt-2">
                    <label for="cz-en-word2vec-output">Nearest Word2Vec Suggestion:</label>
                    <table id="cz-en-word2vec-output" class="wrapped-output-table">
                        <tr><td>No suggestion.</td></tr>
                    </table>
                </div>
            </div>
        </form>

        <?php
        return ob_get_clean();
    }
    public function render_shortcode_de_en($content = null): string {
        ob_start();
        ?>
        <h2>German to English

            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-de-en-translation" title="Show Help">?
            </button>
            </h2>
            <div id="gt-help-de-en-translation"
                 class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
                <div class="col-12 text-justify">
                    <p>Enter a German word and click "Send" to get its English translation.</p>
                    <p>You can copy or print the results for further use.</p>
                </div>
            </div>
        <form class="gt-word-translation-form" data-type="de-en-translation" action="javascript:void(0);">
            <div class="row">
                <div class="col-12 col-md-4 mt-2">
                    <label for="gt-de-en-translation-input">German Word:</label>
                    <input type="search" id="gt-de-en-translation-input"
                           data-type="de-en-translation"
                           class="gt-de-en-translation-input form-control form-control-lg"
                           placeholder="Enter a word"
                           list="de-en-translation-datalist">
                    <datalist id="de-en-translation-datalist"></datalist>
                </div>



                <div class="col-12 col-md-2 mt-3 mt-md-5">
                    <button id="de-en-translation-submit" type="submit" class="w-100 btn btn-primary">
                        Send
                    </button>
                </div>

                <div class="col-10 col-md-5 mt-2">
                    <label for="de-en-translation-output">English Translation:</label>
                    <table id="de-en-translation-output" class="de-en-translation-output gt-de-en-translation-result wrapped-output-table">
                        <tr><td>No word entered.</td></tr>
                    </table>
                </div>

                <div class="col-2 col-md-1 mt-5 text-center">
                    <a href="javascript:void(0);" class="gt-print-btn" data-target="de-en-translation-output">Print</a>
                </div>

                <!-- Word2Vec -->
                <div class="col-10 col-md-5 mt-2">
                    <label for="de-en-word2vec-output">Nearest Word2Vec Suggestion:</label>
                    <table id="de-en-word2vec-output" class="wrapped-output-table">
                        <tr><td>No suggestion.</td></tr>
                    </table>
                </div>
            </div>
            <!-- Speciální znaky -->
            <div class="col-12 col-md-4">
                <label>Special characters:</label>
                <div class="row justify-content-between">
                    <button type="button" class="btn btn-primary gt-typer-addbtn">ä</button>
                    <button type="button" class="btn btn-primary gt-typer-addbtn d-md-none">Ä</button>
                    <button type="button" class="btn btn-primary gt-typer-addbtn">ö</button>
                    <button type="button" class="btn btn-primary gt-typer-addbtn d-md-none">Ö</button>
                    <button type="button" class="btn btn-primary gt-typer-addbtn">ü</button>
                    <button type="button" class="btn btn-primary gt-typer-addbtn d-md-none">Ü</button>
                    <button type="button" class="btn btn-primary gt-typer-addbtn">ß</button>
                </div>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }
    public function render_shortcode_la_en($content = null): string {
        ob_start();
        ?>
        <h2>Latin to English
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-la-en-translation" title="Show Help">?
            </button>
        </h2>
        <div id="gt-help-la-en-translation"
             class="row gt-help-text d-none rounded border border-info mx-1 pt-2 bg-light">
            <div class="col-12 text-justify">
                <p>Enter a Latin word and click "Send" to get its English translation.</p>
                <p>You can copy or print the results for further use.</p>
            </div>
        </div>
        <form class="gt-word-translation-form_la_en" data-type="la-en-translation" action="javascript:void(0);">
            <div class="row">
                <!-- Input field for Czech word -->
                <div class="col-12 col-md-4 mt-2">
                    <label for="gt-la-en-translation-input">Latin Word:</label>
                    <input type="search" id="gt-la-en-translation-input"
                           data-type ="la-en-translation"
                           class="gt-la-en-translation-input la-en-translation-autocomplete gt-autocomplete form-control form-control-lg"
                           placeholder="Enter a word">
                </div>

                <!-- Submit button -->
                <div class="col-12 col-md-2 mt-3 mt-md-5">
                    <button id="la-en-translation-submit" type="submit" class="w-100 btn btn-primary">
                        Send
                    </button>
                </div>

                <!-- Output area for English translation -->
                <div class="col-10 col-md-5 mt-2">
                    <label for="la-en-translation-output">English Translation:</label>
                    <table id="la-en-translation-output" class="la-en-translation-output gt-la-en-translation-result wrapped-output-table">
                        <tr><td>No word entered.</td></tr>
                    </table>
                </div>

                <!-- Print button -->
                <div class="col-2 col-md-1 mt-5 text-center">
                    <a href="javascript:void(0);" class="gt-print-btn" data-target="la-en-translation-output">Print</a>
                </div>
                <!-- Word2Vec -->
                <div class="col-10 col-md-5 mt-2">
                    <label for="la-en-word2vec-output">Nearest Word2Vec Suggestion:</label>
                    <table id="la-en-word2vec-output" class="wrapped-output-table">
                        <tr><td>No suggestion.</td></tr>
                    </table>
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
    public function autocomplete_la_cz_translation( string $value ): ?array
    {
        $container          = GT_Container::instance();
        $cz_en_translation_dao = $container->get_la_cz_translation_dao();

        return $cz_en_translation_dao->get_word_la_by_prefix( $value );
    }
}
