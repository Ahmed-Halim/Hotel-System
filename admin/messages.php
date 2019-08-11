<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Messages";

function getMessages() {
  global $DB;
  $sql = "SELECT * FROM message";
  $result = $DB->query($sql)->fetchAll();
  $res = "";
  foreach ($result as $row) {
    $res .= '
    <tr>
      <td>'.$row["message_id"].'</td>
      <td>'.$row["name"].'</td>
      <td>'.$row["email"].'</td>
      <td>'.$row["title"].'</td>
      <td>'.$row["body"].'</td>
      <td>'.$row["date"].'</td>
    </tr>
    ';
  }

  $stmt = $DB->prepare("UPDATE message SET status = 1");
  $stmt->execute();

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
            <th>Name</th>
            <th>Email</th>
            <th>Message Title</th>
            <th>Message Text</th>
            <th>Date</th>
          </tr>

          <?php echo getMessages(); ?>

        </tbody>
      </table>
    </div>

  <?php include("footer.php"); ?>

</body>

</html>
