<div class="col-12"><a href="/hr"><div class="user_profile button active">job list</div></a><a href="/addjob"><div class="job_listing button">add job</div></a><a href="/updateworkers"><div class="job_listing button">update workers</div></a></div>
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
        <a href="/addjob"><div class="add_job button active">Add Job</div></a>
      </div>
        <div class="col-12">
          <div class="search_results">
          <ul class="results_list">

            <?php
  
              if(isset($_GET['remove'])) {
                  // Check to see if url being accessed includes ?remove=XXXXX
                  $sql = "SELECT * FROM jobs WHERE jid = '" . $_GET['remove'] . "'";
                  if(DB::query($sql)->fetch()[0]) {
                      // Bid exists
                      $remove_job = "DELETE from jobs WHERE jid = '" . $_GET['remove'] . "'";
                      DB::query($remove_job);
                  }
              }
          
              if(isset($_GET['bid_id']) && isset($_GET['job_id'])) {
                // If both variables are set and are != ""
                $employee_info = DB::query("SELECT * FROM winners WHERE bid = {$_GET['bid_id']}")->fetch();
                $job_info = DB::query("SELECT * FROM jobs WHERE jid = {$_GET['job_id']}")->fetch();
                
                var_dump($employee_info);
              }
          
              //$_query_text = "INSERT INTO confirmed_winners VALUES (:new_department, :new_job_name, :old_department, :old_job_name, :eid)";
              //$_sql = DB::prepare($_query_text);
              //$_sql->execute(['new_department' => $_POST['new_department'], 'new_job_name' => $_POST['new_job_name'], 'old_department' => $_POST['old_department'], 'old_job_name' => $_POST['old_job_name'], 'eid' => $_POST['eid']]);

              // Reset $_POST variable so items do not show in form
              $_POST = "";

              $sql = "SELECT * FROM jobs";
              foreach(DB::query($sql) as $row) {
                // Iterate through each job listing

                // Get the number of bids for this specific job listing.
                $num_rows = DB::query("SELECT COUNT(*) FROM bids WHERE jid = '{$row['jid']}'")->fetchColumn();

                echo "
                <li class=\"results_item\">
                  <div job=\"" . $row['jid'] . "\" class=\"no-select results_item title_card\">
                    " . $row['title'] . " (" . $row['dept'] . ")
                    <div class=\"hide_on_mobile bid_count\">
                      <label>Bid Count:</label> " . $num_rows . "
                    </div>
                    <div class=\"arrow_right\">
                        <i class=\"fas fa-chevron-left\"></i>
                    </div>
                  </div>
                  <div job=\"" . $row['jid'] . "\" class=\"results_item_content\">
                      <div job=\"" . $row['jid'] . "\" class=\"results_item\">
                          <table>
                              <tr><th>Reason for posting:</th><td>{$row['rforpost']}</td></tr>
                              <tr><td><em><a onclick=\"removeJob('{$row['jid']}');\" style=\"text-decoration: none; color: red;\"><span class=\"low_pro button active\" id=\"{$row['jid']}\" style=\"background:red;\">Remove listing</span></a></em></td></tr>
                          </table>
                      </div>
                      <div job=\"" . $row['jid'] . "\" class=\"results_item winners\">";

                    // Add winners or add text that says winners don't exist.
                    $winners_sql = DB::prepare("SELECT count(*) FROM winners WHERE job_assoc = :job");
                    $winners_sql->execute(['job' => $row['jid']]);

                    if($winners_sql->fetchColumn() > 0) {

                        // Winners exist
                        $winners_sql = DB::prepare("SELECT * FROM winners WHERE job_assoc = :job ORDER BY job_order ASC");
                        $winners_sql->execute(['job' => $row['jid']]);
                        $iter = 1;

                        echo "<table class=\"winners_table\">";
                        echo "<tr><th>Worker Name</th><th>Seniority Date</th><th>Worker's Department</th><th>Preference</th></tr>";
                          foreach($winners_sql as $winner) {
                            $tmp_jid = explode($row['jid'], "-");
                            $tmp_jid = $tmp_jid[0] . "%2D" . $tmp_jid[1];
                            echo "<tr><td>$iter. {$winner['worker_name']}</td><td>{$winner['seniority_date']}</td><td>{$winner['worker_dept']}</td><td style='text-align: center !important;'><strong>{$winner['preference']}</strong></td><td><a href=\"?job_id=$jid&bid_id={$winner['bid']}\" class=\"low_pro button active\">Confirm winner</a></td></tr>";
                            $iter++;
                        }
                        echo "</table>";
                    }
                    else
                    {
                        echo "No winners have been selected.";
                    }

                 echo"     </div>
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

<script>
  var removeJob = function(jobId) {
    if (window.confirm("Are you sure you would like to remove this job listing?")) {
        location.href = "?remove=" + jobId;
    }
  };
</script>
<script src="/assets/js/listing.js"></script>
