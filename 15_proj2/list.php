<?php
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
// Get the JSON "contents" list
$contents = $db["contents"][0];

header("content-type: application/json");

print json_encode($contents);
?>
