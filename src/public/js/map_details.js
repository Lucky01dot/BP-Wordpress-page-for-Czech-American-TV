var last_mep; // Last used city name
var last_name; // Last used last name
var last_type; // Last used type

$(window).on('load', function () {

    // Redirect on load based on GET parameters
    // ... try to redirect, if the inputs are not set, it will not redirect.
    gt_name_distribution_redirect_from_other_page();

    // Details show/hide toggle city-specific details
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_DETAILS_DIV).on('click', '.gt-name-distribution-map-display-toggle', function () {
        if ($(this).hasClass("gt-name-distribution-map-display-toggle-expanded")) {
            $(this).removeClass("gt-name-distribution-map-display-toggle-expanded");
        } else {
            $(".gt-name-distribution-map-display-toggle-expanded").click();
            $(this).addClass("gt-name-distribution-map-display-toggle-expanded");
        }
        $(this).siblings().slideToggle();
    });

    // Details redirect from region details
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_DETAILS_DIV).on('click', '.gt-details-redirect', function () {
        document.getElementById("gt-name-distribution-map-ln-input").value = "";
        last_name = undefined;
        last_mep = $(this).data("id");
        gt_details_redirect($(this).data("id"), $(this).data("name"), $(this).data("type"))
        gt_update();
    });

    $("#gt-name-distribution-map-region-input").change(function () {
        document.getElementById("gt-name-distribution-map-mep-input").value = "";
        last_mep = undefined;
        gt_update();
    });

    // Button "clean" for name input
    $("#clean_name").click(function () {
        document.getElementById("gt-name-distribution-map-ln-input").value = "";
        last_name = undefined;
        gt_update();
    });

    // Button "clean" for city input
    $("#clean_city").click(function () {
        document.getElementById("gt-name-distribution-map-mep-input").value = "";
        last_mep = undefined;
        gt_update();
    });

    // Button "clean" for region input
    $("#gt-name-distribution-map-region-clean-btn").click(function () {
        if (document.getElementById("gt-name-distribution-map-mep-input").value !== "") {
            return;
        }
        document.getElementById("gt-name-distribution-map-region-input").value = "0";
        gt_update();
    });

    // Get data from autocomplete and call update
    /* the inputs require to have 'data-type' attribute containing... */
    /** ... {@see GT_NAME_DISTRIBUTION_MAP_PARAMETER} */
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUTS).on("autocompleteselect", function (event, ui) {
        // TODO: figure out what the `help` field is for and doc it in the code

        // Ignore if object has defined 'help' field
        if (ui.item.help) {
            return false;
        }

        let $ui_type = $(this).data("type");

        if ($ui_type === GT_AUTOCOMPLETE_TYPE.NAME_DISTRIBUTION_MEP) {
            last_mep = ui.item.id;
        } else if ($ui_type === GT_AUTOCOMPLETE_TYPE.NAME_DISTRIBUTION_LN) {
            last_name = ui.item.id;
        }

        gt_update();
    });
});

//#region FUNCTIONS

/**
 * Check for redirection from other page and show map accordingly.
 */
function gt_name_distribution_redirect_from_other_page() {
    let name = $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_REDIRECT_LAST_NAME).val()

    // Ignore if the input is not set...
    if (name === undefined || name === null || name.length === 0) {
        return;
    }

    // get last name ID by ajax
    $.get(__ajax_obj.url, {
        _ajax_nonce: __ajax_obj.nonce,
        action: "gt_name_distribution_last_name_info",
        name: name
    }, function (data) {
        // Check if the response is in a valid format...
        if (data.hasOwnProperty("status")) {
            // On successful request response...
            if (data.status === "success") {
                gt_details_redirect(data.results.id, data.results.name, GT_NAME_DISTRIBUTION_MAP_PARAMETER.LAST_NAME);
            }
            // Otherwise, not acceptable response...
            else {
                alert("Sorry, we do not have name distribution information for the name " + name);
            }
        }
    }, "json");
}

