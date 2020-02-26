<?php

/*
** Listing Page
**
** This page is the main page where union
** workers can bid on jobs they are interested
** in transitioning to.
**/

/*
** Eligibility Requirements
**
** (1) Must have worked for Mercury for at least one year.
** (2) Must have not changed job within the last 6 months.
*/

// Eligibility: [edate] is the user's join date and [next_job_change] is the date the user is eligible for a job change.
$edate = strtotime($user['eligibility']);
$cdate = (empty($user['next_job_change'])) ? strtotime(date('Y-m-d')) : strtotime($user['next_job_change']);

// Time Variables: Creates UNIX time code for today's date and the date one year ago today.
$today = strtotime(date('Y-m-d'));
$one_year = strtotime(date('Y-m-d') . ' -1 year');

$decision = ($edate <= $one_year) ? (($cdate <= $today) ? true : false) : false; // Decides whether user is eligible to bid

// Error text: If we got false, meaning we activated one of the eligibility issues.
$error_text = ($edate > $one_year) ? "You must have worked for Brunswick Mercury for at least a year to change jobs." : "";
$error_text = (($cdate > $today) && empty($error_text)) ? "You changed your job within the last four months and thus are not eligible." : $error_text;
$error_style = ($error_text) ? "style=\"display: block;\"" : "";

// $_GET isset BID: Maybe not the most secure way to do this.
if(isset($_GET['bid'])) {

  // Job Info
  $job = DB::query("SELECT top 1 * FROM jobs WHERE jid='" . $_GET['bid'] . "'")->fetch();

  // Insert into the database
  if($decision) {
    // Double check that the user is passsing all restrictions.

    if(DB::query("SELECT * FROM bids WHERE eid = '{$user['eid']}' AND jid = '{$job['jid']}'")->fetchColumn() == 0) {
      // Make sure the user has not already bid on the job.

      $rand = rand(2313,2310233231);
      $sql = "INSERT INTO bids (eid, worker_name, bid_job_name, jid, bid_department, senior_date, preference, bid) VALUES (:bid, :eid, :worker_name, :job_name, :jid, :department, :bdate, '0')";
      $query = DB::prepare($sql);
      $query->execute(['eid' => $user['eid'],
                       'worker_name' => $user['name'],
                       'job_name' => $job['title'],
                       'jid' => $job['jid'],
                       'department' => $user['dept'],
                       'bdate' => $user['eligibility'],
                       'bid' => NULL]);
      $error_text = "Successfully bid on job.";
      $error_style = "style=\"display: block; background: #149414;\"";
    }
  }

}

