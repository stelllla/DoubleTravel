(function() {
    var $relatedCountries = document.getElementsByClassName('showRelatedCountries')[0];

    $relatedCountries.addEventListener('click', function () {
        if ($relatedCountries.classList.contains('active')) {
            $relatedCountries.classList.remove('active');
        } else {
            $relatedCountries.classList.add('active');
        }

        var $countriesDiv = document.getElementById('relatedCountries');
        if ($countriesDiv.classList.contains('hidden')) {
            $countriesDiv.classList.remove('hidden');
        } else {
            $countriesDiv.classList.add('hidden');
        }
    });

    var $currName = document.getElementById('selectedCountryCapital');
    var $itemsDiv = document.getElementById('items');
    if (!$currName.innerText) {
        $itemsDiv.classList.add('hidden');
    } else {
        $itemsDiv.classList.remove('hidden');
    }

    var $showWhether = document.getElementById('showWhether');
    $showWhether.addEventListener('click', function() {

        if($showWhether.classList.contains('active')) {
            $showWhether.classList.remove('active');
        } else {
            $showWhether.classList.add('active');
        }


        var $whether = document.getElementById('whetherContainer');

        if($whether.classList.contains('hidden')) {
            $whether.classList.remove('hidden');
        } else {
            $whether.classList.add('hidden');
        }
    });

    // inizialize Google Map
    var map;
    var geoJSON;
    var request;
    var gettingData = false;
    var openWeatherMapKey = "ea9aaebdbadf339a42046a3a38a53e67";

    var lat = (document.getElementById('lat')).innerHTML;
    var lng = (document.getElementById('lng')).innerHTML;
    var center = new google.maps.LatLng(lat,lng);

    function initialize() {
        var mapOptions = {
            zoom: 4,
            center: center
        };
        map = new google.maps.Map(document.getElementById('googleMap'),
            mapOptions);

        var marker = new google.maps.Marker({position: center});
        map.panTo(center);
        marker.setMap(map);

        addMapEventListeners();
    }

    var addMapEventListeners = function() {
        // Add interaction listeners to make weather requests
        google.maps.event.addListener(map, 'idle', checkIfDataRequested);
        // Sets up and populates the info window with details
        map.data.addListener('click', function(event) {
            infowindow.setContent(
                "<img src=" + event.feature.getProperty("icon") + ">"
                + "<br /><strong>" + event.feature.getProperty("city") + "</strong>"
                + "<br />" + event.feature.getProperty("temperature") + "&deg;C"
                + "<br />" + event.feature.getProperty("weather")
            );
            infowindow.setOptions({
                position:{
                    lat: event.latLng.lat(),
                    lng: event.latLng.lng()
                },
                pixelOffset: {
                    width: 0,
                    height: -15
                }
            });
            infowindow.open(map);
        });
    };

    var checkIfDataRequested = function() {
        // Stop extra requests being sent
        while (gettingData === true) {
            request.abort();
            gettingData = false;
        }
        getCoords();
    };
    // Get the coordinates from the Map bounds
    var getCoords = function() {
        var bounds = map.getBounds();
        var NE = bounds.getNorthEast();
        var SW = bounds.getSouthWest();
        getWeather(NE.lat(), NE.lng(), SW.lat(), SW.lng());
    };
    // Make the weather request
    var getWeather = function(northLat, eastLng, southLat, westLng) {
        gettingData = true;
        var requestString = "http://api.openweathermap.org/data/2.5/box/city?bbox="
            + westLng + "," + northLat + "," //left top
            + eastLng + "," + southLat + "," //right bottom
            + map.getZoom()
            + "&cluster=yes&format=json"
            + "&APPID=" + openWeatherMapKey;
        request = new XMLHttpRequest();
        request.onload = proccessResults;
        request.open("get", requestString, true);
        request.send();
    };
    // Take the JSON results and proccess them
    var proccessResults = function() {
        console.log(this);
        var results = JSON.parse(this.responseText);
        if (results.list.length > 0) {
            resetData();
            for (var i = 0; i < results.list.length; i++) {
                geoJSON.features.push(jsonToGeoJson(results.list[i]));
            }
            drawIcons(geoJSON);
        }
    };
    var infowindow = new google.maps.InfoWindow();
    // For each result that comes back, convert the data to geoJSON
    var jsonToGeoJson = function (weatherItem) {
        var feature = {
            type: "Feature",
            properties: {
                city: weatherItem.name,
                weather: weatherItem.weather[0].main,
                temperature: weatherItem.main.temp,
                min: weatherItem.main.temp_min,
                max: weatherItem.main.temp_max,
                humidity: weatherItem.main.humidity,
                pressure: weatherItem.main.pressure,
                windSpeed: weatherItem.wind.speed,
                windDegrees: weatherItem.wind.deg,
                windGust: weatherItem.wind.gust,
                icon: "http://openweathermap.org/img/w/"
                + weatherItem.weather[0].icon  + ".png",
                coordinates: [weatherItem.coord.lon, weatherItem.coord.lat]
            },
            geometry: {
                type: "Point",
                coordinates: [weatherItem.coord.lon, weatherItem.coord.lat]
            }
        };
        // Set the custom marker icon
        map.data.setStyle(function(feature) {
            return {
                icon: {
                    url: feature.getProperty('icon'),
                    anchor: new google.maps.Point(25, 25)
                }
            };
        });
        // returns object
        return feature;
    };
    // Add the markers to the map
    var drawIcons = function (weather) {
        map.data.addGeoJson(geoJSON);
        // Set the flag to finished
        gettingData = false;
    };
    // Clear data layer and geoJSON
    var resetData = function () {
        geoJSON = {
            type: "FeatureCollection",
            features: []
        };
        map.data.forEach(function(feature) {
            map.data.remove(feature);
        });
    };

    google.maps.event.addDomListener(window, 'load', initialize);

})();
