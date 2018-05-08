<?php
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
// Get the JSON "users" list
$user = $db["users"][0];

// Get and trim the input fields if necessary, also all lowercase
$username = strtolower(trim($_POST["username"]));
$firstname = strtolower(trim($_POST["firstname"]));
$lastname = strtolower(trim($_POST["lastname"]));
$password = $_POST["password"];
$confirm = $_POST["confirm"];

// Check the username
if (array_key_exists($username, $user))
    $output["error"] = "Duplicate username exists!";

// Check all fields
elseif (empty($username) || empty($firstname) ||
        empty($lastname) || empty($password))
    $output["error"] = "Not all data has been submitted!";

// Check all fields
elseif ($password != $confirm)
    $output["error"] = "Passwords do not match!";

// Add the user
else {
    // Append the new user in the JSON under "users"
    $user[$username]["firstname"] = $firstname;
    $user[$username]["lastname"] = $lastname;
    $user[$username]["password"] = $password;
    $user[$username]["profilepic"] = "";
    $user[$username]["contents"] = "";
    // Replace the new user list in the original JSON
    $db["users"][0] = $user;

    file_put_contents("db.json", json_encode($db, JSON_PRETTY_PRINT));

    // Set up the session
    session_start();
    $_SESSION["username"] = $username;

    $output["success"] = "";
}

header("content-type: application/json");

print json_encode($output);
?>
