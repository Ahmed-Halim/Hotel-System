<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "General Settings";

if (!empty($_POST)) {
  SaveChanges();
}

function SaveChanges() {
  global $DB;
  foreach ($_POST as $name => $value) {
    $stmt = $DB->prepare("UPDATE general SET data_value = ? WHERE data_name = ?");
    $stmt->bindParam(1, $value);
    $stmt->bindParam(2, $name);
    $stmt->execute();
  }
}

init();

function init() {
  global $DB, $data;
  $sql = "SELECT * FROM general";
  $result = $DB->query($sql)->fetchAll();
  foreach ($result as $row) {
    $name = $row["data_name"];
    $value = $row["data_value"];
    $data[$name] = $value;
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

function getTitle() {
  global $data;
  $title = "";
  if (isset($data["site_name"])) {
    $title .= $data["site_name"];
  }
  return $title;
}

function getMetaKeywords() {
  global $data;
  $meta_keywords = "";
  if (isset($data["meta_keywords"])) {
    $meta_keywords = $data["meta_keywords"];
  }
  return $meta_keywords;
}

function getMetaDescription() {
  global $data;
  $meta_description = "";
  if (isset($data["meta_description"])) {
    $meta_description = $data["meta_description"];
  }
  return $meta_description;
}

function getCookies () {
  global $data;
  $cookies = "";
  if (isset($data["cookies"])) {
    $cookies = $data["cookies"];
  }
  return $cookies;
}

function getAboutUs () {
  global $data;
  $about_us = "";
  if (isset($data["about_us"])) {
    $about_us = $data["about_us"];
  }
  return $about_us;
}

function getTerms () {
  global $data;
  $terms = "";
  if (isset($data["terms"])) {
    $terms = $data["terms"];
  }
  return $terms;
}

function getPrivacyPolicy () {
  global $data;
  $privacy_policy = "";
  if (isset($data["privacy_policy"])) {
    $privacy_policy = $data["privacy_policy"];
  }
  return $privacy_policy;
}

function getFacebook () {
  global $data;
  $facebook = "#";
  if (isset($data["facebook"])) {
    $facebook = $data["facebook"];
  }
  return $facebook;
}

function getTwitter () {
  global $data;
  $twitter = "#";
  if (isset($data["twitter"])) {
    $twitter = $data["twitter"];
  }
  return $twitter;
}

function getYoutube () {
  global $data;
  $youtube = "#";
  if (isset($data["youtube"])) {
    $youtube = $data["youtube"];
  }
  return $youtube;
}

function getInstagram () {
  global $data;
  $instagram = "#";
  if (isset($data["instagram"])) {
    $instagram = $data["instagram"];
  }
  return $instagram;
}

function getIOS () {
  global $data;
  $ios = "#";
  if (isset($data["ios"])) {
    $ios = $data["ios"];
  }
  return $ios;
}

function getAndroid () {
  global $data;
  $android = "#";
  if (isset($data["android"])) {
    $android = $data["android"];
  }
  return $android;
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

    <form action="general.php" method="POST">
      <label>Site Title <input type="text" name="site_name" placeholder="Site name .." value="<?php echo getTitle(); ?>"/></label>
      <label>Site Keywords <textarea name="meta_keywords" placeholder="Keywords related to your site .."><?php echo getMetaKeywords(); ?></textarea></label>
      <label>Site Description <textarea name="meta_description" placeholder="Describe your site .. .."><?php echo getMetaDescription(); ?></textarea></label>

      <h4>Social Media</h4>
      <label>Facebook <input type="url" name="facebook" placeholder="Facebook page URL" value="<?php echo getFacebook(); ?>"/></label>
      <label>Twitter <input type="url" name="twitter" placeholder="Twitter account URL" value="<?php echo getTwitter(); ?>"/></label>
      <label>Youtube <input type="url" name="youtube" placeholder="Youtube channel URL" value="<?php echo getYoutube(); ?>"/></label>
      <label>Instagram <input type="url" name="instagram" placeholder="Instagram account URL" value="<?php echo getInstagram(); ?>"/></label>

      <h4>More from us</h4>
      <label>About us <textarea name="about_us" placeholder="Who we are ?"><?php echo getAboutUs(); ?></textarea></label>
      <label>Terms <textarea name="terms" placeholder="Terms and conditions .."><?php echo getTerms(); ?></textarea></label>
      <label>Privacy Policy <textarea name="privacy_policy" placeholder="Privacy policy .."><?php echo getPrivacyPolicy(); ?></textarea></label>
      <label>Cookies <textarea name="cookies" placeholder="Cookies .."><?php echo getCookies(); ?></textarea></label>


      <h4>Mobile app</h4>
      <label>Android <input type="url" name="android" placeholder="Android app URL" value="<?php echo getAndroid(); ?>"/></label>
      <label>IOS <input type="url" name="ios" placeholder="IOS app URL" value="<?php echo getIOS(); ?>"/></label>

      <input type="submit" id="btn" value="Save Changes"/>
    </form>



  </div>
  <?php include("footer.php"); ?>

</body>

</html>
