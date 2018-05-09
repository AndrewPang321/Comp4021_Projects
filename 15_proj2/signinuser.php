<?php
// Read the JSON file
$users = file_get_contents("db.json");
$users = json_decode($users, true);
$users = $users["users"][0];
require_once "recaptchalib.php";
// empty response
$response = null;
//reCAPTCHA secret key
$secret = "6Ldd91cUAAAAAIVf5OXUoovuzMZ1Ib6IFereLArs";
// check secret key
$reCaptcha = new ReCaptcha($secret);


if ($_POST["g-recaptcha-response"]) {
    $response = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
};


// Check the username and password and reCAPTCHA
if($response != NULL && $response -> success) {
    if (array_key_exists($_POST["username"], $users) &&
        $users[$_POST["username"]]["password"] == $_POST["password"])  {

        // Set up the session
        session_start();
        $_SESSION["username"] = $_POST["username"];

        $output["success"] = "";
    } else {
        $output["error"] = "Username/password is not correct!";
    }
}

header("content-type: application/json");

print json_encode($output);
?>