/**
 * Show map details based on the parameters
 */
function gt_details_redirect(id, name, type) {
    // Ignore if any of the inputs is not set...
    if (id.length === 0 || name.length === 0 || type.length === 0) {
        return;
    }

    if (type === GT_NAME_DISTRIBUTION_MAP_PARAMETER.REGION) {
        $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUT.replace("${type}", GT_NAME_DISTRIBUTION_MAP_PARAMETER.REGION))
            .children("option[value=" + id + "]").attr("selected", "selected");
    } else {
        last_name = id;
        $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUT.replace("${type}", type))
            .val(name);
    }

    gt_details(type);
}

/**
 * Get data for map details and print it to HTML
 */
function gt_details(type) {

    let region_id =
        document.getElementById(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUT.replace("${type}", GT_NAME_DISTRIBUTION_MAP_PARAMETER.REGION).substring(1))
            .value;

    let is_name_null = last_name === undefined || last_name === "";
    let is_city_null = last_mep === undefined || last_mep === "";
    let is_region_null = region_id === "0";

    if (is_name_null && is_city_null && is_region_null) {
        // no input, so clear details info and return
        $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_DETAILS_DIV).html("");
        $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_STATUS_MSG_BOX).html("");
        return;
    }

    let details;
    // Disable the inputs
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUTS).prop("disabled", true);
    // Clear the output first
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_DETAILS_DIV).html("");
    // Update status message
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_STATUS_MSG_BOX).html("Loading...");

    $.post(__ajax_obj.url, {
        _ajax_nonce: __ajax_obj.nonce,
        action: "gt_name_distribution_map_details",
        name_id: last_name,
        region_id: region_id,
        mep_id: last_mep
    }, function (data) {
        // Check if the response is in a valid format...
        if (data.hasOwnProperty("status")) {
            // On successful request response...
            if (data.status === "success") {

                gt_map_data_regions = [['Regions', 'Count']];
                gt_map_data_cities = [];

                let status_msg = ""

                // ======================= Cities =======================
                if (is_name_null && !is_city_null) {
                    details = gt_fill_cities(data);
                    status_msg = data.results.name_cz;
                }
                // ======================= Regions =======================
                else if (is_name_null && is_city_null && !is_region_null) {
                    details = gt_fill_regions(data, region_id);
                    status_msg = data.results.name_en;
                }
                // ======================= Names =======================
                else if (!is_name_null && is_city_null && is_region_null) {
                    // Only Name
                    details = gt_fill_names(data, 0);
                    status_msg = data.results.name;
                } else if (!is_name_null && is_city_null && !is_region_null) {
                    // Name & Region
                    details = gt_fill_names(data, 1);
                    status_msg = data.results.name;
                } else if (!is_name_null && !is_city_null) {
                    // Name & City & Region
                    details = gt_fill_names(data, 2);
                    status_msg = data.results.name;
                }

                $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_STATUS_MSG_BOX).html("");
                $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_STATUS_MSG_BOX).append('<span class="font-weight-bold">' + status_msg + '</span>');

                // Add print button for names
                if (type === GT_NAME_DISTRIBUTION_MAP_PARAMETER.LAST_NAME) {
                    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_STATUS_MSG_BOX).append(
                        '<a href="javascript:void(0);" class="gt-print-btn" data-target="gt-name-distribution-map-print" data-map="true"><img src="' + __wp_vars.print_uri + '" class="gt-print-img float-right" alt="Print"></a>'
                    );
                }

                // Set details to output
                $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_DETAILS_DIV).html(details);

                // Hide cities
                $(".gt-name-distribution-map-display-toggle").siblings().slideToggle();
                $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUTS).removeClass("bg-primary text-white");
                $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUT.replace("${type}", type)).addClass("bg-primary text-white");

            }
            // Otherwise, not acceptable response...
            else {
                // Clear the output
                $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_DETAILS_DIV).html("");
                // Set new status message
                $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_STATUS_MSG_BOX).html("Error: " + data.error_msg);

                console.log(`Name transcription process error: ${data.error_msg}`);
            }
        } else {
            // Re-enable the inputs
            $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUTS).prop("disabled", false);
            // Clear the output
            $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_DETAILS_DIV).html("");
            // Set new status message
            $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_STATUS_MSG_BOX).html("Unknown server error.");
        }

        // Re-enable the inputs
        $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUTS).prop("disabled", false);

    }, "json")
        .fail(function () {
            // Re-enable the inputs
            $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUTS).prop("disabled", false);
            // Clear the output
            $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_DETAILS_DIV).html("");
            // Set new status message
            $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_STATUS_MSG_BOX).html("Unable to retrieve detail information about the map.");
        });
}

