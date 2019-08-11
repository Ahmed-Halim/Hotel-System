<?php

//in order to display nav bar in different ways for guest, hotel manager and admin we did the following

//if there is session get user id of it
if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}

if (isset($_COOKIE['user_id'])) {
  $user_id = $_COOKIE['user_id'];
}

//function check if user id belongs to user with role admin or not
function IsAdmin($user_id) {
  global $DB;
  $stmt = $DB->prepare("SELECT * FROM user WHERE user_id = ?");
  $stmt->bindParam(1, $user_id);
  if ($stmt->execute()) {
    if ($row = $stmt->fetch()) {
      return ($row["role"] == "admin");
    }
  }
  return false;
}

//function check if user id belongs to user with role hotel-manager or not
function IsHotelManager($user_id) {
  global $DB , $Hotel_ID;
  $stmt = $DB->prepare("SELECT * FROM hotel WHERE manager_id = ?");
  $stmt->bindParam(1, $user_id);
  if ($stmt->execute()) {
    if ($row = $stmt->fetch()) {
      $Hotel_ID = $row["hotel_id"];
      return true;
    }
  }
  return false;
}

?>

<a title="" href="./home.php">
  <img alt="" id="logo" src="images/logo.png">
</a>


<ul class="languagepicker roundborders large">
  <li><a title="" href="#en"><img alt="" src="images/flags/en.png"/>English</a></li>
  <li><a title="" href="#ar"><img alt="" src="images/flags/ar.png"/>Arabic</a></li>
  <li><a title="" href="#de"><img alt="" src="images/flags/de.png"/>German</a></li>
  <li><a title="" href="#fr"><img alt="" src="images/flags/fr.png"/>Français</a></li>
  <li><a title="" href="#es"><img alt="" src="images/flags/es.png"/>Español</a></li>
  <li><a title="" href="#it"><img alt="" src="images/flags/it.png"/>Italiano</a></li>
</ul>


<nav id="menu">
  <ul>
    <li><a title="" href="./home.php"><i class="fas fa-home"></i> Home</a></li>
    <li><a title="" href="./hotels.php"><i class="fas fa-building"></i> Hotels</a></li>
  <? if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) : ?>

    <? if (IsAdmin($user_id)) : ?>
      <li><a title="" href="./admin/dashboard.php"><i class="fas fa-cog"></i> Dashboard</a></li>
    <? elseif (IsHotelManager($user_id)) : ?>
      <li><a title="" href="./hotel-manager/dashboard.php"><i class="fas fa-cog"></i> Dashboard</a></li>
    <? else:?>
      <li><a title="" href="./profile.php"><i class="fas fa-user-plus"></i> My Account</a></li>
    <? endif; ?>

    <li><a title="" href="./logout.php"><i class="fas fa-sign-in-alt"></i> Logout</a></li>
  <? else: ?>
    <li><a title="" href="./register.php"><i class="fas fa-user-plus"></i> Register</a></li>
    <li><a title="" href="./login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
  <? endif; ?>
  </ul>

  <div class="mobile-menu">
    <div class="bar"></div>
    <div class="bar"></div>
    <div class="bar"></div>
  </div>
</nav>
