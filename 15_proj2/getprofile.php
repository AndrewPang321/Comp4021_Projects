<?php
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
$users = $db["users"][0];

// Retrieve the search term and orderby term
$username = $_GET["username"];

// Check the username and make the output
if (array_key_exists(strtolower($username), $users)) {
    $output["success"] = "yes";
    $output["contents"] = $users[strtolower($username)];
} else {
    $output["success"] = "no";
    $output["contents"] = "";
}

header("content-type: application/json");

print json_encode($output);
?>
