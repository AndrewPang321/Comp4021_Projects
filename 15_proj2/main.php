<?php

// Add the code to start the session
session_start();
// Add the code to check for signed in user
if (!isset($_SESSION["username"])) {
  header("Location: signin.php");
  exit;
}
// Read the JSON file
$db = file_get_contents("db.json");
$db = json_decode($db, true);
$users = $db["users"][0];
// Add the code to validate the user from the JSON file
if (!array_key_exists($_SESSION["username"], $users)) {
  // Do something if the username does not exist!
  header("Location: signin.php");
  exit;
}
// Read the first name of the user
$firstname = $users[$_SESSION["username"]]["firstname"];

?>
<!DOCTYPE html>
<html>
<head>
    <title>Lab 7: Main Page</title>
    <meta charset="utf-8">
    <meta name="viewport" 
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> 
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">
</head>
<body class="bg-primary p-5">
  <div class="container rounded bg-white" style="width: 20rem">
    <div class="row">
      <div class="col p-3 text-center">
        <h4>Main Page</h4>
      </div>
    </div>
    <div class="row">
      <div class="col text-center mb-3">
        <i class="far fa-smile"></i>
        Hi, <?= htmlspecialchars($firstname) ?>!
      </div>
    </div>
    <div class="row">

      <div class="col">
        <form id="signoutForm" action="signout.php">
          <div class="form-group text-center p-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-sign-out-alt mr-2"></i> Sign Out</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</body>
</html>
