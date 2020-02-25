<?php

require '../f/db.func.php';
session_start();

if(isset($_GET['sid'])) {
  $s = DB::prepare("UPDATE login_validate SET validated=1 WHERE session=:session");
  $s->execute(['session' => $_GET['sid']]);
  $uid = DB::query("SELECT uid FROM login_validate WHERE session='" . $_GET['sid'] . "' LIMIT 1")->fetch()['uid'];
  $_SESSION['login'] = $uid;
  echo "Done, please close window.";
}

?>
