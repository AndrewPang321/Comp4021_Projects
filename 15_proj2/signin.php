<?php
// Add the code to start the session
session_start();
// Add the code to avoid double sign in
if (isset($_SESSION["username"])) {
  header("Location: main.php");
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lab 7: Sign In Form</title>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">
    <script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="jquery-cookie-master/src/jquery.cookie.js" type="text/javascript"></script>
    <script>
    $(document).ready(function() {

      var remember = $.cookie('remember');
      if (remember == 'true')
      {
          var uname = $.cookie('uname');
          var password = $.cookie('password');
          // autofill the fields
          $('#username').val(uname);
          $('#password').val(password);
          if ($.cookie('remember'))
          {
            document.getElementById("RM").checked = true;
          }
      }
        // Add the code to submit the form via AJAX
        $(".signinForm").on("submit", () => {
          // AJAX submit the form
          var query = $(".signinForm").serialize();
          $.post("signinuser.php", query, (data) => {
            if (data.error) {
              $("#signinError").text(data.error);
              $("#signinError").show();
            } else {
              if (document.getElementById("RM").checked) {
                var uname = $('#username').val();
                var password = $('#password').val();

                // set cookies to expire in 14 days
                $.cookie('uname', uname, { expires: 14 });
                $.cookie('password', password, { expires: 14 });
                $.cookie('remember', true, { expires: 14 });
              }
              else
              {
                // reset cookies
                $.cookie('uname', null);
                $.cookie('password', null);
                $.cookie('remember', null);
              }
              window.location = "main.php";
            }
          }, "json");

          return false;
        });

        // Add the code to handle the register button
        $("#registerButton").on("click", function() {
          window.location = "register.html";
        });

    });
    </script>
    <style>
      html,
      body {
        height: 100%;
      }

      body {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: center;
        align-items: center;
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .signinForm {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
      }
      .signinForm .checkbox {
        font-weight: 400;
      }
      .signinForm .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
      }
      .signinForm .form-control:focus {
        z-index: 2;
      }
    </style>
</head>
<body class="text-center">
  <form class="signinForm">
    <!-- <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72"> -->
    <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>

    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fas fa-user"></i></div>
        </div>
        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required autofocus>
      </div>
    </div>
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text"><i class="fas fa-key"></i></div>
        </div>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
      </div>
    </div>
    <div class="checkbox mb-3">
      <label>
        <input type="checkbox" value="remember-me" id="RM"> Remember me
      </label>
    </div>
    <div class="g-recaptcha" data-sitekey="6Ldd91cUAAAAAAaDUOpOlWsbgW_ZF37CbjLWT3M8"></div>
    <div class="form-group text-center">
      <button type="submit" class="btn btn-lg btn-primary btn-block"><i class="fas fa-sign-in-alt mr-2"></i> Sign In</button>
    </div>
    <div id="signinError" class="form-group text-center text-danger" style="display: none">
      <i class="fas fa-times"></i> <span>Sign in error!</span>
    </div>
    <hr>
    <div class="form-text text-center mb-2">
      Don't have an account?
    </div>
    <div class="form-group text-center pb-2">
      <button id="registerButton"
              type="button" class="btn btn-lg btn-info btn-block"><i class="fas fa-clipboard-list mr-2"></i> Register</button>
    </div>
    <p class="mt-5 mb-3 text-muted">&copy; COMP4021 Group 15</p>
  </form>
</body>
</html>
