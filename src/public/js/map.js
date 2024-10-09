let gt_map_data_regions;
let gt_map_data_cities;
let gt_gmap;
let gt_gmap_circles;
let gt_gmap_marker;
// Info window after clicking on city
let gt_gmap_info_window = new google.maps.InfoWindow();
// Red circle after clicking on city
let gt_gmap_override_circle = _gt_map_circle_create_new(null, null);

// Google API from WP injects
google.charts.load('current', {
        packages: ['geochart'],
        callback: function () {
            gt_map_regions([['Regions', 'Count']])
        }
    }
);

$(document).ready(function ($) {
    // Display map results as regions
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_REGION_CHECKBOX).prop("checked", true).change(function () {
        if (gt_map_data_regions !== undefined) {
            gt_map_regions(gt_map_data_regions);
        }
    });
    // Display map results as city points
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_MEP_CHECKBOX).prop("checked", false).change(function () {
        if (gt_map_data_cities !== undefined) {
            gt_map_cities(gt_map_data_cities);
        }
    });
});

//#region FUNCTIONS

/**
 * Draws the city map (google maps api) from given data.
 * @param {Object[]} data the map data
 */
function gt_map_cities(data) {
    // Remove (reset) circles
    for (let circle in gt_gmap_circles)
        gt_gmap_circles[circle].setMap(null);

    // Remove (reset) info box and info circle
    gt_gmap_info_window.setMap(null);
    gt_gmap_override_circle.setMap(null);

    // Remove (reset) markers
    if (gt_gmap_marker !== undefined)
        gt_gmap_marker.setMap(null);

    gt_gmap_circles = [];
    let center = {lat: 49.822, lng: 15.914}; // Default parameters to zoom and position the map to see the entire Czech Republic
    let zoom = 6;

    // Create map if not defined
    if (gt_gmap === undefined)
        gt_gmap = _gt_map_create_new(zoom, center);

    // If details are specified, change the map location.
    if (typeof data.details !== 'undefined') {
        center = data.details.coords;
        zoom = 10;
    } else if (data.length === 1) { // Exactly 1 city...
        center = data[0].coords;
        zoom = 9;
    }

    // Set the zoom settings
    gt_gmap.setZoom(zoom);
    gt_gmap.setCenter(center);

    // If details are not specified...
    if (typeof data.details === 'undefined') {

        for (let city in data) {
            if (city === "total")
                continue;

            // Add the circle for this city to the map.
            let circle = _gt_map_circle_create_new(gt_gmap, data[city].coords);
            gt_gmap_circles.push(circle);

            (function (circle, data, city) {
                google.maps.event.addListener(circle, 'click', function (event) {
                    gt_gmap_override_circle.setOptions({
                        map: gt_gmap,
                        center: circle.getCenter(),
                        fillColor: '#FF0000',
                        strokeColor: '#FF0000',
                        zIndex: 200
                    });
                    gt_gmap_info_window.setContent(data[city].name + " : " + data[city].count);
                    gt_gmap_info_window.setPosition(circle.getCenter());
                    gt_gmap_info_window.open(gt_gmap);
                });
            })(circle, data, city);
        }
    }
    // Otherwise, details are specified...
    else {
        // Marker for city details
        gt_gmap_marker = new google.maps.Marker({
            position: data.details.coords,
            map: gt_gmap,
            title: data.details.name
        });
    }

    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_GMAP_DIV).removeClass("d-none");
    $(GT_SELECTOR.NAME_DISTRIBUTION_NMAP_DIV).addClass("d-none");
}

/**
 * Draws the region map (google charts api) from given data.
 * @param {Object[]} data the map data
 * @param {boolean} hide_legend hides hover legend if true
 */
function gt_map_regions(data, hide_legend = false) {
    let map_data = google.visualization.arrayToDataTable(data);
    let options = {
        displayMode: 'regions',
        region: 'CZ',
        resolution: 'provinces',
        datalessRegionColor: 'transparent',
        colorAxis: {colors: ['#e6f9ff', '#80dfff', '#1ac6ff', '#0086b3']},
        magnifyingGlass: {enable: true, zoomFactor: 5.0}
    };

    if (hide_legend) {
        options["legend"] = 'none';
    }

    $(GT_SELECTOR.NAME_DISTRIBUTION_NMAP_DIV).removeClass("d-none");
    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_GMAP_DIV).addClass("d-none");

    let geomap = new google.visualization.GeoChart(document.getElementById('gt-name-distribution-map-nmap'));

    geomap.draw(map_data, options);

    // Add click listener on name region map
    if (!hide_legend) {
        google.visualization.events.addListener(geomap, 'regionClick', function (event) {
            //expand new selection
            $('a[data-map_code="' + event.region + '"]').click();
        });
    }
}

/**
 * Create a new google map with specific configuration
 * @returns {google.maps.Map}
 * @private
 */
function _gt_map_create_new(zoom, center) {
    return new google.maps.Map(
        document.getElementById(GT_SELECTOR.NAME_DISTRIBUTION_MAP_GMAP_DIV.substring(1)),
        {
            zoom: zoom,
            minZoom: 6,
            center: center,
            disableDefaultUI: true,
            zoomControl: true,
            tilt: 0
        });
}

/**
 * Create a new map circle with specific configuration
 * @returns {google.maps.Circle}
 * @private
 */
function _gt_map_circle_create_new(map, center) {
    return new google.maps.Circle({
        strokeColor: '#0000FF',
        strokeOpacity: 0.8,
        strokeWeight: 1,
        fillColor: '#0000FF',
        fillOpacity: 0.35,
        map: map,
        clickable: true,
        center: center,
        radius: 2000
    });
}

//#endregion
