<?php

session_start();
include_once("DB.php");
include_once("validation.php");

$reservations = array();
for($i = 7; $i >= 0; $i--) {
  $date = date('Y-m-d',strtotime("-$i day"));
  $datename = date('m-d',strtotime("-$i day"));
  if ($i == 0) $datename .= " (Today)";
  $stmt = $DB->prepare("SELECT COUNT(*) AS result FROM reservation WHERE reservation_date = ? AND hotel_id = ?");
  $stmt->bindParam(1, $date);
  $stmt->bindParam(2, $Hotel_ID);
  $stmt -> execute();
  if ($row = $stmt->fetch()) {
    $reservations[$datename] = $row["result"];
  }
}
echo json_encode($reservations);
?>
