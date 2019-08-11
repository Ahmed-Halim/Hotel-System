<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Edit FAQ";

if (!empty($_POST) && isset($_POST["id"]) && isset($_POST["question"]) && isset($_POST["answer"])) {
  SaveChanges();
}

function SaveChanges() {
  global $DB;
  $stmt = $DB->prepare("UPDATE faq SET question = ?, answer = ? WHERE faq_id = ?");
  $stmt->bindParam(1, $_POST["question"]);
  $stmt->bindParam(2, $_POST["answer"]);
  $stmt->bindParam(3, $_POST["id"]);
  $stmt->execute();
}

if (isset($_GET["id"]) && !empty($_GET["id"])) {
  $id = $_GET["id"];
  getFAQ($id);
}

if (isset($_POST["id"]) && !empty($_POST["id"])) {
  $id = $_POST["id"];
  getFAQ($id);
}


function getFAQ($id) {
  global $DB , $FAQ;
  $stmt = $DB->prepare("SELECT * FROM faq WHERE faq_id = ?");
  $stmt->bindParam(1, $id);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    $FAQ["id"] = $row["faq_id"];
    $FAQ["question"] = $row["question"];
    $FAQ["answer"] = $row["answer"];
  }
}

function getID() {
  global $FAQ;
  $id = "";
  if (isset($FAQ["id"])) {
    $id .= $FAQ["id"];
  }
  return $id;
}

function getQuestion() {
  global $FAQ;
  $question = "";
  if (isset($FAQ["question"])) {
    $question .= $FAQ["question"];
  }
  return $question;
}

function getAnswer() {
  global $FAQ;
  $answer = "";
  if (isset($FAQ["answer"])) {
    $answer .= $FAQ["answer"];
  }
  return $answer;
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

    <form action="edit-faq.php" method="POST">
      <input name="id" style="display: none;" value="<?php echo getID(); ?>">
      <label>Question<textarea name="question" placeholder="Question .."><?php echo getQuestion(); ?></textarea></label>
      <label>Answer<textarea name="answer" placeholder="Answer .."><?php echo getAnswer(); ?></textarea></label>
      <input type="submit" id="btn" value="Submit"/>
    </form>

  </div>

  <?php include("footer.php"); ?>

</body>

</html>
