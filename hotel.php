<?php session_start(); ?>
<?php include("general.php"); ?>
<?php
if (isset($_GET["id"]) && !empty($_GET["id"]) && filter_var($_GET["id"], FILTER_VALIDATE_INT)) {
  getHotel($_GET["id"]);
} else {
  header("refresh:0;url=home.php" );
  die;
}

global $data;
$data["page_title"] = getName();

function getHotel($id) {
  global $DB;
  global $hotel;
  $stmt = $DB->prepare("SELECT * FROM hotel WHERE hotel_id = ?");
  $stmt->bindParam(1, $id);
  if ($stmt->execute()) {
  if ($row = $stmt->fetch()) {
      $hotel["id"] = $row['hotel_id'];
      $hotel["name"] = $row['name'];
      $hotel["city"] = $row['city'];
      $hotel["description"] = $row['description'];
      $hotel["google_map"] = $row['google_map'];
      $hotel["facility"] = getFacilityArray($row['hotel_id']);
      $hotel["images"] = getImagesArray($row['hotel_id']);
      $hotel["single_price"] = $row['single_price'];
      $hotel["double_price"] = $row['double_price'];
      $hotel["suit_price"] = $row['suit_price'];
  }
  }
}

function getFacilityArray($hotel_id) {
  global $DB;
  $facilities = array();
  $stmt = $DB->prepare("SELECT facility.name FROM hotel_facility INNER JOIN facility ON hotel_facility.facility_id = facility.facility_id WHERE hotel_facility.hotel_id = ?");
  $stmt->bindParam(1, $hotel_id);
  $stmt->execute();
  while ($row = $stmt->fetch()) {
    array_push($facilities, $row["name"]);
  }
  return $facilities;
}


function getImagesArray($hotel_id) {
  global $DB;
  $images = array();
  $stmt = $DB->prepare("SELECT image FROM hotel_image WHERE hotel_id = ?");
  $stmt->bindParam(1, $hotel_id);
  $stmt->execute();
  while ($row = $stmt->fetch()) {
    array_push($images, $row["image"]);
  }
  return $images;
}


//Accessors
function getID() {
  global $hotel;
  $id = "";
  if (isset($hotel["id"])) {
    $id = $hotel["id"];
  }
  return $id;
}

function getName() {
  global $hotel;
  $name = "";
  if (isset($hotel["name"])) {
    $name = $hotel["name"];
  }
  if ($name == "") $name = "No name";
  return $name;
}

function getCity() {
  global $hotel;
  $city = "";
  if (isset($hotel["city"])) {
    $city = $hotel["city"];
  }
  if ($city == "") $city = "No city";
  return $city;
}

function getDescription() {
  global $hotel;
  $description = "";
  if (isset($hotel["description"])) {
    $description = $hotel["description"];
  }
  if ($description == "") $description = "No description";
  return $description;
}

function getGoogleMap() {
  global $hotel;
  $google_map = "";
  if (isset($hotel["google_map"])) {
    $google_map = $hotel["google_map"];
  }
  if ($google_map == "") $google_map = "https://maps.google.com/maps?q=sofitel&t=&z=13&ie=UTF8&iwloc=&output=embed";
  return $google_map;
}

function getFacility() {
  global $hotel;
  $facilities = "";
  $icons = array("Air Conditioner" => "ac_unit", "Fitness centre" => "fitness_center", "Wifi" => "wifi", "Room service" => "room_service", "Swimming pool" => "pool", "Restaurant" => "restaurant", "TV" => "tv", "Locker" => "vpn_key", "Parking" => "local_parking", "Airport Shuttle" => "airport_shuttle", "Spa" => "spa");
  if (isset($hotel["facility"])) {
    foreach ($hotel["facility"] as $facility) {
      $icon = "done";
      if (isset($icons[$facility])) {
          $icon = $icons[$facility];
      }
      $facilities .= '<div class="col-sm-6 col-md-3"><i class="material-icons">'. $icon .'</i> '.$facility.'</div>';
    }
  }
  if ($facilities == "") $facilities = '<div class="col-sm-6 col-md-3"><i class="material-icons">close</i> No facilities</div>';
  return $facilities;
}

function getImages() {
  global $hotel;
  $images = "";
  if (isset($hotel["images"])) {
    foreach ($hotel["images"] as $image) {
      if (!empty($image)) {
        $images .= '<img alt="'.getName().'" class="fadeIn SlideImg" src="./uploads/'.$image.'">
            ';
      }
    }
  }
  if ($images == "") {
    $images = '<img alt="'.getName().'" class="fadeIn SlideImg" src="./images/default-image.png">';
  }

  return $images;
}

