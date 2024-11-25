<?php
require_once("function.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $local = $_POST['id'] ?? null;

    $getLocalCoords = getLocalCoords($local);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/indexstyle.css">
    <title>weatherClient</title>
</head>
<body>
<form method="POST">
    <div id="logo"><h2>weatherClient</h2></div>
    <div class="search-container">
        <div class="search-bar">
            <input type="text" name="id" placeholder="Ex: Lisbon" class="search-input" autocomplete="off" spellcheck="false" required>
            <button type="submit" class="search-button">Search</button>
        </div>
    </div>
</form>
<br>
<p><?php echo $getLocalCoords['name'];?></p>
<p><?php echo $getLocalCoords['country'];?></p>
<p><?php echo $getLocalCoords['lat'];?></p>
<p><?php echo $getLocalCoords['lon'];?></p>
</body>
</html>
