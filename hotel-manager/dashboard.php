<?php
session_start();
include_once("DB.php");
include_once("validation.php");

$data["page_title"] = "Dashboard";

function getPageTitle() {
  global $data;
  $title = "";
  if (isset($data["page_title"])) {
    $title .= $data["page_title"];
  }
  return $title;
}


function reservations_count() {
  global $DB, $Hotel_ID;
  $stmt = $DB->prepare("SELECT COUNT(*) AS num FROM reservation WHERE hotel_id = ?");
  $stmt->bindParam(1, $Hotel_ID);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    return $row["num"];
  }
  return 0;
}

function income_count() {
  global $DB, $Hotel_ID;
  $stmt = $DB->prepare("SELECT SUM(price) AS income FROM reservation WHERE hotel_id = ?");
  $stmt->bindParam(1, $Hotel_ID);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    return round($row["income"]/1000) . "K";
  }
  return 0;
}


function users_count() {
  global $DB, $Hotel_ID;
  $stmt = $DB->prepare("SELECT COUNT(user_id) AS num FROM reservation WHERE hotel_id = ?");
  $stmt->bindParam(1, $Hotel_ID);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    return $row["num"];
  }
  return 0;
}

function rank() {
  global $DB, $Hotel_ID;
  $stmt = $DB->prepare('SET @rank=0');
  $stmt->execute();
  $stmt = $DB->prepare('SELECT rank FROM(SELECT @rank:=@rank+1 AS rank, COUNT(*) AS res, hotel_id FROM `reservation` GROUP BY hotel_id) AS all_hotels WHERE all_hotels.hotel_id = ?');
  $stmt->bindParam(1, $Hotel_ID);
  $stmt->execute();
  if ($row = $stmt->fetch()) {
    return $row["rank"];
  }
  return 0;
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
      <div id="dashboard-items">

          <div id="dashboard-1"><h1><?php echo reservations_count(); ?></h1><p>Reservation</p></div>
          <div id="dashboard-2"><h1><?php echo users_count(); ?></h1><p>Customer</p></div>
          <div id="dashboard-3"><h1><?php echo income_count(); ?> LE</h1><p>Income</p></div>
          <div id="dashboard-4"><h1><?php echo rank(); ?></h1><p>Rank</p></div>

      </div>

      <canvas id="myChart"></canvas>

    </div>

    <?php include("footer.php"); ?>
    <script src="includes/charts/Chart.js"></script>
    <script>


    var number_reservations = [];
    var dates = [];

    $.ajax({
      type: 'POST',
      url: './reservations_json.php',
      async:false,
      success: function(data) {
        var mydata = JSON.stringify([data]);
        mydata = JSON.parse(data);
        for (var date in mydata) {
          dates.push(date);
          number_reservations.push(mydata[date]);
        }
      }
    });


    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: dates,
        datasets: [{
          label: ' No. of reservations',
          data: number_reservations,
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
          ],
          borderWidth: 2
        }]
      },
      options: {
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    });
    </script>


  </body>
</html>
