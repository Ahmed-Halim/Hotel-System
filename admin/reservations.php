<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Reservations";

function getReservations($status = "") {
  global $DB;

  $current_date = date('Y-m-d');
  $stmt = $DB->prepare("UPDATE reservation SET status = 'Expired' WHERE checkin < (?)");
  $stmt->bindParam(1 , $current_date);
  $stmt->execute();
  $sql = "SELECT reservation.reservation_id AS reservation_id, reservation.reservation_date AS reservation_date, hotel.name AS hotel_name, user.first_name AS first_name, user.last_name AS last_name, reservation.checkin AS checkin, reservation.number_of_nights AS number_of_nights, reservation.room_type AS room_type, reservation.price AS price, reservation.status AS status FROM reservation JOIN hotel ON reservation.hotel_id = hotel.hotel_id JOIN user ON reservation.user_id = user.user_id";
  if ($status == "Active") {
    $sql .= " WHERE status = 'Active'";
  } else if ($status == "Expired") {
    $sql .= " WHERE status = 'Expired'";
  } else if ($status == "Canceled") {
    $sql .= " WHERE status = 'Canceled'";
  }
  $result = $DB->query($sql)->fetchAll();
  $res = "";
  foreach ($result as $row) {
    $res .= '
    <tr>
      <td>'.$row["reservation_id"].'</td>
      <td>'.$row["first_name"]. ' ' .$row["last_name"]. '</td>
      <td>'.$row["hotel_name"].'</td>
      <td>'.$row["reservation_date"].'</td>
      <td>'.$row["checkin"].'</td>
      <td>'.$row["number_of_nights"].'</td>
      <td>'.$row["room_type"].'</td>
      <td>'.$row["price"].' LE</td>
      <td><span class="'.strtolower($row["status"]).'">'.$row["status"].'</span></td>
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

    <div class="m-b-30">Show only ( <a href="./reservations.php">All</a> - <a href="./reservations.php?status=Active">Active</a> - <a href="./reservations.php?status=Expired">Expired</a> - <a href="./reservations.php?status=Canceled">Canceled</a> )</div>

    <table>
      <tbody>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Hotel</th>
          <th>Res. date</th>
          <th>Checkin</th>
          <th>Nights</th>
          <th>Room Type</th>
          <th>Price</th>
          <th>Status</th>
        </tr>

        <?php
          $status = "";
          if (isset($_GET["status"])) $status = $_GET["status"];

          echo getReservations($status);

        ?>

      </tbody>
    </table>
  </div>

  <?php include("footer.php"); ?>

</body>

</html>
