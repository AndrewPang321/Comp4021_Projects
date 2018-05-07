<?php
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
$users = $db["users"][0];

// Retrieve the search term and orderby term
$title = $_GET["title"];
$year = $_GET["year"];
$poster = $_GET["poster"];

// Check the username and make the output
if (array_key_exists(strtolower(trim($_GET["username"])), $users)) {
    $output["success"] = "yes";
    $output["contents"] = $users[strtolower(trim($_GET["username"]))]["contents"][0];

    // Check the title is duplicated or not
    if ($output["contents"][$title] != NULL) {
        $output["success"] = "duplicate";
        $output["contents"] = "";
    } else {
        // Add corresponding JSON obj
        $output["contents"][$title]["name"] = $title;
        $output["contents"][$title]["year"] = $year;
        $output["contents"][$title]["image"] = $poster;    
        
        // Update the record
        $db["users"][0][strtolower(trim($_GET["username"]))]["contents"][0] = $output["contents"];
        file_put_contents("db.json", json_encode($db, JSON_PRETTY_PRINT));
    }
} else {
    $output["success"] = "no";
    $output["contents"] = "";
}

header("content-type: application/json");

print json_encode($output);
?>
