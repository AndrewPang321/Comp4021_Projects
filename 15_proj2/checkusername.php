<?php
header("content-type: application/json");

// Read the JSON file
$users = file_get_contents("db.json");
$users = json_decode($users, true);
$users = $users["users"][0];

// Check the username and make the output
if (array_key_exists(strtolower(trim($_GET["username"])), $users))
    $output["available"] = "no";
else
    $output["available"] = "yes";

print json_encode($output);
?>
