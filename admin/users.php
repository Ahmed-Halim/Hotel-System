<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Users";

if (isset($_GET["delete"]) && !empty($_GET["delete"])) {
  $user_id = $_GET["delete"];
  DeleteUser($user_id);
}

function DeleteUser($user_id) {
  global $DB;
  $stmt = $DB->prepare("DELETE FROM user WHERE user_id = ?");
  $stmt->bindParam(1, $user_id);
  $stmt->execute();


  $stmt = $DB->prepare("SELECT profile_picture FROM user WHERE user_id = ?");
  $stmt->bindParam(1, $user_id);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    if (isset($row["profile_picture"]) && !empty($row["profile_picture"])) {
      $path = str_replace("admin", "uploads/", getcwd());
      $file = $path . $row["profile_picture"];
      unlink($file);
    }
  }


  $stmt = $DB->prepare("DELETE FROM reservation WHERE user_id = ?");
  $stmt->bindParam(1, $user_id);
  $stmt->execute();
}


function getUsers() {
  global $DB;
  $sql = "SELECT * FROM user";
  $result = $DB->query($sql)->fetchAll();
  $res = "";
  foreach ($result as $row) {
    $res .= '
    <tr>
      <td>'.$row["user_id"].'</td>
      <td>'.$row["first_name"]. ' ' .$row["last_name"]. '</td>
      <td>'.$row["email"].'</td>
      <td>'.$row["country"].'</td>
      <td>'.$row["phone"].'</td>
      <td>'.$row["role"].'</td>
      <td>
        <a title="" href="./edit-user.php?id='.$row["user_id"].'"><i class="material-icons edit">edit</i></a>
        <a title="" href="./users.php?delete='.$row["user_id"].'"><i class="material-icons delete">delete</i></a>
      </td>
    </tr>
    ';
  }
  return $res;
}

function getPageTitle() {
  global $data;
  $title = "";
  if (isset($data["page_title"])) {
    $title .= $data["page_title"];
  }
  return $title;
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
      <a title="" id="btn" href="./add-user.php"><i class="material-icons">add</i> Add New User</a>

      <table>
        <tbody>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Country</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
          <?php echo getUsers(); ?>
        </tbody>
      </table>
    </div>

  <?php include("footer.php"); ?>

</body>

</html>
