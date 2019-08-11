<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Add Hotel";

if (!empty($_POST)) {
  AddHotel();
}

function AddHotel() {
  global $DB, $success , $failed;
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
  if (!isset($_POST["hotel_manager"]) || empty($_POST["hotel_manager"])) {
    $failed = "hotel manager as not been selected";
    return false;
  }

  create_hotel($_POST["name"], $_POST["city"], $_POST["description"], $_POST["google_map"], $_POST["single_price"], $_POST["double_price"], $_POST["suit_price"], $_POST["hotel_manager"]);
}

function create_hotel($name, $city, $description, $google_map, $single_price, $double_price, $suit_price, $hotel_manager) {
  global $DB, $success, $failed;
  $stmt = $DB->prepare("INSERT INTO hotel (`hotel_id`, `name`, `city`, `description`, `google_map`, `single_price`, `double_price`, `suit_price`, `manager_id`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)");
  $stmt->bindParam(1, $name);
  $stmt->bindParam(2, $city);
  $stmt->bindParam(3, $description);
  $stmt->bindParam(4, $google_map);
  $stmt->bindParam(5, $single_price);
  $stmt->bindParam(6, $double_price);
  $stmt->bindParam(7, $suit_price);
  $stmt->bindParam(8, $hotel_manager);

  if ($stmt->execute()) {
    assignFacility($name);
    assignImages($name);
    $success = "Hotel has been added successfully";
  } else {
    $failed = "Failed to add this hotel";
  }

}

function assignFacility($name) {
  global $DB;
  $stmt = $DB->prepare("SELECT hotel_id FROM hotel WHERE name = ?");
  $stmt->bindParam(1, $name);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    $hotel_id = $row["hotel_id"];
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
  $upload_path = str_replace("admin", "uploads/", getcwd());
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

function getFacilities() {
  global $DB;
  $stms = $DB->prepare("SELECT * FROM facility");
  $stms->execute();
  $res = "";
  $count = 1;
  while ($row = $stms->fetch()) {
    $checked = '';
    if (isset($_POST[$count]) && !empty($_POST[$count])) {
      $checked = 'checked';
    }
    $count++;
    $res .= '
      <input type="checkbox" name="'.$row["facility_id"].'" '.$checked.' /> '.$row["name"].'<br>';
  }
  return $res;
}


function getUsers() {
  global $DB;
  $stms = $DB->prepare("SELECT * FROM user");
  $stms->execute();
  $res = "";
  $count = 1;
  while ($row = $stms->fetch()) {
    $selected = '';
    if (isset($_POST["hotel_manager"]) && !empty($_POST["hotel_manager"]) && $row["user_id"] == $_POST["hotel_manager"]) {
      $selected = ' selected';
    }
    $count++;
    $res .= '
        <option value="'.$row["user_id"].'"'.$selected.'> '.$row["first_name"]. ' ' .$row["last_name"] . '</option>';
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

    <form action="add-hotel.php" method="POST" enctype="multipart/form-data">
      <?php if(isset($success)) {
        header("refresh:0;url=hotels.php");
      } elseif(isset($failed)) {
        echo '<div id="Error">'.$failed.'</div>';
      }
      ?>
      <label>Hotel Name <input type="text" name="name" placeholder="Hotel Name .." value="<?php if(isset($_POST["name"])) echo $_POST["name"]; ?>"/></label>
      <label>City <input type="text" name="city" placeholder="Hotel City .." value="<?php if(isset($_POST["city"])) echo $_POST["city"]; ?>"/></label>
      <label>Hotel Description <textarea name="description" placeholder="Describe this hotel .. .."><?php if(isset($_POST["description"])) echo $_POST["description"]; ?></textarea></label>
      <label>Hotel on Google Map <input type="url" name="google_map" placeholder="URL of hotel on google map" value="<?php if(isset($_POST["google_map"])) echo $_POST["google_map"]; ?>" /></label>
      <label>Facilities</label>
      <?php echo getFacilities(); ?>


      <label>Single price <input type="text" name="single_price" placeholder="Single room price in egyptian pound" value="<?php if(isset($_POST["single_price"])) echo $_POST["single_price"]; ?>" /></label>
      <label>Double Price <input type="text" name="double_price" placeholder="Double room price in egyptian pound" value="<?php if(isset($_POST["double_price"])) echo $_POST["double_price"]; ?>" /></label>
      <label>Suit Price <input type="text" name="suit_price" placeholder="Suit price in egyptian pound" value="<?php if(isset($_POST["suit_price"])) echo $_POST["suit_price"]; ?>" /></label>

      Images <span id="add_image_upload">+</span>
      <div id="upload_images">
        <label><input type="file" name="images[]" /></label>
      </div>

      <label>Hotel Manager
      <select name="hotel_manager">
        <option value="">Select Hotel Manager</option>
        <?php echo getUsers(); ?>

      </select>
      </label>

      <input type="submit" id="btn" value="Submit"/>

    </form>

  </div>

  <?php include("footer.php"); ?>

</body>

</html>
