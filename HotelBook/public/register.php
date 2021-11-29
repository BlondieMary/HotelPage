<?php
require_once __DIR__.'\..\boot\boot.php';

use Hotel\User;

// Check for existing user
if(!empty(User::getCurrentUserId()))
{
  header("Location: index.php");
  
}

?>
<!DOCTYPE>
<html>
  <head>
    <meta charset= "UTF-8" >
    <meta name= "viewport" content=" width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <link rel="icon" href="assets/css/images/favicon.png" type="image/x-icon">
    <title>College Link </title>
    <style type="text/css">
      body{
        background: #333;
      }
    </style>
    <link href="assets/css/style_one.css" type="text/css" rel="stylesheet"/  >
    <script src="http://code.jquery.com/jquery-2.1.3.js"></script>
    <script src="http://code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script  src="assets/js/script.js" type="javascript"></script>
    <script  src="assets/js/form_valid.js" type="text/javascript"></script>
  </head>
  <body>
  <header>
    <div class="primary-menu text-left">
	 <span>College Link 2021</span>
      <div class="nav text-right">
        <label class="togle" for="toggle">&#9776;</label>
        <input type="checkbox" id="toggle"/>
        <div class="menu">
           <ul><li><a href="index.php" ><i class="fas fa-home"></i>Home</a></li>
        </div>
      </div>
    </div>
  </header>
  <div class="body-register flex-box">
  <section class="container-register-form text-center">
  <h1>College Link 2021</h1>
   <h1>A COMFORTABLE & MEMORABLE EXPERIENCE</h1>
    <div class="title"><strong>Registration</strong></div>
	<h1> REGISTRATION </h1>
      <form class="registerForm" name="registerForm" id="registerForm" action="actions/register.php" autocomplete="off" method="post">
        <div class="user-details flex-box">
          <div class="input-box">
            <div class="details" required><i class="fas fa-user"></i> Username <span style="color:red">*</span></div>
              <input  id="userName" type="text" placeholder="Enter User Name" name="name" required >
              <div class="userNames">
                <i class="fas fa-check-circle not-valid"></i>
              </div>
             <div class="text-danger userName-error"></div>
          </div>
          <div class="input-box">
            <div class="details"><i class="fas fa-envelope"></i> Email<span style="color:red">*</span></div>
            <input id="email" type="text"placeholder="Enter email" class="email" name="email" required >
            <div class="checkEmails"><i class="fas fa-check-circle not-valid"></i></div>
            <div class="text-danger email-error">
               Must be a valid email address!
            </div>
          </div>
          <div class="input-box">
            <div class="details"><i class="far fa-envelope"></i> Repeat email<span style="color:red">*</span></div>
            <input id="rpt-email" type="text"placeholder="Repeat email" name="rpt-email" required >
            <div class="rptEmails"><i class="fas fa-check-circle not-valid"></i></div>
            <div class="email-not-match"></div>
          </div>
          <div class="input-box">
            <div class="details"><i class="fas fa-key"></i> Password<span style="color:red">*</span></div>
            <input id="password" type="password" placeholder="Enter User Password" name="password" required ><i class="fas fa-check-circle not-valid"></i>
            <div class="Checkpassword">
            <i class="fas fa-check-circle not-valid"></i>
            </div>
            <div class="text-danger password-error">
              Password must be more than 4 characters!
            </div>
          </div>
          <div class="input-box">
            <div class="details"><i class="fas fa-key"></i>Repeat Password<span style="color:red">*</span></div>
            <input id="rpt-password" type="password" placeholder="Repeat User Password" name="rpt-password" required ><i class="fas fa-check-circle not-valid"></i>
            <div class="rptcheckpassword"><i class="fas fa-check-circle not-valid"></i></div>
            <div class="password-not-match"></div>
          </div>
          <div class="required">
            <span style="color:red">* Required Field </span>
          </div>
          <div class="button">
            <input id="button" disabled  type="submit" name="Register" value="Register">
          </div>
        </div>
      </form>
    </section>
  </div>
  <footer>
    <p> <i class="fas fa-copyright"></i> CollegeLink 2021</p>
  </footer>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link href="assets/css/small_monitor.css" type="text/css" rel="stylesheet"/>
  <link href="assets/css/tablet.css" type="text/css" rel="stylesheet"/>
  <link href="assets/css/mobile.css" type="text/css" rel="stylesheet"/>
  <link href="assets/css/fontawsome.min.css" rel="stylesheet" >
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 </body>
</html>