<?php

//General.php is a script running before any page. it hold all necessary information

include_once("DB.php");

init();

//init function get all data in table general and put it in array $data
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

//all the functions below just to return specific value in array $data

function getTitle() {
  global $data;
  $title = "";
  if (isset($data["site_name"])) {
    $title .= $data["site_name"];
  }
  if (isset($data["page_title"])) {
    $title .= " - " . $data["page_title"];
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
