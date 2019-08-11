<div id="cookies_content"><?php echo getCookies(); ?></div>
<div id="aboutus_content"><?php echo getAboutUs(); ?></div>
<div id="terms_content"><?php echo getTerms(); ?></div>
<div id="privacy_policy_content"><?php echo getPrivacyPolicy(); ?></div>


<footer <?php if (basename($_SERVER['SCRIPT_NAME']) != "home.php" && basename($_SERVER['SCRIPT_NAME']) != "register.php" && basename($_SERVER['SCRIPT_NAME']) != "login.php") echo 'class="dark"'; ?>>

  <div class="container">
    <div class="row">

      <div class="col-sm-4 col-md-4">
        <h3><strong>MORE FROM US</strong></h3>
        <ul class="clearfix">
          <li id="aboutus">About us</li>
          <li id="terms">Terms of use</li>
          <li id="privacy">Privacy Policy</li>
        </ul>
      </div>

      <div class="col-sm-4 col-md-3">
        <h3><strong>Social Media</strong></h3>
        <ul class="clearfix">
          <li><a title="Facebook" href="<?php echo getFacebook(); ?>"><i class="fab fa-facebook-square"></i> Facebook</a></li>
          <li><a title="Twitter" href="<?php echo getTwitter(); ?>"><i class="fab fa-twitter"></i> Twitter</a></li>
          <li><a title="Youtube" href="<?php echo getYoutube(); ?>"><i class="fab fa-youtube"></i> Youtube</a></li>
          <li><a title="Instagram" href="<?php echo getInstagram(); ?>"><i class="fab fa-instagram"></i> Instagram</a></li>
        </ul>
      </div>

      <div class="col-sm-4 col-md-3">
        <h3><strong>Help !</strong></h3>
        <ul class="clearfix">
          <li><a title="FAQ" href="./faq.php">FAQ</a></li>
          <li><a title="Complains" href="./complains.php">Complains</a></li>
          <li><a title="Contact us" href="./contact.php">Contact us</a></li>
        </ul>

      </div>

      <div class="col-sm-4 col-md-2">

        <h3><strong>Get Our App</strong></h3>
        <ul class="clearfix">
          <li><a title="IOS App" href="<?php echo getIOS(); ?>"><img alt="" class="download-app" src="images/ios.png" /></a></li>
          <li><a title="Adnroid App" href="<?php echo getAndroid(); ?>"><img alt="" class="download-app" src="images/android.png" /></a></li>
        </ul>

      </div>

    </div>
  </div>
</footer>

<i id="top" class="fa fa-chevron-circle-up" aria-hidden="true"></i>

<div class="popupForm">
  <span class="closeBtn"><i class="fa fa-times" aria-hidden="true"></i></span>
  <h4 id="popupTitle">Title</h4>
  <p id="popupBody">Body</p>
</div>



<div class="annoncement">
  <p>This website uses cookies</p>
  <span id="more">More</span>
  <span class="closeBtn"><i class="fa fa-times" aria-hidden="true"></i></span>
</div>

<!-- JavaScript -->
<script src="includes/jquery/jquery.min.js"></script>
<script src="includes/jquery/jquery-ui.js"></script>
<script src="includes/scripts.js"></script>
