<?php
session_start();

//since payment.php requires authorization so if there is no session nor cookie then redirect user to login page
if ( !isset($_SESSION['user_id']) && !isset($_COOKIE['user_id']) ) {
  header("refresh:0;url=login.php" );
  die;
}
?>

<?php include("general.php"); ?>

<?php
global $data;
$data["page_title"] = "Payment";
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
        <div id="progress3" class="bar-inner">
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

      <div class="text-center"><img alt="" id="VisaImg" src="images/visa.png"></div>

      <form autocomplete="off" id="visa" name="visa" action="finish.php" method="POST">

        <div class="hidden">
          <input name="hotel_id" value="<?php if(isset($_POST['hotel_id'])) echo $_POST['hotel_id']; ?>">
          <input name="checkin" value="<?php if(isset($_POST['checkin'])) echo $_POST['checkin']; ?>">
          <input name="nights" value="<?php if(isset($_POST['nights'])) echo $_POST['nights']; ?>">
          <input name="room" value="<?php if(isset($_POST['room'])) echo $_POST['room']; ?>">
          <input name="payment" value="<?php if(isset($_POST['payment'])) echo $_POST['payment']; ?>">
        </div>

        <div class="row">

          <div class="col-sm-2"></div>
          <div class="col-sm-8">
            <div id="Error"></div>
            <input name="card_number" id="number" placeholder="Card number" />
          </div>
          <div class="col-sm-2"></div>

        </div>


        <div class="row">

          <div class="col-sm-2"></div>
          <div class="col-sm-4">
            <input name="expired_date" id="expired" placeholder="Expiration date" />
          </div>
          <div class="col-sm-4">
            <input name="cvv" id="cvv" placeholder="CVV"/>
          </div>
          <div class="col-sm-2"></div>

        </div>



        <div class="row">

          <div class="col-sm-2"></div>
          <div class="col-sm-8">
            <input type="submit" id="payment-btn" value="Confirm Payment"/>
          </div>
          <div class="col-sm-2"></div>

        </div>

      </form>

    </div>
  </div>

  <?php include("footer.php");?>

</body>

</html>
