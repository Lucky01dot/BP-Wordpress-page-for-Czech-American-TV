var map;
var markers = [];
var mapVisible = false;
var keepMarkers = false;
var lastCity = ""; // Uchovává poslední hodnotu outputu
var checkInterval = 1000; // Kontrolovat každou sekundu

jQuery(document).ready(function ($) {
    function initMap(lat = 50.0755, lng = 14.4378) {
        map = L.map('map_cz').setView([lat, lng], 8);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> contributors'
        }).addTo(map);
    }

    function findCityCoordinates(cityName) {
        if (!cityName) return;

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${cityName}, Czech Republic`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    var lat = parseFloat(data[0].lat);
                    var lon = parseFloat(data[0].lon);

                    if (!map) {
                        initMap(lat, lon);
                    } else {
                        map.setView([lat, lon], 10);
                    }

                    if (!keepMarkers) {
                        markers.forEach(marker => marker.remove());
                        markers = [];
                    }

                    var newMarker = L.marker([lat, lon]).addTo(map)
                        .bindPopup(`<b>${cityName}</b>`).openPopup();
                    markers.push(newMarker);
                }
            })
            .catch(error => console.error('Chyba při hledání města:', error));
    }

    $('#gt-show-on-map').click(function () {
        var cityName = $('#gt-german-terminology-german-city-cz-output').val();

        if (!mapVisible) {
            $('#map-container').fadeIn();
            $(this).text('❌ Zavřít mapu');
            if (!map) {
                initMap();
            }
            findCityCoordinates(cityName);
            mapVisible = true;
        } else {
            $('#map-container').fadeOut();
            $(this).text('📍 Zobrazit na mapě');
            mapVisible = false;
        }
    });

    $('#gt-reset-map').click(function () {
        markers.forEach(marker => marker.remove());
        markers = [];
        map.setView([50.0755, 14.4378], 8);
    });



    $('#gt-toggle-markers').click(function () {
        keepMarkers = !keepMarkers;
        $(this).toggleClass('btn-secondary btn-success');
        $(this).text(keepMarkers ? '📍 Zachovat body ✅' : '📍 Ponechat body');
    });

    // Timer na kontrolu změn v outputu
    setInterval(function () {
        var cityName = $('#gt-german-terminology-german-city-cz-output').val();
        if (mapVisible && cityName && cityName !== lastCity) {
            lastCity = cityName; // Uložíme novou hodnotu
            findCityCoordinates(cityName); // Aktualizujeme mapu
        }
    }, checkInterval);
});

