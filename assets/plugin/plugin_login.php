<?php

  /*
   * Login Parse
   * Date: 05/22/2019
   * Reference: Maxwell Newberry (i-Jet)
   */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
