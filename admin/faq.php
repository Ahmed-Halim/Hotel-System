<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "FAQ";

if (isset($_GET["delete"]) && !empty($_GET["delete"])) {
  $faq_id = $_GET["delete"];
  DeleteFAQ($faq_id);
}

function DeleteFAQ($faq_id) {
  global $DB;
  $stmt = $DB->prepare("DELETE FROM faq WHERE faq_id = ?");
  $stmt->bindParam(1, $faq_id);
  $stmt->execute();
}


function getFAQ() {
  global $DB;
  $sql = "SELECT * FROM faq";
  $result = $DB->query($sql)->fetchAll();
  $res = "";
  foreach ($result as $row) {
    $res .= '
    <tr>
      <td>'.$row["faq_id"].'</td>
      <td>'.$row["question"].'</td>
      <td>'.$row["answer"].'</td>
      <td>
        <a title="" href="./edit-faq.php?id='.$row["faq_id"].'"><i class="material-icons edit">edit</i></a>
        <a title="" href="./faq.php?delete='.$row["faq_id"].'"><i class="material-icons delete">delete</i></a>
      </td>
    </tr>
    ';
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
      <a title="" id="btn" href="./add-faq.php"><i class="material-icons">add</i> Add New FAQ</a>

      <table>
        <tbody>
          <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Answer</th>
            <th>Action</th>
          </tr>
          <?php echo getFAQ(); ?>
        </tbody>
      </table>
    </div>

  <?php include("footer.php"); ?>

</body>

</html>
