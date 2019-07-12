<div class="col-12"><a href="http://142.93.254.242/hr"><div class="user_profile button active">job list</div></a><a href="http://142.93.254.242/addjob"><div class="job_listing button">add job</div></a><a href="http://142.93.254.242/updateworkers"><div class="job_listing button">update workers</div></a></div>
<div class="col-12">
  <div class="main_block">

    <!-- search bar -->
    <div class="col-12">

      <div class="section welcome">
        <div class="heading">
          <strong>[HR]</strong>: Welcome, <strong><?=$user['name']?></strong>.
        </div>
      </div>

      <div class="section results">
        <div class="col-12 job_list_line">
        <div class="results_heading">Job List</div>
        <a href="http://142.93.254.242/addjob"><div class="add_job button active">Add Job</div></a>
      </div>
        <div class="col-12">
          <div class="search_results">
          <ul class="results_list">

            <?php

              $sql = "SELECT * FROM `jobs`";
              foreach(DB::query($sql) as $row) {
                // Iterate through each job listing

                // Get the number of bids for this specific job listing.
                $num_rows = DB::query("SELECT COUNT(*) FROM `bids` WHERE `jid` = '{$row['jid']}'")->fetchColumn();

                echo "

                <li class=\"results_item\">
                  <div job=\"test\" class=\"results_item title_card\">
                    " . $row['title'] . "
                    <div class=\"bid_count\">
                      <label>Bid Count:</label> " . $num_rows . "
                    </div>
                  </div>
                </li>

                ";

              }

            ?>
          </ul>
        </div>
        </div>
      </div>

    </div>


  </div>
</div>
