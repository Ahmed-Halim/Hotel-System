<?php session_start(); ?>
<?php include("general.php"); ?>
<?php

//put page title in array $data
global $data;
$data["page_title"] = "All Hotels";

function getAllHotels($order) {
  global $DB;
  $str = "";
  $stmt = "";

  //prepare different sql statment for each order
  if ($order == "name") {
    $stmt = $DB->prepare("SELECT * FROM hotel ORDER BY name");
  } elseif($order == "lowprice") {
    $stmt = $DB->prepare("SELECT * FROM hotel ORDER BY single_price ASC");
  } elseif($order == "highprice") {
    $stmt = $DB->prepare("SELECT * FROM hotel ORDER BY single_price DESC");
  }
  if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {

      $id = $row['hotel_id'];
      $name = $row['name'];
      $image = getFirstImage($id);
      $starting_price = $row['single_price'];
      $city = $row['city'];

      //build a string of html code contains all hotels fetched from the database
      $str .= '
      <div class="col-xs-12 col-md-3">
        <div class="hotel-box">
          <span>Start from '.$starting_price.' LE</span>
          <a title="'.$name.'" href="hotel.php?id='.$id.'">
            <img alt="'.$name.'" src="'.$image.'">
          </a>
          <h2>'.$name.'</h2>
          <em>'.$city.'</em>
        </div>
      </div>
          ';
    }
  }
  return $str;
}


function getFirstImage($id) {
  global $DB;
  $stmt = $DB->prepare("SELECT image FROM hotel_image WHERE hotel_id = ?");
  $stmt->bindParam(1, $id);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    return "./uploads/".$row["image"];
  }
  return "./images/default-image.png";
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


    <h2 class="m-t-30 text-center">Hotels</h2>
    <div id="filteration">
      <span id="FilterBy">Filter By</span>
      <a title="Name" href="./hotels.php?order=name" id="FilterByName">Name</a>
      <a title="Low Price" href="./hotels.php?order=lowprice" id="FilterByLowPrice">Low Price</a>
      <a title="High Price" href="./hotels.php?order=highprice" id="FilterByHighPrice">High Price</a>
    </div>


    <div class="row">
    <?php
      //order by default is by name
      $order = "name";
      //if there is get request with name order then change value of $order and sen it as an argument to getAllHotels function
      if (isset($_GET["order"])) {
        if ($_GET["order"] == "name") {
          $order = "name";
        }
        if ($_GET["order"] == "lowprice") {
          $order = "lowprice";
        }
        if ($_GET["order"] == "highprice") {
          $order = "highprice";
        }
      }
      echo getAllHotels($order);
    ?>
    </div>

  </div>


  <?php include("footer.php"); ?>

</body>

</html>
