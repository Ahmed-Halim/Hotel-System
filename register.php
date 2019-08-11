<?php
session_start();

// if user is alreay login then redirect hin to home page
if ( isset($_SESSION['user_id']) || isset($_COOKIE['user_id']) ) {
  header("refresh:0;url=home.php" );
  die;
}
?>

<?php include("general.php"); ?>

<?php

global $data;
$data["page_title"] = "Register";

$success = false;

if (!empty($_POST)) {
  if (validate_registration()) {
    $success = true;
    header( "refresh:3;url=home.php" );
  }
}

function validate_registration() {
  global $failed;
  $RegexOnlyCharacters = "/^[a-zA-Z ]+$/";
  $RegexOnlyPhone = "/^01[012][0-9]{8}/";
  $RegexOnlyEmail = "/\S+@\S+\.\S+/";

  if (!isset($_POST["FirstName"]) || empty($_POST["FirstName"]) || !preg_match($RegexOnlyCharacters, $_POST["FirstName"])) {
    $failed = "First name must not be empty or have numbers";
    return false;
  }
  if (!isset($_POST["LastName"]) || empty($_POST["LastName"])|| !preg_match($RegexOnlyCharacters, $_POST["LastName"])) return false;
  if (!isset($_POST["Email"]) || empty($_POST["Email"])|| !preg_match($RegexOnlyEmail, $_POST["Email"])) return false;
  if (!isset($_POST["Password"]) || empty($_POST["Password"]) || strlen($_POST["Password"]) < 8) return false;
  if (!isset($_POST["Country"]) || empty($_POST["Country"]) || !preg_match($RegexOnlyCharacters, $_POST["Country"])) return false;
  if (!isset($_POST["Phone"]) || empty($_POST["Phone"]) || !preg_match($RegexOnlyPhone, $_POST["Phone"])) return false;

  $fname = $_POST["FirstName"];
  $lname = $_POST["LastName"];
  $email = $_POST["Email"];
  $password = md5($_POST["Password"]);
  $country = $_POST["Country"];
  $phone = $_POST["Phone"];

  return create_account($fname , $lname, $email, $password, $country, $phone);
}

function create_account($fname , $lname, $email, $password, $country, $phone, $role = "customer") {
  global $DB;
  global $failed;

  //Check if email exist
  $stmt = $DB->prepare("SELECT * FROM user WHERE email = ?");
  $stmt->bindParam(1, $email);
  $stmt -> execute();
  if ($stmt->fetch()) {
      $failed = "This email is already registed";
      return false;
  }

  //add new user to database
  $stmt = $DB->prepare("INSERT INTO user (first_name, last_name, email, password, country, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bindParam(1, $fname);
  $stmt->bindParam(2, $lname);
  $stmt->bindParam(3, $email);
  $stmt->bindParam(4, $password);
  $stmt->bindParam(5, $country);
  $stmt->bindParam(6, $phone);
  $stmt->bindParam(7, $role);
  return $stmt->execute();
}

function getCountryList() {
  $res = "";
  $countries = array('Afghanistan','Albania','Algeria','American Samoa','Andorra','Angola','Anguilla','Antarctica','Antigua','Argentina','Armenia','Aruba','Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia','Botswana','Bouvet Island','Brazil','British Indian Ocean Territory','Brunei Darussalam','Bulgaria','Burkina Faso','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Cayman Islands','Central African Republic','Chad','Chile','China','Christmas Island','Cocos (Keeling) Islands','Colombia','Comoros','Congo','Congo','Cook Islands','Costa Rica','Croatia','Cuba','Cyprus','Czech Republic','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Ethiopia','Falkland Islands','Faroe Islands','Fiji','Finland','France','France','French Guiana','French Polynesia','French Southern Territories','Gabon','Gambia','Georgia','Germany','Ghana','Gibraltar','Greece','Greenland','Grenada','Guadeloupe','Guam','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Holy See','Honduras','Hong Kong','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Korea','Kuwait','Kyrgyzstan','Latvia','Lebanon','Lesotho','Liberia','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Martinique','Mauritania','Mauritius','Mayotte','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Montserrat','Morocco','Mozambique','Myanmar','Namibia','Nauru','Nepal','Netherlands','Netherlands Antilles','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Niue','Norfolk Island','Northern Mariana Islands','Norway','Oman','Pakistan','Palau','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Pitcairn','Poland','Portugal','Puerto Rico','Qatar','Reunion','Romania','Russian Federation','Rwanda','Saint Kitts And Nevis','Saint Lucia','Saint Vincent And The Grenadines','Samoa','San Marino','Sao Tome And Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Georgia','Spain','Sri Lanka','St. Helena','St. Pierre And Miquelon','Sudan','Suriname','Svalbard And Jan Mayen Islands','Swaziland','Sweden','Switzerland','Syrian Arab Republic','Taiwan','Tajikistan','Tanzania','Thailand','Togo','Tokelau','Tonga','Trinidad And Tobago','Tunisia','Turkey','Turkmenistan','Turks And Caicos Islands','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','United States Minor Outlying Islands','Uruguay','Uzbekistan','Vanuatu','Venezuela','Viet Nam','Virgin Islands (British)','Virgin Islands (U.S.)','Wallis And Futuna Islands','Western Sahara','Yemen','Yugoslavia','Zambia','Zimbabwe');
  foreach ($countries as $country) {
    $res .= '<option value="'.$country.'">' . $country . '</option>
                    ';
  }
  return $res;
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
        <div class="col-sm-12 col-md-5">
          <div id="left-section">
            <img alt="" id="UserImg" src="images/user.png">
            <h4>Already a member?</h4>
            <a title="" id="green-btn" href="./login.php">Login</a>
          </div>
        </div>

        <div class="col-sm-12 col-md-7">
          <div id="WhitePanel">
          <? if ($success): ?>
            <div class="text-center success_registeration_msg">
              <img alt="" id="DoneImg" src="images/done.png">
              <h4>You have successfully registered</h4>
              <p>You will be redirected after 3s</p>
            </div>
          <? elseif (isset($failed)) : ?>
            <div class="text-center failed_registeration_msg">
              <img alt="" id="ErrorImg" src="images/Error.png">
              <h4><?php echo $failed; ?></h4>
            </div>
          <? else: ?>

            <div class="text-center m-b-30"><h4>Create new account</h4></div>

            <form autocomplete="off" name="register" action="register.php" method="POST">
              <div id="Error"></div>
              <div class="row">
                <div class="col-sm-6">
                  <input name="FirstName" type="text" id="FirstName" placeholder="First Name"/>
                </div>
                <div class="col-sm-6">
                  <input name="LastName" type="text" id="LastName" placeholder="Last Name"/>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <input name="Email" type="text" id="Email" placeholder="Email"/>
                </div>
                <div class="col-sm-6">
                  <input name="Password" type="password" id="Password" placeholder="Password"/>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6">
                  <select name="Country" id="Country">

                    <option value="">Country</option>
                    <?php echo getCountryList(); ?>

                  </select>
                </div>
                <div class="col-sm-6">
                  <input name="Phone" type="text" id="Phone" placeholder="Phone"/>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-12">
                  <input type="submit" id="register-btn" value="Sign up"/>
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
