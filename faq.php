<?php session_start(); ?>

<?php include("general.php"); ?>


<?php

//Declare global variable called $data to hold all genaric data in the page like title, metakeywords, metadescription, etc
global $data;
$data["page_title"] = "FAQ";

function FAQ() {
  global $DB;
  $res = "";
  //get all data in table faq
  $stmt = $DB->prepare("SELECT * FROM faq");
  if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
      $question = $row["question"];
      $answer = $row["answer"];
      //build a string of html code concatinted with question and answers fetched from the database
      $res .= '<li class="question"><i class="fas fa-angle-right"></i> '.$question.'</li>
      <li class="answer">'.$answer.'</li>
      ';

    }
  }
  return $res;
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
    <div class="border-container faq">
      <h2>Frequently asked questions</h2>
      <ul>

        <?php echo FAQ(); ?>

      </ul>


    </div>
  </div>

  <?php include("footer.php"); ?>

</body>

</html>
