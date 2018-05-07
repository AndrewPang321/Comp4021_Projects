<?php
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
$users = $db["users"][0];

// Retrieve the search term and orderby term
$old_title = $_GET["oldtitle"];
$new_title = $_GET["newtitle"];
$year = $_GET["year"];
$poster = $_GET["poster"];

// Check the username and make the output
if (array_key_exists(strtolower(trim($_GET["username"])), $users)) {
    $output["success"] = "yes";
    $output["contents"] = $users[strtolower(trim($_GET["username"]))]["contents"][0];

    // Edit corresponding JSON obj
    $output["contents"][$old_title]["name"] = $new_title;
    $output["contents"][$old_title]["year"] = $year;
    $output["contents"][$old_title]["image"] = $poster;
    $output["contents"][$new_title] = $output["contents"][$old_title];
    if ($old_title != $new_title) {
        unset($output["contents"][$old_title]);
    }
    
    
    // Update the record
    $db["users"][0][strtolower(trim($_GET["username"]))]["contents"][0] = $output["contents"];
    file_put_contents("db.json", json_encode($db, JSON_PRETTY_PRINT));
} else {
    $output["success"] = "no";
    $output["contents"] = "";
}

header("content-type: application/json");

print json_encode($output);
?>