function getSinglePrice() {
  global $hotel;
  $single_price = "0";
  if (isset($hotel["single_price"])) {
    $single_price = $hotel["single_price"];
  }
  return $single_price;
}

function getDoublePrice() {
  global $hotel;
  $double_price = "0";
  if (isset($hotel["double_price"])) {
    $double_price = $hotel["double_price"];
  }
  return $double_price;
}

function getSuitPrice() {
  global $hotel;
  $suit_price = "0";
  if (isset($hotel["suit_price"])) {
    $suit_price = $hotel["suit_price"];
  }
  return $suit_price;
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
        <div id="progress2" class="bar-inner">
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


      <div class="row">
        <div class="col-sm-12 col-md-5">

          <div class="slideshow-container">

            <?php echo getImages(); ?>

            <a title="" class="prev" onclick="plusSlide(-1)">&#10094;</a>
            <a title="" class="next" onclick="plusSlide(1)">&#10095;</a>
          </div>

        </div>

        <div class="col-sm-12 col-md-7">

          <h2><?php echo getName(); ?></h2>
          <em id="Hotel-Location"><?php echo getCity();; ?></em>
          <p id="Hotel-Description"><?php echo getDescription(); ?></p>
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12 m-t-30 m-b-30">
          <h4>Facilities</h4>
          <div class="facilities row">

            <?php echo getFacility(); ?>

          </div>
        </div>
      </div>

      <h4 class="m-b-20">Location</h4>
      <iframe id="gmap_canvas" src="<?php echo getGoogleMap(); ?>"></iframe>

      <h5 id="make-reservation">Book Now</h5>

      <div id="Error" class="m-b-30"></div>

      <form autocomplete="off" action="payment.php" method="POST" id="Reservation">
        <div class="row first-row">

          <input id="hotel_id" name="hotel_id" value="<?php echo getID(); ?>" />

          <div class="col-sm-6">
            <span>Checkin Date</span>
            <i class="fas fa-sign-in-alt"></i>
            <input id="checkin" name="checkin" placeholder="Check-in date" <?php if (isset($_COOKIE["checkin"])) echo 'value="'.$_COOKIE["checkin"].'"'; ?>/>
          </div>
          <div class="col-sm-6">
            <span>Nights</span>
            <i class="fas fa-bed"></i>
            <select name="nights" id="nights">
              <option value="0">Number of nights</option>
              <option value="1" <?php if (isset($_COOKIE["nights"]) && $_COOKIE["nights"] == 1) echo 'selected'; ?>>1</option>
              <option value="2" <?php if (isset($_COOKIE["nights"]) && $_COOKIE["nights"] == 2) echo 'selected'; ?>>2</option>
              <option value="3" <?php if (isset($_COOKIE["nights"]) && $_COOKIE["nights"] == 3) echo 'selected'; ?>>3</option>
              <option value="4" <?php if (isset($_COOKIE["nights"]) && $_COOKIE["nights"] == 4) echo 'selected'; ?>>4</option>
              <option value="5" <?php if (isset($_COOKIE["nights"]) && $_COOKIE["nights"] == 5) echo 'selected'; ?>>5</option>
              <option value="6" <?php if (isset($_COOKIE["nights"]) && $_COOKIE["nights"] == 6) echo 'selected'; ?>>6</option>
              <option value="7" <?php if (isset($_COOKIE["nights"]) && $_COOKIE["nights"] == 7) echo 'selected'; ?>>7</option>
            </select>
          </div>
        </div>

        <input id="room" name="room" />
        <input id="payment" name="payment" />

        <div class="row">
          <div class="col-sm-4 col-md-4">
            <div class="RoomType">Single</div>
            <div id="Single-Price"><?php echo getSinglePrice(); ?></div>
          </div>
          <div class="col-sm-4 col-md-4">
            <div class="RoomType">Double</div>
            <div id="Double-Price"><?php echo getDoublePrice(); ?></div>
          </div>
          <div class="col-sm-4 col-md-4">
            <div class="RoomType">Suit</div>
            <div id="Suit-Price"><?php echo getSuitPrice(); ?></div>
          </div>
        </div>

        <div id="Success">
          <div class="cart">
            <ul>
              <li><strong>Number of nights:</strong> <span id="numberNights"></span> night</li>
              <li><strong>Room price:</strong> <span id="roomPrice"></span> LE</li>
              <li><strong>Added value tax (14%):</strong> <span id="addedValuePrice"></span> LE</li>
              <li><strong>Total due:</strong> <span id="totalDue"></span> LE</li>
            </ul>
          </div>

          <div class="row">
            <div class="col-sm-12">
              <input type="submit" value="Checkout" />
            </div>
          </div>
        </div>


      </form>




    </div>

  </div>

  <?php include("footer.php"); ?>

</body>

</html>
