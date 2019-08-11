<?php
if ( !isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
  header("refresh:0;url=../login.php" );
  die;
}

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
}

if (isset($_COOKIE['user_id'])) {
  $user_id = $_COOKIE['user_id'];
}


if (!IsAdmin($user_id)) {
  echo "You are not authorized to view this page !";
  die;
}


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
?>
