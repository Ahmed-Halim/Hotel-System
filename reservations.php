<?php
session_start();

//since reservation.php requires authorization so if there is no session nor cookie then redirect user to login page
if ( !isset($_SESSION['user_id']) && !isset($_COOKIE['user_id']) ) {
  header("refresh:0;url=login.php" );
  die;
}
?>

<?php include("general.php"); ?>

<?php

global $data;
$data["page_title"] = "Reservations";

getUser();

function getUser() {
  global $DB;
  global $user;

  $user_id = $_SESSION["user_id"];
  $stmt = $DB->prepare("SELECT * FROM user WHERE user_id = ?");
  $stmt->bindParam(1, $user_id);
  if ($stmt->execute()) {
    if ($row = $stmt->fetch()) {

      $user["id"] = $row['user_id'];
      $user["first_name"] = $row['first_name'];
      $user["last_name"] = $row['last_name'];
      $user["email"] = $row['email'];
      $user["password"] = $row['password'];
      $user["country"] = $row['country'];
      $user["phone"] = $row['phone'];
      $user["profile_picture"] = $row['profile_picture'];
      $user["role"] = $row['role'];

    }
  }
}

function getFirstName() {
  global $user;
  $first_name = "";
  if (isset($user["first_name"])) {
    $first_name = $user["first_name"];
  }
  return $first_name;
}

function getLastName() {
  global $user;
  $last_name = "";
  if (isset($user["last_name"])) {
    $last_name = $user["last_name"];
  }
  return $last_name;
}

function getFullName() {
  global $user;
  $full_name = "";
  if (isset($user["first_name"])) {
    $full_name .= $user["first_name"];
  }
  if (isset($user["last_name"])) {
    $full_name .= " " . $user["last_name"];
  }
  return $full_name;
}

function getEmail() {
  global $user;
  $email = "";
  if (isset($user["email"])) {
    $email = $user["email"];
  }
  return $email;
}

function getPassword() {
  global $user;
  $password = "";
  if (isset($user["password"])) {
    $password = $user["password"];
  }
  return $password;
}

function getCountry() {
  global $user;
  $country = "";
  if (isset($user["country"])) {
    $country = $user["country"];
  }
  return $country;
}

function getPhone() {
  global $user;
  $phone = "";
  if (isset($user["phone"])) {
    $phone = $user["phone"];
  }
  return $phone;
}

function getProfilePicture() {
  global $user;
  $profile_picture = "";
  if (isset($user["profile_picture"]) && !empty($user["profile_picture"])) {
    $profile_picture = "uploads/" . $user["profile_picture"];
  } else {
    $profile_picture = "images/default-user.png";
  }
  return $profile_picture;
}

function getRole() {
  global $user;
  $role = "";
  if (isset($user["role"])) {
    $role = $user["role"];
  }
  return $role;
}


function getReservationList() {
  global $DB;
  $user_id = $_SESSION["user_id"];
  $stmt = $DB->prepare("SELECT reservation.reservation_id AS reservation_id, hotel.name AS hotel_name, reservation.checkin AS checkin, reservation.number_of_nights AS number_of_nights, reservation.room_type AS room_type, reservation.price AS price, reservation.status AS status FROM reservation JOIN hotel ON reservation.hotel_id = hotel.hotel_id WHERE reservation.user_id = ?");
  $stmt->bindParam(1, $user_id);
  $res = "";
  if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {

      $res .= "
      <tr>
        <td>".$row['reservation_id']."</td>
        <td>".$row['hotel_name']."</td>
        <td>".$row['checkin']."</td>
        <td>".$row['number_of_nights']."</td>
        <td>".$row['room_type']."</td>
        <td>".$row['price']." LE</td>
        <td><span class='".strtolower($row['status'])."'>".$row['status']."</span></td>
      </tr>";

    }
  }
  return $res;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("blog-info.php"); ?>
</head>

<body>
  <header>

    <div class="container">

      <?php include("header.php"); ?>

  </header>


  <div class="container mobile-p-30">
    <div class="row">

      <div class="col-sm-3">
        <div class="sidebar">
          <img src="<?php echo getProfilePicture(); ?>" class="profile-picture">
          <div class="profile-name"><?php echo getFullName(); ?></div>

          <ul>
            <li><a title="" href="./reservations.php">My Reservations</a></li>
            <li><a title="" href="./profile.php">Edit my profile</a></li>
          </ul>
        </div>
      </div>
      <div class="col-sm-9">
        <div class="border-container main">

          <h4 class="m-b-30">My Reservations</h4>

          <table>
            <tbody>
              <tr>
                <th>Res. ID</th>
                <th>Hotel</th>
                <th>Checkin</th>
                <th>Nights</th>
                <th>Room Type</th>
                <th>Price</th>
                <th>Status</th>
              </tr>
              <?php echo getReservationList(); ?>

            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>


  <?php include("footer.php");?>

</body>

</html>
