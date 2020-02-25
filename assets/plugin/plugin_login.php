<?php

  /*
   * Login Parse
   * Date: 05/22/2019
   * Reference: Maxwell Newberry (i-Jet)
   */

    // Service Check [security]
    if (isset($_SERVER['HTTP_ORIGIN'])) {
      $address = 'http://' . $_SERVER['SERVER_NAME'];
      if (strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0) {
        exit(json_encode(['error' => 'Invalid Origin header: ' . $_SERVER['HTTP_ORIGIN']]));
      }
    } else {
      if(!isset($_SERVER['HTTP_REFERER']) || $_SERVER['HTTP_REFERER'] !== "https://hrbid.azurewebsites.net/") {
        exit(json_encode(['error' => 'No Origin header']));
      }
    }

    session_start();

    if(isset($_POST['badge'])) {

      require '../func/db.func.php';

      if(DB::query("SELECT * FROM `users` WHERE `clock_num`= '{$_POST['badge']}'")->fetchColumn() > 0) {
        // exists

        $_SESSION['login'] = $_POST['badge'];
        $user = DB::query("SELECT * FROM `users` WHERE `clock_num`=" . $_SESSION['login'])->fetch();

        echo "<p>You're now logged in. Welcome, {$user['name']}.</p> Click <a href=\"/listing/\">here</a> to continue.";

      }
      else {
        echo "That badge number does not exist.";
      }

    }

?>