/**
 * Update type
 */
function gt_update() {
    let name = $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUT.replace("${type}", GT_NAME_DISTRIBUTION_MAP_PARAMETER.LAST_NAME)).val();
    let city = $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUT.replace("${type}", GT_NAME_DISTRIBUTION_MAP_PARAMETER.MEP)).val();
    let region = $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_INPUT.replace("${type}", GT_NAME_DISTRIBUTION_MAP_PARAMETER.REGION)).val();

    if (name === "") {
        last_name = undefined;
    }
    if (city === "") {
        last_mep = undefined;
    }

    gt_details(last_type);
}

/**
 * Fill cities information
 * @param data - data from Ajax
 * @returns {string} - html
 */
function gt_fill_cities(data) {
    // Cities
    gt_map_data_cities["details"] = {
        name: data.results.name_cz,
        coords: {lat: parseFloat(data.results.lat), lng: parseFloat(data.results.lng)},
        count: undefined
    };
    let details = "<br><div class='row'>";
    details += "<div class='col-4 col-md-4 font-weight-bold'>Region:</div>";
    details += "<div class='col-8 col-md-8 text-right'>" + data.results.region_name + "</div>";
    details += "</div>";
    details += "<div class='row'>";
    details += "<div class='col-4 col-md-4 font-weight-bold'> German: </div>";
    details += "<div class='col-8 col-md-8 text-right'>" + data.results.name_de + "</div>";
    details += "</div>"
    details += "<hr>";
    details += "<div class='row font-weight-bold'>";
    details += "<div class='col-9 col-md-9'>Popular Last Names</div>";
    details += "<div class='col-3 col-md-3 text-right'>Count</div>";
    details += "</div>";

    data.results.popular_names.forEach(function (name) {
        details += "<div class='row popular-last-names'>";
        details += "<div class='col-9 col-md-9'>" + name.name + "</div>";
        details += "<div class='col-3 col-md-3 text-right'>" + name.count + "</div></a>";
        details += "</div>";
    });

    gt_map_cities(gt_map_data_cities);
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_MEP_CHECKBOX).prop('checked', true);
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_REGION_CHECKBOX).prop('disabled', true);
    document.getElementById("gt-name-distribution-map-region-input").value = data.results.region_id;

    return details;
}

/**
 * Fill regions information
 * @param data - data from Ajax
 * @param region_id - region id
 * @returns {string} - html
 */
function gt_fill_regions(data, region_id) {
    gt_map_data_regions = [['Regions', 'Count', {role: 'tooltip'}]];

    let details = "<div class='row'>";
    details += "<div class='col-12 col-md-12'> <h5> Cities </h5></div>";
    details += "</div>";

    for (let mep_id in data.results.meps) {
        if (data.results.meps.hasOwnProperty(mep_id)) {
            let mep = data.results.meps[mep_id];

            details += "<div class='row'>";
            details += "<div class='col-9 col-md-9 city-in-region'>" + mep.name_cz + "</div><div class='col-3 col-md-3 text-right'> <a href='javascript:void(0);' class='gt-details-redirect city-details' data-type='mep' data-id='" + mep.id + "' data-name='" + mep.name_cz + "'> Details </a></div>";
            details += "</div>";
        }

    }

    gt_map_data_regions.push([{v: data.results.map_code, f: data.results.name_en}, 1, ""]);
    gt_map_regions(gt_map_data_regions, true);

    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_REGION_CHECKBOX).prop('checked', true);
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_MEP_CHECKBOX).prop('disabled', true);

    document.getElementById("gt-name-distribution-map-region-input").value = region_id;

    return details;
}

