<?php
session_start();

//since profile.php requires authorization so if there is no session nor cookie then redirect user to login page
if ( !isset($_SESSION['user_id']) && !isset($_COOKIE['user_id']) ) {
  header("refresh:0;url=login.php" );
  die;
}
?>

<?php include("general.php"); ?>

<?php

getUser();

if (!empty($_POST)) {
  UpdateInfo();
  getUser();
  header("refresh:0;url=profile.php" );
}

global $date;
$data["page_title"] = getFullName();

function getUser() {
  global $DB;
  global $user;

  $user_id = $_SESSION["user_id"];
  $stmt = $DB->prepare("SELECT * FROM user WHERE user_id = ?");
  $stmt->bindParam(1, $user_id);
  if ($stmt->execute()) {
    if ($row = $stmt->fetch()) {

      $user["id"] = $row['user_id'];
      $user["first_name"] = $row['first_name'];
      $user["last_name"] = $row['last_name'];
      $user["email"] = $row['email'];
      $user["password"] = $row['password'];
      $user["country"] = $row['country'];
      $user["phone"] = $row['phone'];
      $user["profile_picture"] = $row['profile_picture'];
      $user["role"] = $row['role'];

    }
  }
}

function getFirstName() {
  global $user;
  $first_name = "";
  if (isset($user["first_name"])) {
    $first_name = $user["first_name"];
  }
  return $first_name;
}

function getLastName() {
  global $user;
  $last_name = "";
  if (isset($user["last_name"])) {
    $last_name = $user["last_name"];
  }
  return $last_name;
}

function getFullName() {
  global $user;
  $full_name = "";
  if (isset($user["first_name"])) {
    $full_name .= $user["first_name"];
  }
  if (isset($user["last_name"])) {
    $full_name .= " " . $user["last_name"];
  }
  return $full_name;
}

function getEmail() {
  global $user;
  $email = "";
  if (isset($user["email"])) {
    $email = $user["email"];
  }
  return $email;
}

function getPassword() {
  global $user;
  $password = "";
  if (isset($user["password"])) {
    $password = $user["password"];
  }
  return $password;
}

function getCountry() {
  global $user;
  $country = "";
  if (isset($user["country"])) {
    $country = $user["country"];
  }
  return $country;
}

function getCountryList($user_country) {
  $res = "";
  $countries = array('Afghanistan','Albania','Algeria','American Samoa','Andorra','Angola','Anguilla','Antarctica','Antigua','Argentina','Armenia','Aruba','Australia','Austria','Azerbaijan','Bahamas','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bermuda','Bhutan','Bolivia','Bosnia','Botswana','Bouvet Island','Brazil','British Indian Ocean Territory','Brunei Darussalam','Bulgaria','Burkina Faso','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Cayman Islands','Central African Republic','Chad','Chile','China','Christmas Island','Cocos (Keeling) Islands','Colombia','Comoros','Congo','Congo','Cook Islands','Costa Rica','Croatia','Cuba','Cyprus','Czech Republic','Denmark','Djibouti','Dominica','Dominican Republic','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Ethiopia','Falkland Islands','Faroe Islands','Fiji','Finland','France','France','French Guiana','French Polynesia','French Southern Territories','Gabon','Gambia','Georgia','Germany','Ghana','Gibraltar','Greece','Greenland','Grenada','Guadeloupe','Guam','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Holy See','Honduras','Hong Kong','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Korea','Kuwait','Kyrgyzstan','Latvia','Lebanon','Lesotho','Liberia','Liechtenstein','Lithuania','Luxembourg','Macau','Macedonia','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Martinique','Mauritania','Mauritius','Mayotte','Mexico','Micronesia','Moldova','Monaco','Mongolia','Montenegro','Montserrat','Morocco','Mozambique','Myanmar','Namibia','Nauru','Nepal','Netherlands','Netherlands Antilles','New Caledonia','New Zealand','Nicaragua','Niger','Nigeria','Niue','Norfolk Island','Northern Mariana Islands','Norway','Oman','Pakistan','Palau','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Pitcairn','Poland','Portugal','Puerto Rico','Qatar','Reunion','Romania','Russian Federation','Rwanda','Saint Kitts And Nevis','Saint Lucia','Saint Vincent And The Grenadines','Samoa','San Marino','Sao Tome And Principe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Georgia','Spain','Sri Lanka','St. Helena','St. Pierre And Miquelon','Sudan','Suriname','Svalbard And Jan Mayen Islands','Swaziland','Sweden','Switzerland','Syrian Arab Republic','Taiwan','Tajikistan','Tanzania','Thailand','Togo','Tokelau','Tonga','Trinidad And Tobago','Tunisia','Turkey','Turkmenistan','Turks And Caicos Islands','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','United States Minor Outlying Islands','Uruguay','Uzbekistan','Vanuatu','Venezuela','Viet Nam','Virgin Islands (British)','Virgin Islands (U.S.)','Wallis And Futuna Islands','Western Sahara','Yemen','Yugoslavia','Zambia','Zimbabwe');
  foreach ($countries as $country) {
    if ($country == $user_country) {
      $res .= '<option value="'.$country.'" selected>' . $country . '</option>
      ';
    } else {
      $res .= '<option value="'.$country.'">' . $country . '</option>
      ';
    }
  }
  return $res;
}

function getPhone() {
  global $user;
  $phone = "";
  if (isset($user["phone"])) {
    $phone = $user["phone"];
  }
  return $phone;
}

