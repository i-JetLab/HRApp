<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require './assets/func/db.func.php'; // Import database file

if($_SESSION['page'] !== "index" && !isset($_SESSION['login'])) {
  header('Location: /');
}

if(isset($_SESSION['login'])) {
  // User info
  $user = DB::query("SELECT * FROM users WHERE clock_num=" . $_SESSION['login'])->fetch();
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>HR Bid | Brunswick</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">

    <!-- style -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel='icon' href='./assets/img/favicon.ico' type='image/x-icon'/ >
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="./assets/js/global.js"></script>
  </head>
  <body ontouchstart="" id="<?=$_SESSION['page']?>">

    <div class="preloader" id="pre-loader"></div>

    <div class="wrapper">
      <?php

        require __DIR__ . '/' . $_SESSION['page'] . '.php';

      ?>
    </div>

  </body>
</html>
