<?php
function getJsonData($city) {
    $city = str_replace(" ", "%20", $city);

    $url = "https://nominatim.openstreetmap.org/search?q=$city&format=json&addressdetails=1&limit=1";
        $options = [
            'http' => [
            'user_agent' => 'cotamilhas',
            ],
        ];
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === "[]")
        return null;

    return $response;
}
