<?php session_start(); ?>
<?php include("general.php"); ?>

<?php

// if there is no get not post request of city redirect user to home page *since searching is based of city value so if it's empty will wouldn't excute valid query*
if (!isset($_POST["City"]) && !isset($_GET["City"])) {
  header("refresh:0;url=home.php");
  die;
}

//put post or get request value into $city
$city = "";

if (isset($_GET["City"])) {
  $city = $_GET["City"];
}

if (isset($_POST["City"])) {
  $city = $_POST["City"];
}

// put value to page_title in global array $data
global $data;
$data["page_title"] = "Search results for " . $city;

function getSearchResult() {
  global $DB;
  global $city;
  $result = "";
  // prepare sql statment to get all hotels in specific city
  $stmt = $DB->prepare("SELECT * FROM hotel where city = ?");
  //execute the prepared query after binding $city to the statment
  if ($stmt->execute(array($city))) {
    while ($row = $stmt->fetch()) {

      $id = $row['hotel_id'];
      $name = $row['name'];
      $image = getFirstImage($id);

      // build string contains html code concatinated with results of sql query
      $result .= '<div class="col-xs-12 col-md-3">
        <div class="hotel-box">
          <a title="'.$name.'" href="./hotel.php?id='.$id.'">
            <img alt="'.$name.'" src="'.$image.'">
          </a>
          <h2>'.$name.'</h2>
        </div>
      </div>
      ';
    }
  }

  if ($result == '') {
    $result = '<h4 class="col-sm-12 text-center">No results found !</h4>';
  } else {
    $result = '<h4 class="col-sm-12 text-center">Search results for '.$city.'</h4>' . $result;
  }
  return $result;
}

// function accept hotel id as a parameter and return the first image for this hotel
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

    <div class="ProgressBar">
      <div class="bar-outer">
        <div id="progress1" class="bar-inner">
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

    <div class="row">

      <?php echo getSearchResult(); ?>

    </div>

  </div>

<?php include("footer.php"); ?>

</body>

</html>
