<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Reservations";

if (isset($_GET["cancel"]) && !empty($_GET["cancel"])) {
  $reservation_id = $_GET["cancel"];
  CancelReservation($reservation_id);
}

function CancelReservation($reservation_id) {
  global $DB;
  $stmt = $DB->prepare("UPDATE reservation SET status = 'Canceled' WHERE reservation_id = ?");
  $stmt->bindParam(1, $reservation_id);
  $stmt->execute();
}


function getReservations() {
  global $DB, $Hotel_ID;
  $stmt = $DB->prepare("SELECT reservation.reservation_id AS reservation_id, reservation.reservation_date AS reservation_date, user.first_name AS first_name, user.last_name AS last_name, reservation.checkin AS checkin, reservation.number_of_nights AS number_of_nights, reservation.room_type AS room_type, reservation.price AS price, reservation.status AS status FROM reservation JOIN hotel ON reservation.hotel_id = hotel.hotel_id JOIN user ON reservation.user_id = user.user_id WHERE reservation.hotel_id = ?");
  $stmt->bindParam(1, $Hotel_ID);
  $stmt->execute();

  $res = "";

  while($row = $stmt->fetch()) {
    $res .= '
    <tr>
      <td>'.$row["reservation_id"].'</td>
      <td>'.$row["first_name"]. ' ' .$row["last_name"]. '</td>
      <td>'.$row["reservation_date"].'</td>
      <td>'.$row["checkin"].'</td>
      <td>'.$row["number_of_nights"].'</td>
      <td>'.$row["room_type"].'</td>
      <td>'.$row["price"].' LE</td>
      <td><span class="'.strtolower($row["status"]).'">'.$row["status"].'</span></td>
      <td><a title="" href="reservations.php?cancel='.$row["reservation_id"].'"><i class="cancel-icon material-icons">cancel</i></a></td>
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

    <table>
      <tbody>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Res. date</th>
          <th>Checkin</th>
          <th>Nights</th>
          <th>Room Type</th>
          <th>Price</th>
          <th>Status</th>
          <th>Action</th>

        </tr>

        <?php echo getReservations(); ?>

      </tbody>
    </table>
  </div>

  <?php include("footer.php"); ?>

</body>

</html>
