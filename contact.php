<?php
session_start();
?>

<?php include("general.php"); ?>

<?php

global $data;
$data["page_title"] = "Contact us";

$response = NULL;

if (!empty($_POST)) {
  //if there is data in $_POST array call Send_Message() to validate and add these data into database
  if (Send_Message()) {
    $response = "Your message has been sent succesfully";
  } else {
    $response = "Error occured !";
  }
}

function Send_Message() {
  //validate what in $_POST array
  if(!isset($_POST['name']) || empty($_POST['name'])) return false;
  if(!isset($_POST['email']) || empty($_POST['email']) || !filter_var($_POST['email'] , FILTER_VALIDATE_EMAIL)) return false;
  if(!isset($_POST['title']) || empty($_POST['title'])) return false;
  if(!isset($_POST['body']) || empty($_POST['body'])) return false;

  //put it in variables to facilitate using afterward
  $name = $_POST['name'];
  $email = $_POST['email'];
  $title = $_POST['title'];
  $body = $_POST['body'];
  $date = date("Y-m-d");

  //prepare a sql statment that will insert thses data into database and execute it after binding data into the statment
  global $DB;
  $stmt = $DB->prepare("INSERT INTO message (name, email, title, body, date) VALUES (?, ?, ?, ?, ?)");
  $stmt->bindParam(1, $name);
  $stmt->bindParam(2, $email);
  $stmt->bindParam(3, $title);
  $stmt->bindParam(4, $body);
  $stmt->bindParam(5, $date);
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


  <div class="container m-t-30 mobile-p-30">
    <div class="border-container main">
      <div class="row">
        <div class="offset-md-2 col-sm-8">

        <? if($response === null): ?>
          <h4 class="m-b-30">Send a message</h4>

          <form action="contact.php" method="POST">
            <div class="row">
              <div class="col-sm-12">
                <input type="text" name="name" placeholder="Name"/>
              </div>
              <div class="col-sm-12">
                <input type="email" name="email" placeholder="Email"/>
              </div>
              <div class="col-sm-12">
                <input type="text" name="title" placeholder="Message Title"/>
              </div>
              <div class="col-sm-12">
                <textarea name="body" placeholder="Message Text"></textarea>
              </div>
            </div>

            <input type="submit" id="btn" value="Send"/>

          </form>
        <? else: ?>
          <div class="text-center"><?php echo $response; ?></div>
        <? endif; ?>

        </div>
      </div>
    </div>
  </div>

  <?php include("footer.php");?>

</body>

</html>
