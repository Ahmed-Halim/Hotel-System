<?php
include_once("DB.php");

if (isset($_GET["image"]) && !empty($_GET["image"])) {
  $stmt = $DB->prepare("DELETE FROM hotel_image WHERE image = ?");
  $image = $_GET["image"];
  $stmt->bindParam(1, $_GET["image"]);
  if ($stmt->execute()) {
    unlink("../uploads/".$_GET["image"]);
    echo 'done';
  }
}

?>
