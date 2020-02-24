<?php

/*
** i-Jet Lab, Algorithm for HR App
** Created by Maxwell Newberry
**
** Date: 09/02/2019
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Page load start
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

// Includes
require 'db.func.php';
require 'array.func.php';

DB::query("TRUNCATE `winners`");

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
      $in_dept[] = array('bid' => $srow['bid'], 'date' => strtotime($srow['senior_date']), 'name' => $srow['worker_name'], 'preference' => $srow['preference'], 'dept' => $srow['bid_department']);
    }
    else {
      // Bidder is outside of the job department.
      $out_dept[] = array('bid' => $srow['bid'], 'date' => strtotime($srow['senior_date']), 'name' => $srow['worker_name'], 'preference' => $srow['preference'], 'dept' => $srow ['bid_department']);
    }

  }

  // We now need to order both employees in the department (and out) by seniority.
  $in_dept = array_sort($in_dept, 'date', SORT_ASC);
  $out_dept = array_sort($out_dept, 'date', SORT_ASC);
  $winner[$row['jid']] = $in_dept + $out_dept;

}
/*
 * UNCOMMENT THIS FOR MORE ACCURATE ALGORITHM.
 */
//array_uni($winner);
//set_winner($winner);

//foreach($winner as $a => $b) {
//  // Iterate through each job listing.
//  for($i = 0; $i < sizeof($b); $i++) {
//    $winner_of_current_job = $b[$i]['bid'];
//    foreach ($winner as $c => $d) {
//      // Check each job to the current job's winner.
//      $winner_of_item_job = $d[$i]['bid'];
//      if (($b[$i]['name'] == $d[$i]['name']) && ($b[$i]['bid'] !== $d[$i]['bid'])) {
//        /*
//        ** EID for Current Item and Current Winner are equal.
//        ** And the current job is not equal to the current item job.
//        ** Now we need to check preferences and remove the one that is lower preference.
//        */
//        if ($b[$i]['preference'] > $d[$i]['preference']) {
//          // The item winner is of higher preference than the current winner, remove current winner and stop loop.
//          $winner[$a][$i]['low_pro'] = true;
//          break;
//        } else {
//          $winner[$c][$i]['low_pro'] = true;
//        }
//      }
//    }
//  }
//}

/* -- Step 3: Remove winners from other jobs, because they can't win other jobs. -- */

/*
 * UNCOMMENT THIS FOR MORE ACCURATE ALGORITHM.
 */
//foreach($winner as $key => $value) {
//  // Go through each job.
//  $iter_winner = $value[0]['name'];
//  foreach($winner as $key_item => $value_item) {
//    // Go through each job and check for $iter_winner in each job.
//    $item_loc = array_search($iter_winner, array_column($value_item, 'name'));
//    if($item_loc && $key !== $key_item) {
//      // The winner is detected in different job, let's remove them.
//      unset($winner[$key_item][$item_loc]);
//    }
//    array_reset($winner);
//  }
//}

array_reset($winner);

foreach($winner as $current_job => $winner_item) {
  // Add winner to database
  // Iterate through each job
  $current_job_id = $current_job;
  $iter = 1;
  foreach($winner_item as $current_winner) {
    // For each winner of current job.
    $seniority_date = date('m/d/Y', $current_winner['date']);
    $sql = DB::prepare("INSERT INTO `winners` VALUES (:job, :bid, :worker_name, :dept, :seniority, :pref, :job_order, :low_pro);");
    $sql->execute(['job' => $current_job_id, 'bid' => $current_winner['bid'], 'worker_name' => $current_winner['name'], 'dept' => $current_winner['dept'], 'seniority' => $seniority_date, 'pref' => $current_winner['preference'], 'job_order' => $iter, 'low_pro' => 0]);
    $iter++;
  }
}

if($winners_unique) {
  http_response_code(200);
}
else
{
  http_response_code(400);
}

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'Page generated in '.$total_time.' seconds.';

?>
