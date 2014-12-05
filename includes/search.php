<?php

    require(__DIR__ . "/../includes/config.php");

    // numerically indexed array of places
    $places = [];

    // searches database using WITH QUERY EXPANSION to order results by relevance.
    $matches = query("SELECT * FROM places WHERE MATCH (postal_code, place_name, admin_name1, admin_code1, country_code) AGAINST (? WITH QUERY EXPANSION) LIMIT 20", $_GET["geo"]);
    
    foreach ($matches as $match)
    {
        array_push($places, $match);
    }
    // output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($places, JSON_PRETTY_PRINT));

?>
