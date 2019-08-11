<?php

function getComplainsCount() {
  global $DB;
  $stmt = $DB->prepare("SELECT COUNT(*) AS num FROM complain WHERE status = 0");
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    return $row["num"];
  }
  return 0;
}

function getMessagesCount() {
  global $DB;
  $stmt = $DB->prepare("SELECT COUNT(*) AS num FROM message WHERE status = 0");
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    return $row["num"];
  }
  return 0;
}

?>

<div class="sidebar">
  <ul>
    <li><a title="" href="./dashboard.php"><i class="material-icons">home</i> Dashboard</a></li>
    <li><a title="" href="./hotels.php"><i class="material-icons">hotel</i> Hotels</a></li>
    <li><a title="" href="./users.php"><i class="material-icons">people</i> Users</a></li>
    <li><a title="" href="./reservations.php"><i class="material-icons">event_available</i> Reservations</a></li>
    <li><a title="" href="./complains.php"><i class="material-icons">announcement</i> Complains<?php $ComplainsCount = getComplainsCount(); if ($ComplainsCount > 0) echo '<span id="complains-number">'.getComplainsCount().'</span>'; ?></a></li>
    <li><a title="" href="./messages.php"><i class="material-icons">mail</i> Messages<?php $MessagesCount = getMessagesCount(); if ($MessagesCount > 0) echo '<span id="messages-number">'.getMessagesCount().'</span>'; ?></a></li>
    <li><a title="" href="./faq.php"><i class="material-icons">question_answer</i> FAQ</a></li>
    <li><a title="" href="./general.php"><i class="material-icons">settings</i> General Settings</a></li>
    <li><a title="" href="./logout.php"><i class="material-icons">directions_run</i> Logout</a></li>
  </ul>
</div>
