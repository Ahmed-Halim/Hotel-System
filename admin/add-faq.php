<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Add FAQ";

if (!empty($_POST) && isset($_POST["question"]) && !empty($_POST["question"]) && isset($_POST["answer"]) && !empty($_POST["answer"])) {
  AddFAQ();
}

function AddFAQ() {
  global $DB, $success , $failed;
  if (!isset($_POST["question"]) || empty($_POST["question"])) {
    $failed = "question is empty";
    return false;
  }

  if (!isset($_POST["answer"]) || empty($_POST["answer"])) {
    $failed = "answer is empty";
    return false;
  }

  $stmt = $DB->prepare("INSERT INTO `faq` (`faq_id`, `question`, `answer`) VALUES (NULL, ?, ?)");
  $stmt->bindParam(1, $_POST["question"]);
  $stmt->bindParam(2, $_POST["answer"]);
  if ($stmt->execute()) {
    $success = "DONE";
  } else {
    $failed = "Failed";
  }
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

    <form action="add-faq.php" method="POST">
      <?php if(isset($success)) {
        echo '<div id="Success">'.$success.'</div>';
      } elseif(isset($failed)) {
        echo '<div id="Error">'.$failed.'</div>';
      }
      ?>
      <label>Question<textarea name="question" placeholder="Question .."></textarea></label>
      <label>Answer<textarea name="answer" placeholder="Answer .."></textarea></label>
      <input type="submit" id="btn" value="Submit"/>
    </form>

  </div>

  <?php include("footer.php"); ?>

</body>

</html>
