//#region WP INJECTS

__ajax_obj = __ajax_obj; // WP reference injected via `wp_localize_script`

//#endregion

jQuery(document).ready(function ($) {

    load_table_info();

    /**
     * Last name explanation (ln_explanation) form import for one record
     */
    $(GT_SELECTOR.LN_EXPLANATION_IMPORT_ONE_FORM).on("submit", function (event) {
        insert_one_record("ln_explanation", GT_SELECTOR.LN_EXPLANATION_IMPORT_ONE_NAME, GT_SELECTOR.LN_EXPLANATION_IMPORT_ONE_EXPLANATION, null, null, GT_SELECTOR.FN_TRANSLATION_IMPORT_ONE_PRIORITY, GT_SELECTOR.LN_EXPLANATION_IMPORT_INFO);
    });

    /**
     * First name diminutives (fn_diminutive) form import for one record
     */
    $(GT_SELECTOR.FN_DIMINUTIVES_IMPORT_ONE_FORM).on("submit", function (event) {
        insert_one_record("fn_diminutive", GT_SELECTOR.FN_DIMINUTIVES_IMPORT_ONE_NAME, null, GT_SELECTOR.FN_DIMINUTIVES_IMPORT_ONE_DIMINUTIVE, null, GT_SELECTOR.FN_TRANSLATION_IMPORT_ONE_PRIORITY, GT_SELECTOR.FN_DIMINUTIVES_IMPORT_INFO);
    });

    /**
     * First name translation (fn_translation) form import for one record
     */
    $(GT_SELECTOR.FN_TRANSLATION_IMPORT_ONE_FORM).on("submit", function (event) {
        insert_one_record("fn_translation", GT_SELECTOR.FN_TRANSLATION_IMPORT_ONE_NAME, null, null, GT_SELECTOR.FN_TRANSLATION_IMPORT_ONE_NAME_EN, GT_SELECTOR.FN_TRANSLATION_IMPORT_ONE_PRIORITY, GT_SELECTOR.FN_TRANSLATION_IMPORT_INFO);
    });

    /**
     * Cities (gt_city) form import
     */
    $(GT_SELECTOR.CITY_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.CITY_IMPORT_FORM, GT_SELECTOR.CITY_IMPORT_FILE_NAME, GT_SELECTOR.CITY_IMPORT_INFO, GT_SELECTOR.CITY_IMPORT_TABLE, "gt_cities_import",
            [GT_SELECTOR.CITY_IMPORT_FORM, GT_SELECTOR.DISTRICT_IMPORT_FORM, GT_SELECTOR.REGION_IMPORT_FORM, GT_SELECTOR.MEP_IMPORT_FORM]);
    });

    /**
     * Districts (gt_district) form import
     */
    $(GT_SELECTOR.DISTRICT_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.DISTRICT_IMPORT_FORM, GT_SELECTOR.DISTRICT_IMPORT_FILE_NAME, GT_SELECTOR.DISTRICT_IMPORT_INFO, GT_SELECTOR.DISTRICT_IMPORT_TABLE, "gt_districts_import",
            [GT_SELECTOR.CITY_IMPORT_FORM, GT_SELECTOR.DISTRICT_IMPORT_FORM, GT_SELECTOR.REGION_IMPORT_FORM, GT_SELECTOR.MEP_IMPORT_FORM]);
    });

    /**
     * Regions (gt_region) form import
     */
    $(GT_SELECTOR.REGION_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.REGION_IMPORT_FORM, GT_SELECTOR.REGION_IMPORT_FILE_NAME, GT_SELECTOR.REGION_IMPORT_INFO, GT_SELECTOR.REGION_IMPORT_TABLE, "gt_regions_import",
            [GT_SELECTOR.CITY_IMPORT_FORM, GT_SELECTOR.DISTRICT_IMPORT_FORM, GT_SELECTOR.REGION_IMPORT_FORM, GT_SELECTOR.MEP_IMPORT_FORM]);
    });

    /**
     * Municipalities with extended powers (gt_mep) form import
     */
    $(GT_SELECTOR.MEP_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.MEP_IMPORT_FORM, GT_SELECTOR.MEP_IMPORT_FILE_NAME, GT_SELECTOR.MEP_IMPORT_INFO, GT_SELECTOR.MEP_IMPORT_TABLE, "gt_mep_import",
            [GT_SELECTOR.CITY_IMPORT_FORM, GT_SELECTOR.DISTRICT_IMPORT_FORM, GT_SELECTOR.REGION_IMPORT_FORM, GT_SELECTOR.MEP_IMPORT_FORM]);
    });

    /**
     * First name diminutives (gt_fn_diminutive) form import
     */
    $(GT_SELECTOR.FN_DIMINUTIVES_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.FN_DIMINUTIVES_IMPORT_FORM, GT_SELECTOR.FN_DIMINUTIVES_IMPORT_FILE_NAME, GT_SELECTOR.FN_DIMINUTIVES_IMPORT_INFO, GT_SELECTOR.FN_DIMINUTIVES_IMPORT_TABLE, "gt_fn_diminutives_import",
            [GT_SELECTOR.FN_DIMINUTIVES_IMPORT_FORM, GT_SELECTOR.FN_TRANSLATION_IMPORT_FORM, GT_SELECTOR.FN_IMPORT_FORM]);
    });

    /**
     * First name translations (gt_fn_translation) form import
     */
    $(GT_SELECTOR.FN_TRANSLATION_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.FN_TRANSLATION_IMPORT_FORM, GT_SELECTOR.FN_TRANSLATION_IMPORT_FILE_NAME, GT_SELECTOR.FN_TRANSLATION_IMPORT_INFO, GT_SELECTOR.FN_TRANSLATION_IMPORT_TABLE, "gt_fn_translation_import",
            [GT_SELECTOR.FN_DIMINUTIVES_IMPORT_FORM, GT_SELECTOR.FN_TRANSLATION_IMPORT_FORM, GT_SELECTOR.FN_IMPORT_FORM]);
    });

    /**
     * First names (gt_first_name) form import
     */
    $(GT_SELECTOR.FN_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.FN_IMPORT_FORM, GT_SELECTOR.FN_IMPORT_FILE_NAME, GT_SELECTOR.FN_IMPORT_INFO, GT_SELECTOR.FN_IMPORT_TABLE, "gt_fn_import",
            [GT_SELECTOR.FN_DIMINUTIVES_IMPORT_FORM, GT_SELECTOR.FN_TRANSLATION_IMPORT_FORM, GT_SELECTOR.FN_IMPORT_FORM]);
    });

    /**
     * Last name explanation (gt_ln_explanation) form import
     */
    $(GT_SELECTOR.LN_EXPLANATION_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.LN_EXPLANATION_IMPORT_FORM, GT_SELECTOR.LN_EXPLANATION_IMPORT_FILE_NAME, GT_SELECTOR.LN_EXPLANATION_IMPORT_INFO, GT_SELECTOR.LN_EXPLANATION_IMPORT_TABLE, "gt_ln_explanation_import",
            [GT_SELECTOR.LN_IMPORT_FORM, GT_SELECTOR.LN_COUNT_IMPORT_FORM, GT_SELECTOR.LN_EXPLANATION_IMPORT_FORM]);
    });

    /**
     * Last name count (gt_ln_count) form import
     */
    $(GT_SELECTOR.LN_COUNT_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.LN_COUNT_IMPORT_FORM, GT_SELECTOR.LN_COUNT_IMPORT_FILE_NAME, GT_SELECTOR.LN_COUNT_IMPORT_INFO, GT_SELECTOR.LN_COUNT_IMPORT_TABLE, "gt_ln_count_import",
            [GT_SELECTOR.LN_IMPORT_FORM, GT_SELECTOR.LN_COUNT_IMPORT_FORM, GT_SELECTOR.LN_EXPLANATION_IMPORT_FORM]);
    });

    /**
     * Last names (gt_last_name) form import
     */
    $(GT_SELECTOR.LN_IMPORT_FORM).on("submit", function (event) {

        insert_csv_file(GT_SELECTOR.LN_IMPORT_FORM, GT_SELECTOR.LN_IMPORT_FILE_NAME, GT_SELECTOR.LN_IMPORT_INFO, GT_SELECTOR.LN_IMPORT_TABLE, "gt_ln_import",
            [GT_SELECTOR.LN_IMPORT_FORM, GT_SELECTOR.LN_COUNT_IMPORT_FORM, GT_SELECTOR.LN_EXPLANATION_IMPORT_FORM]);
    });

    /**
     * Get numbers of records from database for each table
     */
    function load_table_info() {
        if (window.location.href.includes("gt_admin_info")) {
            $.get(__ajax_obj.url, {
                _ajax_nonce: __ajax_obj.nonce,
                action: "gt_tables_info"
            }, function (data) {
                // Check if the response is in a valid format...
                if (data.hasOwnProperty("status")) {
                    // On successful request response...
                    if (data.status === "success") {
                        fill_info_table(data.results);
                    }
                    else {

                    }
                } else {
                }
            }, "json")
                .fail(function () {

                });
        }
    }

    /**
     * Fill information table with Ajax results
     * @param results
     */
    function fill_info_table(results) {
        let t = "<table class='table'>\n" +
            "\t\t\t\t <thead class='thead-dark'>\n" +
            "\t\t\t\t    <tr>\n" +
            "\t\t\t\t      <th scope='col'>Table name</th>\n" +
            "\t\t\t\t      <th scope='col'>Total number of records</th>\n" +
            "\t\t\t\t    </tr>\n" +
            "\t\t\t\t  </thead>\n" +
            "\t\t\t\t  <tbody>";
        results.forEach(element => t += "" +
            "<tr>\n" +
            "\t\t\t\t\t    <td>" + element[0] + "</td>\n" +
            "\t\t\t\t\t    <td>" + element[1]["COUNT(*)"] + "</td>\n" +
            "\t\t\t\t\t  </tr>")
        ;
        t += "</tbody>\n" +
            "\t\t\t\t</table>";
        document.getElementById("gt-table-info").innerHTML = t;
    }

    /**
     * Import CSV records to DB
     *
     * @param selector_form form selector
     * @param selector_file_name file input
     * @param selector_info status field
     * @param selector_table table div
     * @param action Axaj action name
     * @param selectors_to_disable
     */
    function insert_csv_file(selector_form, selector_file_name, selector_info, selector_table, action, selectors_to_disable) {
        // Disable forms on page
        disable_forms(selectors_to_disable, true);

        // Change status
        $(selector_info).html("Loading...");

        // Get properties
        let filename = $(selector_file_name).val();
        let table_name = selector_form;
        let file_data = $(selector_file_name).prop('files')[0];
        let form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('action', action);

        if (file_data === undefined) {
            $(selector_info).html("Please, select CSV file");
            disable_forms(selectors_to_disable, false);
            return;
        }
        // Upload data
        upload_data(filename, action, table_name, selector_info, selector_table,
            selectors_to_disable,
            form_data);
    }

    /**
     * Fill the table with error values
     *
     * @param results results from server
     * @param table table class
     */
    function fill_table_results(results, table) {
        if (results.length > 0) {
            let t = "<table class='table'>\n" +
                "\t<tbody style='display: block; height: 300px; overflow-y: scroll'>";
            for (let i = 0; i < results.length; i++) {
                let tr = "<tr>";
                tr += "<td>" + results[i][0] + "</td>";
                tr += "<td>" + results[i][1] + "</td>";
                if (results[i][results[i].length - 1] === true || results[i][results[i].length - 1] === 1) {
                    tr += "<td style='color: green'>Success!</td>";
                } else {
                    tr += "<td style='color: red'>Fail!</td>";
                }

                tr += "</tr>";
                t += tr;
            }
            t += "\t</tbody>\n" +
                "</table>";
            document.getElementById(table).innerHTML = t;
        }
    }

    function insert_one_record(table, selector_name, selector_explanation, selector_diminutive, selector_name_en, selector_priority, status_selector) {
        let name = $(selector_name).val();
        let explanation = "";
        let diminutive = "";
        let name_en = "";
        let priority = "";

        if (table === "ln_explanation") {
            explanation = $(selector_explanation).val();
        } else if (table === "fn_translation") {
            name_en = $(selector_name_en).val();
            priority = $(selector_priority).val();
        } else if (table === "fn_diminutive") {
            diminutive = $(selector_diminutive).val();
        } else {
            return;
        }

        $.post(__ajax_obj.url, {
            _ajax_nonce: __ajax_obj.nonce,
            action: "gt_import_one_record",
            table: table,
            name_cz: name,
            explanation: explanation,
            diminutive: diminutive,
            priority: priority,
            name_en: name_en
        }, function (data) {
            // Check if the response is in a valid format...
            if (data.hasOwnProperty("status")) {
                // On successful request response...
                if (data.status === "success") {
                    $(selector_name).val("");
                    $(selector_explanation).val("");
                    $(selector_diminutive).val("");
                    $(selector_name_en).val("");
                    $(selector_priority).val("");
                    $(status_selector).html("Success!");
                }
                // Otherwise, not acceptable response...
                else if (data.status === "error") {
                    $(status_selector).html(data.status);
                }
            } else {
                $(status_selector).html("Error - Unknown server response.");
            }
        }, "json")
            .fail(function () {
                $(status_selector).html("Fail!");
        }).catch(function (error) {
            if (error.status === 500) {
                $(status_selector).html('Cannot display results');
            } else if (error.status === 400) {
                $(status_selector).html('Bad Request');
            } else {
                $(status_selector).html('Request failed');
            }
        });
    }

    function disable_forms(selectors_to_disable, disable) {
        selectors_to_disable.forEach(element => $(element + " :input").prop("disabled", disable));
    }

    /**
     * Ajax request to import CSV file
     *
     * @param filename CSV file name
     * @param action_name Axaj action name
     * @param table_name table name
     * @param status_selector Status field
     * @param table_selector Table div
     * @param selectors_to_disable Forms array to disable
     * @param content
     */
    function upload_data(filename, action_name, table_name, status_selector, table_selector, selectors_to_disable, content) {
        $.ajax({
            url: __ajax_obj.url,
            type: 'POST',
            action: action_name,
            contentType: false,
            processData: false,
            filename: filename,
            table_name: table_name,
            data: content,
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.hasOwnProperty("status")) {
                    if (obj["status"] === "success") {
                        $(status_selector).html("Success!");
                        fill_table_results(obj["results"], table_selector);
                    } else if (obj["status"] === "error") {
                        $(status_selector).html(obj["error_msg"]);
                    }
                } else {
                    $(status_selector).html("Error - Unknown server response.");
                }
                disable_forms(selectors_to_disable, false);
            },
            fail: function (response) {
                $(status_selector).html("Fail!");
                disable_forms(selectors_to_disable, false);
            }
        }).catch(function (error) {
            if (error.status === 500) {
                $(status_selector).html('Cannot display results');
            } else if (error.status === 400) {
                $(status_selector).html('Bad Request');
            } else {
                $(status_selector).html('Request failed');
            }
            disable_forms(selectors_to_disable, false);
        });
    }

});




