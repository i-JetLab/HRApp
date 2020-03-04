<?php

/*
** Add Job Processor
**
** This will take a POST request (technically) and
** process the inputs. If everything checks out given
** the two error checks (empty fields & rate of pay...
** ... is numeric) then they job listing will be updated
** with the new job.
*/


if(isset($_POST['submit'])) {
  // Form has been posted.
  
  if(strlen($_POST['rforpost_textarea']) < 256) {

    if($_POST['job_title'] !== "" && $_POST['plant'] !== "" && $_POST['shift'] !== "" && $_POST['department'] !== "" && $_POST['vacancies'] !== "" && $_POST['rforpost_textarea'] !== "" && $_POST['rate_of_pay1'] !== "" && $_POST['rate_of_pay2'] !== ""
    && $_POST['rate_of_pay3'] !== "" && $_POST['rate_of_pay4'] !== "" && $_POST['rate_of_pay5'] !== "" && $_POST['rate_of_pay6'] !== "" && $_POST['rate_of_pay7'] !== "") {
      // All inputs have been given.

      if(is_numeric($_POST['rate_of_pay1']) && is_numeric($_POST['rate_of_pay2']) && is_numeric($_POST['rate_of_pay3']) && is_numeric($_POST['rate_of_pay4']) && is_numeric($_POST['rate_of_pay5']) && is_numeric($_POST['rate_of_pay6']) && is_numeric($_POST['rate_of_pay7']))
      {
        // Rate of pay is all numeric.

        // Set variables
        $r_o_p = implode(",", array($_POST['rate_of_pay1'], $_POST['rate_of_pay2'], $_POST['rate_of_pay3'], $_POST['rate_of_pay4'], $_POST['rate_of_pay5'], $_POST['rate_of_pay6'], $_POST['rate_of_pay7']));
        $jid = "max-" . rand(32423,92373);

        // Parse additional comments
        $addit_comments = addslashes($_POST['addit_textarea']); // fully parsed comment section
        $rforpost = addslashes($_POST['rforpost_textarea']);

        // All inputs have passed error checks and so we will insert the job posting to the database
        $_query_text = "INSERT INTO jobs VALUES (:jid, :title, :dept, :plant, :shift, :compensation, :vacancies, :additional_comments, :rforpost, :removedate)";
        $_sql = DB::prepare($_query_text);
        $_sql->execute(['jid' => $jid, 'title' => $_POST['job_title'], 'dept' => $_POST['department'], 'plant' => $_POST['plant'], 'shift' => $_POST['shift'], 'compensation' => $r_o_p, 'vacancies' => $_POST['vacancies'], 'additional_comments' => $addit_comments, 'rforpost' => $rforpost, 'removedate' => $removedate]);

        // Reset $_POST variable so items do not show in form
        $_POST = "";

        // Set error_block to be success_block
        $error_text = "Successfully added job to the database.";
        $error_style = "style=\"display:block; background: #7DEC70;\"";
      }
      else {
        // Rate of pay is not all numeric.
        $error_style = "style=\"display:block;\"";
        $error_text = "Please check your rate of pay or vacancies and make sure they are numbers (not symbols except periods or letters).";
      }

    }

    else {
      // Missing fields.
      $error_style = "style=\"display:block;\"";
      $error_text = "Please check to make sure all your fields are filled in and re-submit.";
    }
    
  } else {
    // Longer than 255 characters.
    $error_style = "style=\"display:block;\"";
    $error_text = "Please check your additional comments or reason for posting, they must be shorter than 255 characters.";
  }

}

?>

<div class="col-12"><a href="/hr"><div class="job_list button">job list</div></a>
                    <a href="/addjob"><div class="add_job_button button active">add job</div></a>
                    <a href="/updateworkers"><div class="update_workers button">update workers</div></a></div>
