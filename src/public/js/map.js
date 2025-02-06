let gt_map_data_regions;
let gt_map_data_cities;
let gt_gmap;


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
 * Draws the city map (leaflet api) from given data.
 * @param {Object[]} data the map data
 */
function gt_map_cities(data) {
    // Pokud již existuje mapa, odstraníme ji
    if (gt_gmap) {
        gt_gmap.remove();
    }

    // Definujeme výchozí centrum a zoom pro mapu
    let center = [49.822, 15.914]; // Souřadnice středu ČR
    let zoom = 6;

    // Pokud jsou specifikovány detaily, upravíme střed a zoom
    if (typeof data.details !== 'undefined') {
        center = [data.details.coords.lat, data.details.coords.lng];
        zoom = 10;
    } else if (data.length === 1) { // Pokud máme přesně jedno město
        center = [data[0].coords.lat, data[0].coords.lng];
        zoom = 9;
    }

    // Vytvoření nové Leaflet mapy
    gt_gmap = L.map('gt-name-distribution-map-gmap').setView(center, zoom);

    // Přidání OpenStreetMap jako podkladu mapy
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(gt_gmap);

    // Pokud nejsou specifikovány detaily, vykreslíme kruhy
    if (typeof data.details === 'undefined') {
        for (let city in data) {
            if (city === "total") continue;

            // Přidání kruhu pro město
            let circle = L.circle([data[city].coords.lat, data[city].coords.lng], {
                color: '#0000FF',
                fillColor: '#0000FF',
                fillOpacity: 0.35,
                radius: 2000
            }).addTo(gt_gmap);

            // Přidání popisku po kliknutí na kruh
            circle.bindPopup(`${data[city].name} : ${data[city].count}`);
        }
    } else {
        // Pokud máme detaily, přidáme marker
        L.marker([data.details.coords.lat, data.details.coords.lng])
            .addTo(gt_gmap)
            .bindPopup(data.details.name)
            .openPopup();
    }

    $(GT_SELECTOR.NAME_DISTRIBUTION_MAP_GMAP_DIV).removeClass("d-none");
    $(GT_SELECTOR.NAME_DISTRIBUTION_NMAP_DIV).addClass("d-none");

    setTimeout(() => {
        gt_gmap.invalidateSize();
    }, 100);

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

//#endregion
