<?php
// getting json content
function getJSONContent($url, $options)
{
    $data = @file_get_contents($url, false, $options);
    if ($data === false) {

        return null;
    }

    return json_decode($data, true);
}

// getting local coords
function getLocalCoords($local)
{
    $local = urlencode($local);

    $url = "https://nominatim.openstreetmap.org/search?q=$local&format=json&addressdetails=1&limit=1&accept-language=en-gb";
    $options = [
        'http' => [
            'header' => "User-Agent: cotamilhas\r\n"
        ]
    ];
    $context = stream_context_create($options);

    $json = getJSONContent($url, $context);

    if (empty($json) || !isset($json[0])) {
        return null;
    }

    $lat = $json[0]['lat'] ?? null;
    $lon = $json[0]['lon'] ?? null;
    $name = $json[0]['name'] ?? null; 
    $country = $json[0]['address']['country'] ?? null;

    return [
        'lat' => $lat,
        'lon' => $lon,
        'name' => $name,
        'country' => $country,
        'url' => $url // DEBUG
    ];
}

// getting local weather
function getCurrentWeather($lat, $lon)
{
    // Open Meteo API
    $url = "https://api.open-meteo.com/v1/forecast?latitude=$lat&longitude=$lon&current=temperature_2m,relative_humidity_2m,apparent_temperature,is_day,precipitation,rain,showers,snowfall,weather_code,cloud_cover,pressure_msl,wind_speed_10m,wind_direction_10m&timeformat=unixtime";
    $options = [
        'http' => [
            'header' => "User-Agent: cotamilhas\r\n",
            'timeout' => 10
        ]
    ];
    $context = stream_context_create($options);

    $json = getJSONContent($url, $context);

    if (empty($json) || !isset($json['current'])) {
        return null;
    }
    // Units
    $currentUnits = $json['current_units'];
    $temperature_unit = $currentUnits['temperature_2m'] ?? null;
    $humidity_unit = $currentUnits['relative_humidity_2m'] ?? null;
    $apparent_temperature_unit = $currentUnits['apparent_temperature'] ?? null;
    $precipitation_unit = $currentUnits['precipitation'] ?? null;
    $cloud_cover_unit = $currentUnits['cloud_cover'] ?? null;
    $pressure_unit = $currentUnits['pressure_msl'] ?? null;
    $wind_speed_unit = $currentUnits['wind_speed_10m'] ?? null;
    $wind_direction_unit = $currentUnits['wind_direction_10m'] ?? null;
    // Values
    $current = $json['current'];
    $temperature = $current['temperature_2m'] ?? null;
    $humidity = $current['relative_humidity_2m'] ?? null;
    $apparent_temperature = $current['apparent_temperature'] ?? null;
    $is_day = $current['is_day'] ?? null;
    $precipitation = $current['precipitation'] ?? null;
    $cloud_cover = $current['cloud_cover'] ?? null;
    $pressure = $current['pressure_msl'] ?? null;
    $wind_speed = $current['wind_speed_10m'] ?? null;
    $wind_direction = $current['wind_direction_10m'] ?? null;

    return [
        // Values
        'temperature' => $temperature,
        'humidity' => $humidity,
        'apparent_temperature' => $apparent_temperature,
        'is_day' => $is_day,
        'precipitation' => $precipitation,
        'cloud_cover' => $cloud_cover,
        'pressure' => $pressure,
        'wind_speed' => $wind_speed,
        'wind_direction' => $wind_direction,
        // Units
        'temperature_unit' => $temperature_unit,
        'humidity_unit' => $humidity_unit,
        'apparent_temperature_unit' => $apparent_temperature_unit,
        'precipitation_unit' => $precipitation_unit,
        'cloud_cover_unit' => $cloud_cover_unit,
        'pressure_unit' => $pressure_unit,
        'wind_speed_unit' => $wind_speed_unit,
        'wind_direction_unit' => $wind_direction_unit,
        'url' => $url // DEBUG
    ];
}