/**
 * Fill names information
 * @param data - data from Ajax
 * @param type - type (0 = only names), (1 = name and region), (2 = name, region and city)
 * @returns {string} - html
 */
function gt_fill_names(data, type) {
    let print = "<h2> Distribution for  " + data.results.name + ": </h2>";
    print += "<table class='table'>";
    let details = "<div class='row'>";

    if (type === 2 || type === 1) {
        details += "<div class='col-9 col-md-9'> <h4 id='city'> City </h4></div> <div class='col-3 col-md-3 text-right'>  <h4> Count </h4> </div>";
    } else {
        details += "<div class='col-9 col-md-9'> <h4 id='city-region'> Region / City </h4></div> <div class='col-3 col-md-3 text-right'>  <h4> Count </h4> </div>";
    }

    details += "</div>";
    for (let region_id in data.results.regions) {
        if (data.results.regions.hasOwnProperty(region_id)) {
            let region = data.results.regions[region_id];

            if (type !== 2) {
                details += "<div>";
                details += "<div class='row gt-name-distribution-map-display-toggle'>";
                details += "<div class='col-9 col-md-9  font-weight-bold'> <a id='region-name'  href='javascript:void(0);' data-map_code='" + region.map_code + "'>  " + region.name_en + "</a> </div><div class='col-3 col-md-3 font-weight-bold text-right'>" + region.count + "</div>";
                details += "</div>";
            }

            print += "<tr><th>" + region.name_en + "</th><th>" + region.count + "</th><tr>";

            for (let mep_id in region.meps) {
                if (region.meps.hasOwnProperty(mep_id)) {
                    let mep = region.meps[mep_id];

                    details += "<div class='row'>";
                    details += "<div class='city-name col-9 col-md-9'>" + mep.name_cz + "</div><div class='col-3 col-md-3 text-right'>" + mep.count + "</div></a>";
                    details += "</div>";
                    print += "<tr><td>" + mep.name_cz + "</td><td>" + mep.count + "</td></tr>";

                    gt_map_data_cities.push({
                        name: mep.name_cz,
                        coords: {
                            lat: parseFloat(mep.lat),
                            lng: parseFloat(mep.lng)
                        }, count: parseInt(mep.count)
                    });
                }
            }

            details += "</div>";
            details += "</div>";

            gt_map_data_regions.push([{
                v: region.map_code,
                f: region.name_en
            }, parseInt(region.count)]);
        }
    }

    if (type === 0) {
        details += "<div class='row'>";
        details += "<div id='total' class='col-9 col-md-9 font-weight-bold'> Total </div> <div class='col-3 col-md-3 font-weight-bold text-right'>  " + data.results.count + " </div>";
        details += "</div>";
    }

    print += "</table>";
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_PRINT_OUTPUT).html(print);
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_REGION_CHECKBOX).prop('disabled', false);
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_MEP_CHECKBOX).prop('disabled', false);

    if ($(GT_SELECTOR.NAME_DISTRIBUTION_MAP_REGION_CHECKBOX).prop('checked')) {
        gt_map_regions(gt_map_data_regions);
    } else if ($(GT_SELECTOR.NAME_DISTRIBUTION_MAP_MEP_CHECKBOX).prop('checked')) {
        gt_map_cities(gt_map_data_cities);
    }

    //copy map to print
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_PRINT_OUTPUT).append($("gt-name-distribution-map-nmap").clone());

    if (type === 2) {
        document.getElementById("gt-name-distribution-map-region-input").value = data.results.region_id === undefined ? "0" : data.results.region_id;
    }

    return details;
}

//#endregion
