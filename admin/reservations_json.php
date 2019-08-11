<?php

include_once("DB.php");
$reservations = array();
for($i = 7; $i >= 0; $i--) {
  $date = date('Y-m-d',strtotime("-$i day"));
  $datename = date('m-d',strtotime("-$i day"));
  if ($i == 0) $datename .= " (Today)";
  global $DB;
  $stmt = $DB->prepare("SELECT COUNT(*) AS result FROM reservation WHERE reservation_date = ?");
  $stmt->bindParam(1, $date);
  $stmt -> execute();
  if ($row = $stmt->fetch()) {
    $reservations[$datename] = $row["result"];
  }
}
echo json_encode($reservations);
?>