?>
<div class="col-12"><a href="/profile"><div class="user_profile button">user profile</div></a><a href="/listing"><div class="job_listing button active">job listing</div></a><a href="/logout"><div class="log_out button">log out</div></a></div>
<div class="col-12">
  <div class="main_block">

    <!-- search bar -->
    <div class="col-12">

      <div class="section search">
        <div class="search_helper_text">type in any keyword to search for jobs</div>
        <div class="col-12">
          <input onkeyup="searchItems()" type="text" class="search_bar" name="search_bar" id="search_bar" />
        </div>
      </div>

      <div class="error_block" <?=$error_style?>>
        <?=$error_text?>
      </div>

      <div class="section results">
        <div class="results_heading">Results</div>
        <div class="col-9">
          <div class="search_results">
          <ul class="results_list" id="results_list">
            <?php

              /*
              ** List all jobs currently available.
              **
              ** HR would like all jobs listed whether or not
              ** the user is eligible for it.
              */
              $sql = "SELECT * FROM jobs";
              foreach(DB::query($sql) as $row) {

                // Prepare variables: [bid] dictates whether or not the user has bid on the current job and [payrate] just takes the imploded pay rates and makes them an array.
                $bid = true;
                $payrate = explode(",",$row['compensation']);

                // Eligible text
                $eligible = ($decision) ? "Eligible" : "Ineligible";
                $eligible_style = ($decision) ? "" : "in";

                // Checks whether or not the user has bid on the job already: also sets text if the user has already bid on the job.
                if(DB::query("SELECT COUNT(*) FROM bids WHERE eid = '{$user['eid']}' AND jid = '{$row['jid']}'")->fetchColumn() > 0) {
                  $eligible = "Applied";
                  $eligible_style = "bid";
                  $bid = false;
                }

                // If the user is ineligible or already bid on the job, then disable the apply button.
                $disable_button = ($decision && $bid) ? "" : "disabled";
                $button_text = ($decision && $bid) ? "<a href=\"?bid={$row['jid']}\">Apply</a>" : (($bid === false) ? "<a>Applied</a>" : "<a>In-eligible</a>");

                echo "

                  <li class=\"results_item\">
                    <div job=\"" . $row['jid'] ."\" class=\"no-select results_item title_card\">
                      <span id=\"job_title\">" . $row['title'] . "</span>
                      <div class=\"hide_on_mobile $eligible_style eligible\">" . $eligible . "</div>
                      <div class=\"arrow_right\">
                        <i class=\"fas fa-chevron-left\"></i>
                      </div>
                    </div>
                    <div job=\"" . $row['jid'] . "\" class=\"results_item_content\">
                      <!-- <div job=\"" . $row['jid'] . "\" $error_style class=\"error_block\">
                        $error_text
                      </div> -->
                      <div job=\"" . $row['jid'] . "\" class=\"results_item department\">
                        <strong>Department</strong>: " . $row['dept'] . "<br /><strong>Plant</strong>: " . $row['plant'] . "<br /><strong>Shift</strong>: " . $row['shift'] . "<br /><strong>Vacancies</strong>: " . $row['vacancies'] . "
                      </div>
                      <div job=\"" . $row['jid'] . "\" class=\"results_item department\">
                        <strong>Additional Information</strong>:<br />" . $row['additional_comments'] . "
                      </div>
                      <div job=\"" . $row['jid'] . "\" class=\"results_item work_schedule\">
                        <strong>Pay Rate</strong>:
                        <div class=\"results_table\">
                          <table>
                            <tr>
                              <td>0-26 weeks</td>
                              <td>27-52 weeks</td>
                              <td>53-78 weeks</td>
                              <td>79-104 weeks</td>
                              <td>105-130 weeks</td>
                              <td>131+ weeks</td>
                            </tr>
                            <tr>
                              <td>{$payrate[0]}</td>
                              <td>{$payrate[1]}</td>
                              <td>{$payrate[2]}</td>
                              <td>{$payrate[3]}</td>
                              <td>{$payrate[4]}</td>
                              <td>{$payrate[5]}</td>
                            </tr>
                          </table>
                        </div>
                      </div>
                      <div job=\"" . $row['jid'] . "\" class=\"$disable_button apply_button\">
                          $button_text
                      </div>
                    </div>
                  </li>

                ";

              }

            ?>
          </ul>
        </div>
        </div>
        <div class="col-3">
          <div class="filter_block">
            <strong>Information</strong>
            <p>Expand each job listing to see more information about that job. If you are eligible, you will be able to apply to that position. Once you have applied to all jobs you are eligible for, visit the user profile page in the top-left section to order each position by preference. You must set preferences for each position you apply to by Tuesday at 9am, otherwise the preferences will be set for you.
          </div>
        </div>
      </div>

    </div>


  </div>
</div>


<script>
function searchItems(){var e,t,n,s;for(e=document.getElementById("search_bar").value.toUpperCase(),t=document.getElementById("results_list").getElementsByTagName("li"),s=0;s<t.length;s++)(n=t[s].getElementsByTagName("span")[0])&&((n.textContent||n.innerText).toUpperCase().indexOf(e)>-1?t[s].style.display="":t[s].style.display="none")}
</script>
<script src="/assets/js/listing.js"></script>
