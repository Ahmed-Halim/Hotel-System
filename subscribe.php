<?php
include("DB.php");

//insert email sent in post request into table subscribe
if (isset($_POST["email"]) && !empty($_POST["email"]) && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
  $stmt = $DB->prepare("INSERT INTO subscribe (email) VALUES (?)");
  $stmt->bindParam(1, $_POST["email"]);
  $stmt->execute();
  echo "done";
} else {
  echo "failed";
}
?>
