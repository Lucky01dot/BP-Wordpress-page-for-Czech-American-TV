<?php


class GT_Plugin_EN_CZ_Translation {

    private GT_Plugin_Public $plugin_public;
    public array $shortcodes;
    public function __construct( GT_Plugin_Public $plugin_public ) {
        $this->plugin_public = $plugin_public;

        $this->shortcodes = [
            'catv_gt_transcription_fname',
            'catv_gt_transcription_lname',
            'catv_gt_femvar'
        ];
    }

    public function register(){

        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );




    }
    public function autocomplete_cz_en_translation( string $value ): ?array {
        $container          = GT_Container::instance();
        $cz_en_translation_dao = $container->get_cz_en_transtation_dao();

        return $cz_en_translation_dao->get_translations_cz_to_en( $value );
    }
    public function shortcode_fn_translation( string $attrs, $content = null ){
        ob_start();

        ?>
        <h2>Czech to English
            <button class="btn btn-primary btn-sm gt-help-btn float-right rounded-circle font-weight-bold"
                    data-target="gt-help-cz-en-translation" title="Show Help">?
            </button>
        </h2>
        <div id="gt-help-cz-en-fn-translation"
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


        <?php


        return ob_get_clean();
    }



















}