<?php
session_start();
//since finish.php requires authorization so if there is no session or cookie then redirect user to login page
if ( !isset($_SESSION['user_id']) && !isset($_COOKIE['user_id']) ) {
  header("refresh:0;url=login.php" );
  die;
}
?>

<?php include("general.php"); ?>

<?php

//Declare global variable called $data to hold all genaric data in the page like title, metakeywords, metadescription, etc
global $data;
$data["page_title"] = "Finish";

function Make_Reservation() {

    //validate what in $_POST array
  if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) return false;
  if(!isset($_POST['hotel_id']) || empty($_POST['hotel_id'])) return false;
  if(!isset($_POST['checkin']) || empty($_POST['checkin'])) return false;
  if(!isset($_POST['nights']) || empty($_POST['nights'])) return false;
  if(!isset($_POST['room']) || empty($_POST['room'])) return false;
  if(!isset($_POST['payment']) || empty($_POST['payment'])) return false;

  //put it in variables to facilitate using afterward
  $user_id = $_SESSION['user_id'];
  $hotel_id = $_POST['hotel_id'];
  $reservation_date = date('Y-m-d');
  $checkin = $_POST['checkin'];
  $nights = $_POST['nights'];
  $room = $_POST['room'];
  $payment = $_POST['payment'];
  $status = "Active";

  //prepare a sql statment that will insert thses data into database and execute it after binding data into the statment
  global $DB;
  $stmt = $DB->prepare("INSERT INTO reservation (user_id, hotel_id, reservation_date, room_type, checkin, number_of_nights, price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bindParam(1, $user_id);
  $stmt->bindParam(2, $hotel_id);
  $stmt->bindParam(3, $reservation_date);
  $stmt->bindParam(4, $room);
  $stmt->bindParam(5, $checkin);
  $stmt->bindParam(6, $nights);
  $stmt->bindParam(7, $payment);
  $stmt->bindParam(8, $status);
  return $stmt->execute();
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
    </div>
  </header>

  <div class="container mobile-p-30">

    <div class="ProgressBar">
      <div class="bar-outer">
        <div id="progress4" class="bar-inner">
          <div class="ball"></div>
        </div>
      </div>

      <div class="bar-text">
        <span>Start</span>
        <span>Book Hotel</span>
        <span>Payment</span>
        <span>Finish</span>
      </div>
    </div>

    <div class="border-container">

      <div class="text-center m-b-30">
      <? if (Make_Reservation()): ?>
        <img alt="" id="DoneImg" src="images/done.png">
        <h3>You have successfuly made a reservation</h3>
      <? else: ?>
        <img alt="" id="DoneImg" src="images/Error.png">
        <h3>Error occured</h3>
      <? endif; ?>
      </div>

    </div>
  </div>

  <?php include("footer.php");?>

</body>

</html>
