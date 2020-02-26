<?php

/*
** Profile page
**
** This page is the listing for all jobs the
** user has bid on and they can remove, or
** set the preference for their bids.
*/

if(isset($_POST['submit'])) {

  /*
   ** Form has been submitted and we need to update the user's preferences of
   ** job choices. This will perform several checks first.
   */

   // Iterate through each input and check for repeats or missing preferences.
   $repeat = false; $missing_preference = false;
   for($x = 0; $x < $_POST['itt']; $x++) {
     for($i = 0; $i < $_POST['itt']; $i++) {
       if(($_POST[$i] == $_POST[$x]) && $i !== $x) {
         $repeat = true;
       }
     }
     if($_POST[$x] == 0) {
       $missing_preference = true;
     }
   }

   // No errors found, update the preferences.
   $itt = 0;
   if(!$repeat && !$missing_preference) {
     $select = "SELECT * FROM bids WHERE eid = '" . $user['eid'] . "'";
     foreach(DB::query($select) as $row) {
       $update_statement = "UPDATE bids SET preference = '" . $_POST[$itt] . "' WHERE jid = '" . $row['jid'] . "' AND eid = '" . $user['eid'] . "'";
       DB::query($update_statement);
       $itt++;
     }
     $error_text = "Succesfully updated bids.";
     $error_style = ($error_text) ? "style=\"display:block; background: #149414;\"" : "";
   }
   else
   {
     // Errors were found.
     $error_text = ($missing_preference) /* Preferences are missing. */ ? (($repeat) /* Missing preferences AND repeat preferences */ ? "You are missing preferences and have repeat preferences." : /* ONLY Missing preferences */ "You have missing preferences, please check your form.") : /* No Missing preferences */ (($repeat) ? /* ONLY repeat preferences */ "You have repeat preferences in your form." : /* No errors, something weird happened. */ "");
     $error_style = ($error_text) /* Make sure error exists. */ ? "style=\"display:block;\"" : "";
   }
}
if(isset($_GET['remove'])) {

    // Check to see if url being accessed includes ?remove=XXXXX
    $sql = "SELECT * FROM bids WHERE bid = '" . $_GET['remove'] . "'";
    if(DB::query($sql)->fetch()[0] > 0) {
        // Bid exists
        $remove_bid = "DELETE from bids WHERE bid = '" . $_GET['remove'] . "'";
        DB::query($remove_bid);
    }

    // Redirect user
    //header("Location: /profile");
}

?>
<div class="col-12"><a href="/profile"><div class="user_profile button active">user profile</div></a><a href="/listing"><div class="job_listing button">job listing</div></a><a href="/logout"><div class="log_out button">log out</div></a></div>
<div class="col-12">
  <div class="main_block">

    <!-- search bar -->
    <div class="col-12">

      <div class="section welcome">
        <div class="heading">
          Welcome, <strong><?=$user['name']?></strong>.
        </div>
      </div>

      <div class="section results">
        <div class="results_heading">Results</div>
        <div class="col-8 profile">
          <div class="description">
            You must adjust your preferences before Tuesday, 9am otherwise the system will automatically select your preferences for you. The preference rating spans from one (1) to however many jobs you've bid on with one (1) being the most preferred. <span class="show_on_mobile"><strong>You must be on a desktop to update your preferences.</strong></span>
          </div>
          <div class="error_block" <?=$error_style?>>
            <?=$error_text?>
          </div>
          <div class="search_results">
            <form method="post" name="105">
              <ul class="results_list">
                <li class="results_item">
                  <div class="table_head"><div class="heading">Preference</div></div>
                </li>
                <?php
                  /*
                  ** List all bids.
                  **
                  ** Goes through each bid and list the job so the user can set preferences.
                  */

                  // SQL Query
                  $sql = "SELECT * FROM bids WHERE eid = :eid";
                  $query = DB::prepare($sql);
                  $query->execute(['eid' => $user['eid']]);
                  $bid_count = DB::query("SELECT COUNT(*) FROM bids WHERE eid = " . $user['eid'])->fetchColumn();
                  $itt = 0;

                  // Using the query, display each bid given the information.
                  while($row = $query->fetch()) {

                    echo "
                    <li class=\"results_item\">
                      <div job=\"{$row['jid']}\" class=\"results_item title_card\">
                          {$row['bid_job_name']} <em><strong><a href=\"?remove={$row['bid']}\" style=\"text-decoration: none; color: red;\">(remove)</a></strong></em>
                        <div class=\"hide_on_mobile preference\">
                          <input type=\"number\" min=\"1\" max=\"{$bid_count}\" class=\"preference_type\" name=\"{$itt}\" value=\"{$row['preference']}\" />
                        </div>
                        <div class=\"hide_on_mobile decision applied\">
                          Applied
                        </div>
                      </div>
                    </li>
                    ";

                    $itt++;

                  }

                  // Empty Bids: If the user has not yet bid on any jobs, then display this.
                  if(DB::query("SELECT COUNT(*) FROM bids WHERE eid = " . $user['eid'])->fetchColumn() == 0) {
                    echo "
                    <li class=\"results_item\">
                      <div job=\"\" class=\"results_item title_card\">
                        You have no currently bid on any jobs.
                      </div>
                    </li>
                    ";
                  }

                  echo "<li style=\"display:none\"><input type=\"hidden\" value=\"{$itt}\" name=\"itt\" /></li>";

                ?>
                <li class="results_item save">
                  <input type="submit" name="submit" value="Save" />
                </li>
              </ul>
            </form>
        </div>
        </div>
      </div>

    </div>


  </div>
</div>
