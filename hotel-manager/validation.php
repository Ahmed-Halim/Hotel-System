<?php
if ( !isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
  header("refresh:0;url=../login.php" );
  die;
}

if (!IsHotelManager($_SESSION['user_id'])) {
  echo "You are not authorized to view this page !";
  die;
}


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
