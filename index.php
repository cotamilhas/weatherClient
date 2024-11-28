<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/indexstyle.css">
    <link rel="shortcut icon" href="./img/icon.ico" type="image/x-icon">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Leaflet JS and CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <title>weatherClient</title>
</head>
<?php
require_once("function.php");
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $local = $_POST['local'];

    if ($local) {
        $getLocalCoords = getLocalCoords($local);
        $getCurrentWeather = getCurrentWeather($getLocalCoords['lat'], $getLocalCoords['lon'], $getLocalCoords['boundingbox']);
    }
}
?>

<body>
    <div class="container">
        <header>
            <h1><a href="index.php">weatherClient</a></h1>
        </header>
        <main>
            <form method="POST" class="search-form">
                <div class="search-container">
                    <input type="text" name="local" placeholder="Search for a location (e.g., Lisbon)" class="search-input" autocomplete="off" spellcheck="false" required>
                    <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <div class="results">
                <?php if ($getLocalCoords): ?>
                    <div class="location-info">
                        <h2><i class="fas fa-map-marker-alt"></i> Location Info</h2>
                        <p><i class="fas fa-map-marker-alt"></i>
                            Location: <strong><?php echo $getLocalCoords['name']; ?></strong>
                        </p>
                        <p><i class="fas fa-flag"></i>
                            Country: <strong><?php echo $getLocalCoords['country']; ?></strong>
                        </p>
                        <p><i class="fas fa-globe"></i>
                            Latitude: <strong><?php echo $getLocalCoords['lat']; ?></strong>
                        </p>
                        <p><i class="fas fa-globe"></i>
                            Longitude: <strong><?php echo $getLocalCoords['lon']; ?></strong>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if ($getCurrentWeather): ?>
                    <div class="weather-info">
                        <h2><i class="fas fa-cloud-sun"></i> Current Weather</h2>
                        <p><i class="fas fa-thermometer-half"></i>
                            Temperature: <strong><?php echo $getCurrentWeather['temperature']; ?><?php echo $getCurrentWeather['temperature_unit']; ?></strong>
                        </p>
                        <p><i class="fas fa-tint"></i>
                            Humidity: <strong><?php echo $getCurrentWeather['humidity']; ?><?php echo $getCurrentWeather['humidity_unit']; ?></strong>
                        </p>
                        <p><i class="fas fa-temperature-high"></i>
                            Apparent Temperature: <strong><?php echo $getCurrentWeather['apparent_temperature']; ?><?php echo $getCurrentWeather['apparent_temperature_unit']; ?></strong>
                        </p>
                        <p><i class="fas fa-sun"></i>
                            Daytime: <strong><?php echo $getCurrentWeather['is_day'] ? 'Yes' : 'No'; ?></strong>
                        </p>
                        <p><i class="fas fa-cloud-rain"></i>
                            Precipitation: <strong><?php echo $getCurrentWeather['precipitation']; ?><?php echo $getCurrentWeather['precipitation_unit']; ?></strong>
                        </p>
                        <p><i class="fas fa-cloud"></i>
                            Cloud Cover: <strong><?php echo $getCurrentWeather['cloud_cover']; ?><?php echo $getCurrentWeather['cloud_cover_unit']; ?></strong>
                        </p>
                        <p><i class="fas fa-wind"></i>
                            Wind Speed: <strong><?php echo $getCurrentWeather['wind_speed']; ?><?php echo $getCurrentWeather['wind_speed_unit']; ?></strong>
                        </p>
                        <p><i class="fas fa-compass"></i>
                            Wind Direction: <strong><?php echo $getCurrentWeather['wind_direction']; ?><?php echo $getCurrentWeather['wind_direction_unit']; ?></strong>
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            <link rel="stylesheet" type="text/css" href="https://unpkg.com/leaflet@1.3.3/dist/leaflet.css">
            <script src='https://unpkg.com/leaflet@1.3.3/dist/leaflet.js'></script>
            <?php if ($getLocalCoords): ?>
                <div id="map"></div>
                <script>
                    var map = L.map('map').setView([<?php echo $getLocalCoords['lat'] . "," . $getLocalCoords['lon']; ?>], 15);

                    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    L.marker([<?php echo $getLocalCoords['lat'] . "," . $getLocalCoords['lon']; ?>]).addTo(map);

                    var geojsonData = <?php echo json_encode($getLocalCoords['geojson']); ?>;

                    if (geojsonData) {
                        L.geoJSON(geojsonData).addTo(map);
                    }
                </script>

            <?php endif; ?>
        </main>
        <footer>
            <p>&copy; <?php echo date('Y'); ?> weatherClient. All rights reserved.</p>
            <p>
                <a href="https://github.com/cotamilhas/weatherclient" target="_blank" class="github-link">
                    <i class="fab fa-github"></i> View on GitHub
                </a>
            </p>
        </footer>
    </div>
</body>

</html>