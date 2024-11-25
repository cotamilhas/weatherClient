<?php
function getJSONContent($url, $options)
{
    $data = @file_get_contents($url, false, $options);
    if ($data === false) {
        return null;
    }
    return json_decode($data, true);
}

function getLocalCoords($city)
{
    $city = str_replace(" ", "%20", $city);

    $url = "https://nominatim.openstreetmap.org/search?q=$city&format=json&addressdetails=1&limit=1";
    $options = ['http' => ['user_agent' => 'cotamilhas']];

    $context = stream_context_create($options);
    $json = getJSONContent($url, $context);

    if ($json === "[]")
        return null;

    $lat = $json[0]['lat'] ?? null;
    $lon = $json[0]['lon'] ?? null;
    $name = $json[0]['name'] ?? null;
    $country = $json[0]['address']['country'] ?? null;

    return [
        'lat' => $lat,
        'lon' => $lon,
        'name' => $name,
        'country' => $country
    ];
}
