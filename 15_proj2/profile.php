<?php
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
$users = $db["users"][0];

// Retrieve the search term and orderby term
$old_username = $_POST["oldusername"];
$new_username = $_POST["newusername"];
$firstname = $_POST["firstname"];
$lastname = $_POST["lastname"];
$profile_pic = $_POST["profilepic"];
$password = $_POST["password"];

// Check the username and make the output
if (array_key_exists(strtolower($old_username), $users)) {
    $output["success"] = "yes";
    $output["contents"] = $users;

    if ($password == $output["contents"][strtolower($old_username)]["password"]) {
        // Edit corresponding JSON obj
        $output["contents"][strtolower($old_username)]["firstname"] = $firstname;
        $output["contents"][strtolower($old_username)]["lastname"] = $lastname;
        $output["contents"][strtolower($old_username)]["profilepic"] = $profile_pic;

        // Check username changed or not
        if ($old_username != $new_username) {
            $db["users"][0][strtolower($new_username)] = $output["contents"][strtolower($old_username)];
            unset($db["users"][0][strtolower($old_username)]);
            $output["contents"] = $db["users"][0][strtolower($new_username)];
        } else {
            $db["users"][0][strtolower($old_username)] = $output["contents"][strtolower($old_username)];
            $output["contents"] = $db["users"][0][strtolower($old_username)];
        }
        // Update the record
        file_put_contents("db.json", json_encode($db, JSON_PRETTY_PRINT));
    } else {
        $output["success"] = "incorrect";
        $output["contents"] = "";
    }
} else {
    $output["success"] = "no";
    $output["contents"] = "";
}

header("content-type: application/json");

print json_encode($output);
?>
