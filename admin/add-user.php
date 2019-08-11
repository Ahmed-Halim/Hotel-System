<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Add User";

if (!empty($_POST)) {
    validate_user();
}

function validate_user() {
  global $failed;
  $RegexOnlyCharacters = "/^[a-zA-Z ]+$/";
  $RegexOnlyPhone = "/^01[012][0-9]{8}/";
  $RegexOnlyEmail = "/\S+@\S+\.\S+/";

  if (!isset($_POST["first_name"]) || empty($_POST["first_name"]) || !preg_match($RegexOnlyCharacters, $_POST["first_name"])) {
    $failed = "invalid first name";
    return false;
  }
  if (!isset($_POST["last_name"]) || empty($_POST["last_name"])|| !preg_match($RegexOnlyCharacters, $_POST["last_name"])) {
    $failed = "invalid last name";
    return false;
  }
  if (!isset($_POST["email"]) || empty($_POST["email"])|| !preg_match($RegexOnlyEmail, $_POST["email"])) {
    $failed = "invalid email";
    return false;
  }
  if (!isset($_POST["password"]) || empty($_POST["password"]) || strlen($_POST["password"]) < 8) {
    $failed = "invalid password";
    return false;
  }
  if (!isset($_POST["country"]) || empty($_POST["country"]) || !preg_match($RegexOnlyCharacters, $_POST["country"])) {
    $failed = "invalid country";
    return false;
  }
  if (!isset($_POST["phone"]) || empty($_POST["phone"]) || !preg_match($RegexOnlyPhone, $_POST["phone"])) {
    $failed = "invalid phone";
    return false;
  }
  if (!isset($_POST["role"]) || empty($_POST["role"]) || ($_POST["role"] != "admin" && $_POST["role"] != "hotel-manager" && $_POST["role"] != "customer")) {
    $failed = "invalid role";
    return false;
  }


  $fname = $_POST["first_name"];
  $lname = $_POST["last_name"];
  $email = $_POST["email"];
  $password = md5($_POST["password"]);
  $country = $_POST["country"];
  $phone = $_POST["phone"];
  $role = $_POST["role"];

  create_account($fname , $lname, $email, $password, $country, $phone, $role);

}

function create_account($fname , $lname, $email, $password, $country, $phone, $role) {
  global $DB;
  global $success , $failed;

  //Check if email exist
  $stmt = $DB->prepare("SELECT * FROM user WHERE email = ?");
  $stmt->bindParam(1, $email);
  $stmt -> execute();
  if ($stmt->fetch()) {
      $failed = "This email is already registed";
      return false;
  }

  $stmt = $DB->prepare("INSERT INTO user (first_name, last_name, email, password, country, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
  $stmt->bindParam(1, $fname);
  $stmt->bindParam(2, $lname);
  $stmt->bindParam(3, $email);
  $stmt->bindParam(4, $password);
  $stmt->bindParam(5, $country);
  $stmt->bindParam(6, $phone);
  $stmt->bindParam(7, $role);
  if ($stmt->execute()) {
    $success = "User has been added succesfully";
  } else {
    $failed = "Can't add user !";
  }
}

function getPageTitle() {
  global $data;
  $title = "";
  if (isset($data["page_title"])) {
    $title .= $data["page_title"];
  }
  return $title;
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

  <?php include("header.php"); ?>

  <?php include("sidebar.php"); ?>


  <div id="main">



    <form action="add-user.php" method="POST">

      <?php if(isset($success)) {
        echo '<div id="Success">'.$success.'</div>';
      } elseif(isset($failed)) {
        echo '<div id="Error">'.$failed.'</div>';
      }
      ?>
      <label>First Name <input type="text" name="first_name" placeholder="First Name .." value="<?php if(isset($_POST["first_name"])) echo $_POST["first_name"]; ?>" /></label>
      <label>Last Name <input type="text" name="last_name" placeholder="Last Name .." value="<?php if(isset($_POST["last_name"])) echo $_POST["last_name"]; ?>" /></label>
      <label>Email <input type="email" name="email" placeholder="Email .." value="<?php if(isset($_POST["email"])) echo $_POST["email"]; ?>"/></label>
      <label>Password <input type="password" name="password" placeholder="password .." value="<?php if(isset($_POST["password"])) echo $_POST["password"]; ?>"/></label>
      <label>Country <select name="country" id="Country">
        <option value="">Country</option>
        <?php echo getCountryList(); ?>
      </select>
      </label>
      <label>Phone <input type="text" name="phone" placeholder="Phone .." value="<?php if(isset($_POST["phone"])) echo $_POST["phone"]; ?>" /></label>
      <label>Role <select name="role">
        <option value="customer">Customer</option>
        <option value="hotel-manager">Hotel Manager</option>
        <option value="admin">Admin</option>
      </select>
      </label>
      <input type="submit" id="btn" value="Submit"/>
    </form>

  </div>

  <?php include("footer.php"); ?>

</body>

</html>
