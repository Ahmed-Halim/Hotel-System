<?php

session_start();
//if user is already login and have session or cookie then redirect him to home page
if ( isset($_SESSION['user_id']) || isset($_COOKIE['user_id']) ) {
  header("refresh:0;url=home.php" );
  die;
}
?>

<?php include("general.php"); ?>

<?php

//put page title in array $data
global $data;
$data["page_title"] = "Login";

//status variable is request_login by default. based on its value the page will looks different
$status = "request_login";

function login() {
  $RegexOnlyEmail = "/\S+@\S+\.\S+/";
  //validate email and password
  if (!isset($_POST["Email"]) || empty($_POST["Email"])|| !preg_match($RegexOnlyEmail, $_POST["Email"])) return false;
  if (!isset($_POST["Password"]) || empty($_POST["Password"]) || strlen($_POST["Password"]) < 8) return false;

  //put it in variables and encrypt password using md5 function to prevent storing password in plain text in th database *for security purposes*
  $email = $_POST["Email"];
  $password = md5($_POST["Password"]);

  //send it to validate_login function which looks at the database and check whether it is exist or not
  return validate_login($email, $password);
}

function validate_login($email, $password) {
  global $DB;
  // prepare sql statment that check if given email an password belongs to any user in the system
  $stmt = $DB->prepare("SELECT * FROM user WHERE email = ? AND password = ?");
  $stmt->bindParam(1, $email);
  $stmt->bindParam(2, $password);
  $stmt->execute();
  return $stmt->fetch() ? true : false;
}

// if there is POST request then check and validate login. if it's authorized user then initiate a session and if remember me checkbox is checked create cookie
if (!empty($_POST)) {
  if (login()) {
    $status = "exist";
    $_SESSION['user_id'] = getUserID();
    if (isset($_POST["Remember"]) && ($_POST["Remember"] == '1' || $_POST["Remember"] == "on")) {
      setcookie('user_id', getUserID(), time() + 3600 * 24 * 30 * 1000 , "/");
    }
    header( "refresh:3;url=home.php" );
  } else {
    $status = "not_exist";
  }
}

//function return user id given email and password used in login
function getUserID() {
  global $DB;
  $email = $_POST["Email"];
  $password = md5($_POST["Password"]);
  $stmt = $DB->prepare("SELECT user_id FROM user WHERE email = ? AND password = ?");
  $stmt->bindParam(1, $email);
  $stmt->bindParam(2, $password);
  $stmt->execute();
  $row = $stmt->fetch();
  return $row["user_id"];
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("blog-info.php"); ?>
</head>

<body>
  <header>

    <div class="container">

      <?php include("header.php"); ?>

      <div class="row">
        <div class="col-sm-12 col-md-6">
          <div id="left-section">
            <img alt="" id="UserImg" src="images/user.png">
            <h4>Don't have an account?</h4>
            <a title="" id="green-btn" href="./register.php">Register</a>
          </div>
        </div>

        <div class="col-sm-12 col-md-6">
          <div id="WhitePanel">
          <? if ($status == "exist"): ?>

            <div class="text-center success_login_msg">
              <img alt="" id="DoneImg" src="images/done.png">
              <h4>You have succesfully login !</h4>
              <p>You will be redirected after 3s</p>
            </div>

          <? elseif ($status == "not_exist"): ?>

            <div class="text-center failed_login_msg">
              <img alt="" id="ErrorImg" src="images/error.png">
              <h4>Incorrect Email or Password</h4>
              <a href="login.php" id="green-btn">Try again</a>
            </div>

          <? elseif ($status == "request_login"): ?>

          <div class="text-center m-b-30"><h4>Login</h4></div>
          <form autocomplete="off" name="login" action="login.php" method="POST">
            <div id="Error"></div>
            <div class="row">
              <div class="col-sm-12">
                <input name="Email" type="text" id="Email" placeholder="Email"/>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <input name="Password" type="password" id="Password" placeholder="Password"/>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <input name="Remember" type="checkbox" id="Remember"/> Remember me for 30 day ?
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <input type="submit" id="login-btn" value="Login"/>
              </div>
            </div>
          </form>

          <? endif; ?>

          </div>
        </div>

      </div>
    </div>

  </header>

  <?php include("footer.php"); ?>

</body>

</html>
