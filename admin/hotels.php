<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Hotels";

if (isset($_GET["delete"]) && !empty($_GET["delete"])) {
  $hotel_id = $_GET["delete"];
  DeleteHotel($hotel_id);
}

function DeleteHotel($hotel_id) {
  global $DB;
  $stmt = $DB->prepare("DELETE FROM hotel WHERE hotel_id = ?");
  $stmt->bindParam(1, $hotel_id);
  $stmt->execute();


  $stmt = $DB->prepare("DELETE FROM hotel_facility WHERE hotel_id = ?");
  $stmt->bindParam(1, $hotel_id);
  $stmt->execute();

  $stmt = $DB->prepare("SELECT image FROM hotel_image WHERE hotel_id = ?");
  $stmt->bindParam(1, $hotel_id);
  $stmt->execute();
  while ($row = $stmt->fetch()) {
    if (isset($row["image"]) && !empty($row["image"])) {
      $path = str_replace("admin", "uploads/", getcwd());
      $file = $path . $row["image"];
      unlink($file);
    }
  }

  $stmt = $DB->prepare("DELETE FROM hotel_image WHERE hotel_id = ?");
  $stmt->bindParam(1, $hotel_id);
  $stmt->execute();
}


function getHotels() {
  global $DB;
  $sql = "SELECT hotel.hotel_id AS hotel_id, hotel.name AS name, hotel.city AS city, user.first_name AS first_name, user.last_name AS last_name FROM hotel JOIN user ON hotel.manager_id = user.user_id";
  $result = $DB->query($sql)->fetchAll();
  $res = "";
  foreach ($result as $row) {
    $res .= '
    <tr>
      <td>'.$row["hotel_id"].'</td>
      <td>'.$row["name"].'</td>
      <td>'.$row["city"].'</td>
      <td>'.$row["first_name"].' '.$row["last_name"].'</td>
      <td>
        <a title="" href="./edit-hotel.php?id='.$row["hotel_id"].'"><i class="material-icons edit">edit</i></a>
        <a title="" href="./hotels.php?delete='.$row["hotel_id"].'"><i class="material-icons delete">delete</i></a>
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
      <a title="" id="btn" href="./add-hotel.php"><i class="material-icons">add</i> Add New Hotel</a>

      <table>
        <tbody>
          <tr>
            <th>ID</th>
            <th>Hotel Name</th>
            <th>City</th>
            <th>Manager</th>
            <th>Action</th>
          </tr>
          <?php echo getHotels(); ?>
        </tbody>
      </table>
    </div>

  <?php include("footer.php"); ?>

</body>

</html>
