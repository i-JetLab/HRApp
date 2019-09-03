<?php

  /*
  ** i-Jet Lab, Algorithm for HR App
  ** Created by Maxwell Newberry
  **
  ** Date: 09/02/2019
  */

  // Security
  if((!isset($_SERVER['PHP_AUTH_USER']) && ($_SERVER['PHP_AUTH_USER'] !== "ijet")) && (!isset($_SERVER['PHP_AUTH_PW']) && ($_SERVER['PHP_AUTH_PW'] !== "lab")))
  {
    /*
    ** The user must submit an authorized user and pass to access this page.
    ** If the request does not provide a user and pass they will prompted for it.
    */
    header("WWW-Authenticate: Basic realm=\"HR App\"");
    header("HTTP/1.0 401 Unauthorized");
  }

  // Includes
  require 'db.func.php';
  require 'array.func.php';

  /* -- Step 1: Organize all bids to have preferences. -- */

  // Check preferences of all bids.
  $sql = "SELECT * FROM `bids`"; $blank_users = array();
  foreach(DB::query($sql) as $row) {
    if($row['preference'] == 0) {
      $blank_users[] = $row['eid'];
    }
  }

  // Update preferences for users who have empty preferences.
  foreach(array_unique($blank_users) as $user) {
    $find_max = (int) DB::query("SELECT MAX(preference) FROM `bids` WHERE `eid` = '$user'")->fetch()[0]; // Finds the highest preference set by user.
    $query = DB::prepare("SELECT * FROM `bids` WHERE `eid` = :user AND `preference` = '0'");
    $query->execute(['user' => $user]);
    while($row = $query->fetch()) { // Go through each bid made by user.
      if($row['preference'] == 0) {
        // Edit only rows that are not set.
        $int = $find_max + 1;
        DB::query("UPDATE `bids` SET `preference`='{$int}' WHERE `eid`='$user' AND `bid`='{$row['bid']}'");
        $find_max++;
      }
    }
  }

  /* -- Step 2: Go through each job and organize list of winners and runner-ups. -- */

  $winners_unique = false; // An iterator variable that will dictate whether each job's winner is unique.
  $winner = array();

  $query = DB::prepare("SELECT * FROM `jobs`");
  $query->execute();

  while($row = $query->fetch()) {
    // For each job.
    $in_dept = array(); $out_dept = array();

    foreach(DB::query("SELECT * FROM `bids` WHERE `jid` = '{$row['jid']}'") as $srow) {
      // For each bid for a specific job.

      if($srow['bid_department'] == $row['dept']) {
        // Bidder is within the job department.
        $in_dept[] = array('bid' => $srow['bid'], 'date' => strtotime($srow['senior_date']), 'name' => $srow['worker_name'], 'preference' => $srow['preference']);
      }
      else {
        // Bidder is outside of the job department.
        $out_dept[] = array('bid' => $srow['bid'], 'date' => strtotime($srow['senior_date']), 'name' => $srow['worker_name'], 'preference' => $srow['preference']);
      }

    }

    // We now need to order both employees in the department (and out) by seniority.
    $in_dept = array_sort($in_dept, 'date', SORT_NUMERIC);
    $out_dept = array_sort($out_dept, 'date', SORT_NUMERIC);
    $winner[$row['jid']] = $in_dept + $out_dept;

  }
  do {
    /*
    ** We have a list of winners. The next steps are to:
    ** (1) Determine if the top winner is a winner for another job.
    ** (2) If they are, is that a higher preference?
    ** (3) If so, remove them entirely from the current job's list.
    */
    foreach($winner as $a => $b) {
      // Iterate through each job listing.
      $winner_of_current_job = $b[0]['bid'];
      foreach($winner as $c => $d) {
        // Check each job to the current job's winner.
        $winner_of_item_job = $d[0]['bid'];
        if(($b[0]['name'] == $d[0]['name']) && ($b[0]['bid'] !== $d[0]['bid'])) {
          /*
          ** EID for Current Item and Current Winner are equal.
          ** And the current job is not equal to the current item job.
          ** Now we need to check preferences and remove the one that is lower preference.
          */
          if($b[0]['preference'] > $d[0]['preference']) {
            // The item winner is of higher preference than the current winner, remove current winner and stop loop.
            unset($winner[$a][0]);
            break;
          }
          else {
            unset($winner[$c][0]);
          }
        }
      }
    }

    array_reset($winner);
    $winners_unique = array_check($winner);

  } while(!$winners_unique);

if($winners_unique) {
    http_response_code(200);
}
else
{
    http_response_code(400);
}

?>
