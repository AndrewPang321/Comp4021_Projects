<?php
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
// Get the JSON "contents" list
$users = $db["users"][0];

// Check the username and make the output
if (array_key_exists(strtolower(trim($_GET["username"])), $users)) {
    $output["available"] = "yes";
    $output["contents"] = $users[strtolower(trim($_GET["username"]))]["contents"][0];
} else {
    $output["available"] = "no";
    $output["contents"] = "";
}

header("content-type: application/json");

print json_encode($output);
?>
