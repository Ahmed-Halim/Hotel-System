<?php session_start(); ?>
<?php include("general.php"); ?>

<?php
function getPopularDestinations() {
  $res = "";
  global $DB;
  $stmt = $DB->prepare("SELECT popular_destination.city AS city, popular_destination.image AS image, COUNT(hotel.hotel_id) AS properties FROM popular_destination LEFT JOIN hotel ON popular_destination.city = hotel.city GROUP BY city");
  if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {
      //build a string of html code contains all popular destinations fetched from the database
      $res .= '
      <div class="col-xs-12 col-sm-6 col-md-3">
        <a title="'.$row["city"].'" href="search.php?City='.$row["city"].'">
          <div class="city-box"><span>'.$row["properties"].' Properties</span>
            <img alt="" src="images/'.$row["image"].'">
            <h2>'.$row["city"].'</h2>
          </div>
        </a>
      </div>
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

    <div class="container">
      <div class="row">
        <div class="col-sm-12 col-md-8 auto">
          <div class="book-section text-center">
            <h1>Booking an hotel becomes easy !</h1>
            <p>Get the best prices on 2,000+ properties, worldwide</p>

            <form autocomplete="off" action="search.php" method="POST" name="Book-Form" id="book-form">

              <div class="row">
                <div class="col-sm-12">
                  <i class="fas fa-search search-icon"></i>
                  <input type="text" name="City" id="City" placeholder="Enter a city name .." />
                </div>
              </div>


              <div class="row">
                <div class="col-sm-6">
                  <div class="shift-right">
                    <span>Checkin Date</span>
                    <i class="fas fa-calendar-alt"></i>
                    <input type="text" name="Checkin" id="checkindate" placeholder="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>" />
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="shift-left">
                    <span>Nights</span>
                    <i class="fas fa-bed"></i>
                    <select name="Nights" id="Nights">
                      <option value="1" selected>1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                    </select>
                  </div>
                </div>

              </div>

              <div id="Error"></div>

              <div class="row">
                <div class="col-sm-12">
                  <input id="search-btn" type="submit" value="Search" />
                </div>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>

  </header>

  <div class="zigzag"></div>

  <div class="container mobile-p-30">

    <h2 class="h2-Section">Most popular destinations</h2>


    <div class="row">

      <?php echo getPopularDestinations(); ?>

    </div>

  </div>

  <div class="subscribe-bg">
    <h3 class="m-b-30">Keep in touch by subscribe to our newsletter !</h3>

    <form id="subcribption-form" action="subscribe.php" method="POST">

      <div class="col-xs-12 col-sm-12 col-md-5 auto">
        <div class="row">
          <div class="col-sm-7">
            <input name="email" id="subscribe-input" class="subscribe-input" placeholder="Enter your Email .."/>
          </div>

          <div class="col-sm-5">
            <input id="subscribe-btn" class="subscribe-btn" type="submit" value="Subscribe"/>
          </div>
        </div>
      </div>

    </form>

    <div id="subcribption-success">You Have Been Successfully Subscribed</div>

  </div>


  <?php include("footer.php");?>

</body>

</html>
