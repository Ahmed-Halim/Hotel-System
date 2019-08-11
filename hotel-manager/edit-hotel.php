<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Edit Hotel";

if (isset($_POST["name"])) {
  SaveChanges();
}

function SaveChanges() {
  global $DB, $Hotel, $success , $failed;
  if (!isset($_POST["name"]) || empty($_POST["name"])) {
    $failed = "name is empty";
    return false;
  }
  if (!isset($_POST["city"]) || empty($_POST["city"])) {
    $failed = "city is empty";
    return false;
  }
  if (!isset($_POST["description"]) || empty($_POST["description"])) {
    $failed = "description is empty";
    return false;
  }
  if (!isset($_POST["google_map"]) || empty($_POST["google_map"])) {
    $failed = "google map url is empty";
    return false;
  }
  if (!isset($_POST["single_price"]) || empty($_POST["single_price"])) {
    $failed = "single price is empty";
    return false;
  }
  if (!isset($_POST["double_price"]) || empty($_POST["double_price"])) {
    $failed = "double price is empty";
    return false;
  }
  if (!isset($_POST["suit_price"]) || empty($_POST["suit_price"])) {
    $failed = "suit price is empty";
    return false;
  }

  edit_hotel($_POST["id"], $_POST["name"], $_POST["city"], $_POST["description"], $_POST["google_map"], $_POST["single_price"], $_POST["double_price"], $_POST["suit_price"]);
}

function edit_hotel($hotel_id, $name, $city, $description, $google_map, $single_price, $double_price, $suit_price) {
  global $DB, $success, $failed;
  $stmt = $DB->prepare("UPDATE hotel SET name = ?, city = ?, description = ?, google_map = ?, single_price = ?, double_price = ?, suit_price = ? WHERE hotel_id = ?");
  $stmt->bindParam(1, $name);
  $stmt->bindParam(2, $city);
  $stmt->bindParam(3, $description);
  $stmt->bindParam(4, $google_map);
  $stmt->bindParam(5, $single_price);
  $stmt->bindParam(6, $double_price);
  $stmt->bindParam(7, $suit_price);
  $stmt->bindParam(8, $hotel_id);

  if ($stmt->execute()) {
    assignFacility($name);
    assignImages($name);
    $success = "Hotel has been updated successfully";
  } else {
    $failed = "Failed to update this hotel";
  }

}

function assignFacility($name) {
  global $DB;

  $stmt = $DB->prepare("SELECT hotel_id FROM hotel WHERE name = ?");
  $stmt->bindParam(1, $name);
  $stmt->execute();

  if ($row = $stmt->fetch()) {
    $hotel_id = $row["hotel_id"];

    $stmt = $DB->prepare("DELETE FROM hotel_facility WHERE hotel_id = ?");
    $stmt->bindParam(1, $hotel_id);
    $stmt->execute();

    for ($i = 1; $i <= 12; $i++) {
      if (isset($_POST[$i]) && !empty($_POST[$i])) {
        $facility_id = $i;
        $stmt = $DB->prepare("INSERT INTO hotel_facility VALUES (? , ?)");
        $stmt->bindParam(1, $hotel_id);
        $stmt->bindParam(2, $facility_id);
        $stmt->execute();
      }
    }
  }
}


function assignImages($name) {
  global $DB;
  $stmt = $DB->prepare("SELECT hotel_id FROM hotel WHERE name = ?");
  $stmt->bindParam(1, $name);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    $hotel_id = $row["hotel_id"];
    $total = count($_FILES['images']['name']);
    for( $i=0 ; $i < $total ; $i++ ) {
      if (!empty($_FILES['images']['name'][$i])) {
        $image = $_FILES['images']['name'][$i];
        $stmt = $DB->prepare("INSERT INTO hotel_image VALUES (?, ?)");
        $stmt->bindParam(1, $hotel_id);
        $stmt->bindParam(2, $image);
        $stmt->execute();
      }
    }

    upload_images($_FILES['images']);

  }
}


function upload_images($images) {
  $upload_path = str_replace("hotel-manager", "uploads/", getcwd());
  $fileExtensions = ['jpeg','jpg','png'];

  $total = count($images['name']);
  for ($i = 0; $i < $total; $i++) {
    $fileName = $images['name'][$i];
    $fileSize = $images['size'][$i];
    $fileTmpName  = $images['tmp_name'][$i];
    $fileType = $images['type'][$i];
    $fileExtension = @strtolower(end(explode('.',$fileName)));
    $to = $upload_path . basename($fileName);
    if (in_array($fileExtension,$fileExtensions) && $fileSize <= 2000000) {
      move_uploaded_file($fileTmpName, $to);
    }
  }
}