function getProfilePicture() {
  global $user;
  $profile_picture = "";
  if (isset($user["profile_picture"]) && !empty($user["profile_picture"])) {
    $profile_picture = "uploads/" . $user["profile_picture"];
  } else {
    $profile_picture = "images/default-user.png";
  }
  return $profile_picture;
}

function getRole() {
  global $user;
  $role = "";
  if (isset($user["role"])) {
    $role = $user["role"];
  }
  return $role;
}

function UploadProfilePicture($image) {

  $currentDir = getcwd();
  $uploadDirectory = "/uploads/";
  $errors = [];
  $fileExtensions = ['jpeg','jpg','png'];
  $fileName = $image['name'];
  $fileSize = $image['size'];
  $fileTmpName  = $image['tmp_name'];
  $fileType = $image['type'];
  $fileExtension = @strtolower(end(explode('.',$fileName)));
  $uploadPath = $currentDir . $uploadDirectory . basename($fileName);

  if (! in_array($fileExtension,$fileExtensions)) {
    $errors[] = "This file extension is not allowed. Please upload a JPG or JPEG or PNG file";
  }

  if ($fileSize > 2000000) {
    $errors[] = "This file is more than 2MB";
  }

  if (empty($errors)) {
    return move_uploaded_file($fileTmpName, $uploadPath);
  }
  return $errors;
}

function UpdateInfo() {

  if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) return false;
  if(!isset($_POST['first_name']) || empty($_POST['first_name'])) return false;
  if(!isset($_POST['last_name']) || empty($_POST['last_name'])) return false;
  if(!isset($_POST['email']) || empty($_POST['email'])) return false;
  if(!isset($_POST['country']) || empty($_POST['country'])) return false;
  if(!isset($_POST['phone']) || empty($_POST['phone'])) return false;

  $user_id = $_SESSION['user_id'];
  $first_name = $_POST['first_name'];
  $last_name = $_POST['last_name'];
  $email = $_POST['email'];
  $country = $_POST['country'];
  $phone = $_POST['phone'];

  global $DB;
  $stmt = "";

  if (isset($_FILES['profile_picture']) && !empty($_FILES['profile_picture']['name'])) {
    $profile_picture = $_FILES['profile_picture'];
    if (UploadProfilePicture($profile_picture) === true) {
      $stmt = $DB->prepare("UPDATE User SET profile_picture = ? WHERE user_id = ?");
      $stmt->bindParam(1, $profile_picture["name"]);
      $stmt->bindParam(2, $user_id);
      $stmt->execute();
    }
  }

  if (isset($_POST['password']) && !empty($_POST['password'])) {
    $password = md5($_POST['password']);
    $stmt = $DB->prepare("UPDATE User SET first_name = ?, last_name = ?, email = ?, password = ?, country = ?, phone = ? WHERE user_id = ?");
    $stmt->bindParam(1, $first_name);
    $stmt->bindParam(2, $last_name);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $password);
    $stmt->bindParam(5, $country);
    $stmt->bindParam(6, $phone);
    $stmt->bindParam(7, $user_id);
  } else {
    $stmt = $DB->prepare("UPDATE User SET first_name = ?, last_name = ?, email = ?, country = ?, phone = ? WHERE user_id = ?");
    $stmt->bindParam(1, $first_name);
    $stmt->bindParam(2, $last_name);
    $stmt->bindParam(3, $email);
    $stmt->bindParam(4, $country);
    $stmt->bindParam(5, $phone);
    $stmt->bindParam(6, $user_id);
  }
  return $stmt->execute();
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
    </div>

  </header>


  <div class="container mobile-p-30">
    <div class="row">

      <div class="col-sm-3">
        <div class="sidebar">
          <img src="<?php echo getProfilePicture(); ?>" class="profile-picture">

          <div class="profile-name"><?php echo getFullName(); ?></div>

          <ul>
            <li><a title="" href="./reservations.php">My Reservations</a></li>
            <li><a title="" href="./profile.php">Edit my profile</a></li>
          </ul>
        </div>
      </div>
      <div class="col-sm-9">
        <div class="border-container main">

          <h4 class="m-b-30">Profile information</h4>

          <form autocomplete="off" action="profile.php" method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-sm-6">
                <label>First Name <input type="text" name="first_name" placeholder="First Name .." value="<?php echo getFirstName(); ?>"/></label>
              </div>
              <div class="col-sm-6">
                <label>Last Name <input type="text" name="last_name" placeholder="Last Name .." value="<?php echo getLastName(); ?>"/></label>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <label>Email <input type="email" name="email" placeholder="Email .." value="<?php echo getEmail(); ?>"/></label>
              </div>
              <div class="col-sm-6">
                <label>Password <input type="password" name="password" placeholder="Set new password .." /></label>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-6">
                <label>Country
                  <select name="country">
                    <?php echo getCountryList(getCountry()); ?>
                  </select>
                </label>
              </div>
              <div class="col-sm-6">
                <label>Phone <input type="text" name="phone" placeholder="Phone .." value="<?php echo getPhone(); ?>"/></label>
              </div>
            </div>

            <div class="row">
              <div class="col-sm-12">
                <label>Profile Picture <input type="file" name="profile_picture" /></label>
              </div>
            </div>

            <input type="submit" id="btn" value="Save Changes"/>

          </form>

        </div>
      </div>
    </div>
  </div>

  <?php include("footer.php");?>

</body>

</html>