<div class="col-12">
  <div class="main_block">

    <!-- search bar -->
    <div class="col-12">

      <div class="section welcome">
        <div class="heading">
          Add a job
        </div>
      </div>

      <div class="section results">
        <div class="col-8 main_content_add_job">
          <div class="error_block" <?=$error_style?>>
            <?=$error_text?>
          </div>
          <form method="post">
            <div class="form_section">
              <div class="label">Job Title</div>
              <select name="job_title" id="job_title">
                <option value=""></option>
                <?php
                  // Select all job listing items
                  $sql = "SELECT * FROM jobs_list";
                  foreach(DB::query($sql) as $row) {
                    if(isset($_POST['job_title'])) {
                      if($_POST['job_title']==$row['job']) {
                        // The form has been submitted and the previously selected item is this one.
                        echo "<option value=\"{$row['job']}\" selected>{$row['job']}</option>";
                      }
                      else {
                        // The form has been submitted and the previously select item is not this one.
                        echo "<option value=\"{$row['job']}\">{$row['job']}</option>";
                      }
                    }
                    else {
                      // No form submitted, display all listings as normal.
                      echo "<option value=\"{$row['job']}\">{$row['job']}</option>";
                    }
                  }
                ?>
              </select>
            </div>
            <div class="form_section">
              <div class="label">Plant</div>
              <select name="plant" id="plant">
                <option value=""></option>
                <?php
                  // Select all plant listing items
                  $sql = "SELECT * FROM plants_list";
                  foreach(DB::query($sql) as $row) {
                    if(isset($_POST['plant'])) {
                      if($_POST['plant']==$row['plant']) {
                        // The form has been submitted and the previously selected item is this one.
                        echo "<option value=\"{$row['plant']}\" selected>{$row['plant']}</option>";
                      }
                      else {
                        // The form has been submitted and the previously select item is not this one.
                        echo "<option value=\"{$row['plant']}\">{$row['plant']}</option>";
                      }
                    }
                    else {
                      // No form submitted, display all listings as normal.
                      echo "<option value=\"{$row['plant']}\">{$row['plant']}</option>";
                    }
                  }
                ?>
              </select>
            </div>
            <div class="form_section">
              <div class="label">Shift</div>
              <select name="shift" id="shift">
                <option value=""></option>
                <?php
                  // Select all shift listing items
                  $sql = "SELECT * FROM shifts_list";
                  foreach(DB::query($sql) as $row) {
                    if(isset($_POST['shifts'])) {
                      if($_POST['shift']==$row['shift']) {
                        // The form has been submitted and the previously selected item is this one.
                        echo "<option value=\"{$row['shift']}\" selected>{$row['shift']}</option>";
                      }
                      else {
                        // The form has been submitted and the previously select item is not this one.
                        echo "<option value=\"{$row['shift']}\">{$row['shift']}</option>";
                      }
                    }
                    else {
                      // No form submitted, display all listings as normal.
                      echo "<option value=\"{$row['shift']}\">{$row['shift']}</option>";
                    }
                  }
                ?>
              </select>
            </div>
            <div class="form_section">
              <div class="label">Department</div>
              <select name="department" id="department">
                <option value=""></option>
                <?php
                  // Select all department listing items
                  $sql = "SELECT * FROM departments_list";
                  foreach(DB::query($sql) as $row) {
                    if(isset($_POST['department'])) {
                      if($_POST['department']==$row['department']) {
                        // The form has been submitted and the previously selected item is this one.
                        echo "<option value=\"{$row['department']}\" selected>{$row['department']}</option>";
                      }
                      else {
                        // The form has been submitted and the previously select item is not this one.
                        echo "<option value=\"{$row['department']}\">{$row['department']}</option>";
                      }
                    }
                    else {
                      // No form submitted, display all listings as normal.
                      echo "<option value=\"{$row['department']}\">{$row['department']}</option>";
                    }
                  }
                ?>
              </select>
            </div>
            <div class="form_section">
              <div class="label">Vacancies</div>
              <input class="input" type="text" value="<?php if(isset($_POST['vacancies'])) { echo $_POST['vacancies']; }?>" name="vacancies" id="vacancies" />
            </div>
            <div class="form_section">
              <div class="label">Additional Information</div>
              <textarea class="input addit_textarea" id="addit_textarea" name="addit_textarea" placeholder="(max characters: 255)"><?php if(isset($_POST['addit_textarea'])) { echo $_POST['addit_textarea']; } ?></textarea>
            </div>
            <div class="form_section">
              <div class="label">Reason for Posting</div>
              <textarea class="input rforpost_textarea" id="rforpost_textarea" name="rforpost_textarea" placeholder="(max characters: 255)"><?php if(isset($_POST['rforpost_textarea'])) { echo $_POST['rforpost_textarea']; } ?></textarea>
            </div>
            <div class="form_section">
              <div class="label">Removal Date</div>
              <input class="input" type="text" placeholder="MM/DD/YYYY" value="<?php if(isset($_POST['removedate'])) { echo $_POST['removedate']; }?>" name="removedate" id="removedate" />
            </div>
            <div class="form_section">
              <div class="label rop">Rate of Pay</div>
              <div class="rate_of_pay_section">
                <div class="rate_of_pay_item">
                  <div class="rate_of_pay_label">
                    0-26 weeks
                  </div>
                  <input class="input" type="text" value="<?php if(isset($_POST['rate_of_pay1'])) { echo $_POST['rate_of_pay1']; } ?>" name="rate_of_pay1" id="rate_of_pay1" />
                </div>
                <div class="rate_of_pay_item">
                  <div class="rate_of_pay_label">
                    27-52 weeks
                  </div>
                  <input class="input" type="text" value="<?php if(isset($_POST['rate_of_pay2'])) { echo $_POST['rate_of_pay2']; } ?>" name="rate_of_pay2" id="rate_of_pay2" />
                </div>
                <div class="rate_of_pay_item">
                  <div class="rate_of_pay_label">
                    53-78 weeks
                  </div>
                  <input class="input" type="text" value="<?php if(isset($_POST['rate_of_pay3'])) { echo $_POST['rate_of_pay3']; } ?>" name="rate_of_pay3" id="rate_of_pay3" />
                </div>
                <div class="rate_of_pay_item">
                  <div class="rate_of_pay_label">
                    79-104 weeks
                  </div>
                  <input class="input" type="text" value="<?php if(isset($_POST['rate_of_pay4'])) { echo $_POST['rate_of_pay4']; } ?>" name="rate_of_pay4" id="rate_of_pay4" />
                </div>
                <div class="rate_of_pay_item">
                  <div class="rate_of_pay_label">
                    105-130 weeks
                  </div>
                  <input class="input" type="text" value="<?php if(isset($_POST['rate_of_pay5'])) { echo $_POST['rate_of_pay5']; } ?>" name="rate_of_pay5" id="rate_of_pay5" />
                </div>
                <div class="rate_of_pay_item">
                  <div class="rate_of_pay_label">
                    131+ weeks
                  </div>
                  <input class="input" type="text" value="<?php if(isset($_POST['rate_of_pay6'])) { echo $_POST['rate_of_pay6']; } ?>" name="rate_of_pay6" id="rate_of_pay6" />
                </div>
                <div class="rate_of_pay_item">
                  <div class="rate_of_pay_label">
                    Before 2009
                  </div>
                  <input class="input" type="text" value="<?php if(isset($_POST['rate_of_pay7'])) { echo $_POST['rate_of_pay7']; } ?>" name="rate_of_pay7" id="rate_of_pay7" />
                </div>
              </div>
            </div>
            <!-- <div class="form_section">
              <div class="ui checkbox">
                <input value="1" type="checkbox" name="jwc" />
                <label>This job requires a Journey Worker's Card or 8 Years of Experience</label>
              </div>
            </div>
            <div class="form_section">
              <div class="ui checkbox">
                <input value="1" type="checkbox" name="cdl" />
                <label>This job requires a CDL to apply</label>
              </div>
            </div> -->
            <div class="form_section">
              <input class="input" type="submit" name="submit" id="submit" value="Add Job" />
            </div>
          </form>
        </div>
        </div>
      </div>

    </div>


  </div>
</div>