getHotel($Hotel_ID);


function getHotel($id) {
  global $DB;
  global $Hotel;
  $stmt = $DB->prepare("SELECT * FROM hotel WHERE hotel_id = ?");
  $stmt->bindParam(1, $id);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    $Hotel["id"] = $row["hotel_id"];
    $Hotel["name"] = $row["name"];
    $Hotel["city"] = $row["city"];
    $Hotel["description"] = $row["description"];
    $Hotel["google_map"] = $row["google_map"];
    $Hotel["single_price"] = $row["single_price"];
    $Hotel["double_price"] = $row["double_price"];
    $Hotel["suit_price"] = $row["suit_price"];
    $Hotel["facility"] = array();
    $Hotel["images"] = array();

    $stmt = $DB->prepare("SELECT facility_id FROM hotel_facility WHERE hotel_id = ?");
    $stmt->bindParam(1, $Hotel["id"]);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
      array_push($Hotel["facility"] , $row["facility_id"]);
    }

    $stmt = $DB->prepare("SELECT image FROM hotel_image WHERE hotel_id = ?");
    $stmt->bindParam(1, $Hotel["id"]);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
      array_push($Hotel["images"] , $row["image"]);
    }
  }
}

function getFacilities() {
  global $DB, $Hotel;
  $stmt = $DB->prepare("SELECT * FROM facility");
  $stmt->execute();
  $res = "";
  while ($row = $stmt->fetch()) {
    $checked = '';
    if (in_array($row["facility_id"], $Hotel["facility"])) {
      $checked = 'checked';
    }
    $res .= '
      <input type="checkbox" name="'.$row["facility_id"].'" '.$checked.' /> '.$row["name"].'<br>';
  }
  return $res;
}


function getImages() {
  global $Hotel;
  $res = "";
  foreach ($Hotel["images"] as $image) {
    $func = "delete_hotel_image('".$image."')";
    $res .= '
      <div class="hotel_image_block" id="'.$image.'">
        <img class="hotel_image" src="../uploads/'.$image.'">
        <div class="x" onclick="'.$func.'">X</div>
      </div>
      ';
  }
  if (!empty($res)) {
    $res = "<div>".$res."</div>";
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

    <form action="edit-hotel.php" method="POST" enctype="multipart/form-data">
      <?php if(isset($success)) {
        header("refresh:0;url=dashboard.php");
      } elseif(isset($failed)) {
        echo '<div id="Error">'.$failed.'</div>';
      }
      ?>
      <input name="id" style="display: none;" value="<?php if(isset($Hotel["id"])) echo $Hotel["id"]; ?>">
      <label>Hotel Name <input type="text" name="name" placeholder="Hotel Name .." value="<?php if(isset($Hotel["name"])) echo $Hotel["name"]; ?>"/></label>
      <label>Location <input type="text" name="city" placeholder="Hotel City .." value="<?php if(isset($Hotel["city"])) echo $Hotel["city"]; ?>"/></label>
      <label>Hotel Description <textarea name="description" placeholder="Describe this hotel .. .."><?php if(isset($Hotel["description"])) echo $Hotel["description"]; ?></textarea></label>
      <label>Hotel on Google Map <input type="url" name="google_map" placeholder="URL of hotel on google map" value="<?php if(isset($Hotel["google_map"])) echo $Hotel["google_map"]; ?>" /></label>
      <label>Facilities</label>
      <?php echo getFacilities(); ?>


      <label>Single price <input type="text" name="single_price" placeholder="Single room price in egyptian pound" value="<?php if(isset($Hotel["single_price"])) echo $Hotel["single_price"]; ?>" /></label>
      <label>Double Price <input type="text" name="double_price" placeholder="Double room price in egyptian pound" value="<?php if(isset($Hotel["double_price"])) echo $Hotel["double_price"]; ?>" /></label>
      <label>Suit Price <input type="text" name="suit_price" placeholder="Suit price in egyptian pound" value="<?php if(isset($Hotel["suit_price"])) echo $Hotel["suit_price"]; ?>" /></label>

      <?php echo getImages(); ?>
      Images <span id="add_image_upload">+</span>
      <div id="upload_images">
        <label><input type="file" name="images[]" /></label>
      </div>


      <input type="submit" id="btn" value="Submit"/>

    </form>

  </div>

  <?php include("footer.php"); ?>

</body>

</html>
