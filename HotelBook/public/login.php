<?php
  require_once __DIR__.'\..\boot\boot.php';

  use Hotel\User;

  // Check for existing user
  if(!empty(User::getCurrentUserId()))
  {
   header('Location: ..\public\index.php');
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
  </head>
  <body>
    <header>
      <div class="primary-menu text-right">
        <p class="main-logo">Hotels</p>
        <div class="nav">
          <label class="togle" for="toggle">&#9776;</label>
          <input type="checkbox" id="toggle"/>
            <div class="menu">
              <ul>
                <li><a href="index.php" ><i class="fas fa-home"></i>Home</a></li>
            </div>
        </div>
      </div>
    </header>
    <div class="body-register flex-box">
      <section class="container-register-form text-center">
        <div class="title">LogIn</div>
          <form class="loginForm" id="loginForm" name="loginForm" action="actions/login.php" method="post">
            <div class="user-details flex-box">
              <div class="input-box">
                <div class="details"><i class="fas fa-envelope"></i> Email</div>
                  <input id="email" type="text"placeholder="Enter email" name="email" required>
                </div>
                <div class="input-box">
              <div class="details"><i class="fas fa-key"></i> Password</div>
                <input id="password" type="password" placeholder="Enter User Password" name="password" required>
              </div>
              <div class="button">
                <input id="button" type="submit" name="Register" value="Log In">
              </div>
            </div>
            <?php if (isset($_GET['error']) == true) {?>
              <div class="error-message">
                <p align="center" color = "red" > Email or Password is not valid</p>
              </div>
            <?php } ?>
          </form>
        </section>
    </div>
    <footer>
      <p> <i class="fas fa-copyright"></i> CollegeLink 2021</p>
    </footer>
    <link href="assets/css/small_monitor.css" type="text/css" rel="stylesheet"/>
    <link href="assets/css/tablet.css" type="text/css" rel="stylesheet"/>
    <link href="assets/css/mobile.css" type="text/css" rel="stylesheet"/>
    <link href="assets/css/fontawsome.min.css" rel="stylesheet" >
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  </body>
</html>