<?php

if(isset($_POST['submit'])) {
  $fileType = strtolower(pathinfo(basename($_FILES["file-upload"]["name"]),PATHINFO_EXTENSION));
  if($fileType == "csv") {
    /*
    ** This code is reliant on the uploaded csv file to be formatted exactly
    ** how it sent. If it is not, then the file upload will fail.
    ** We will sent an error block if the csv is not properly formatted.
    */

    //DB::query("TRUNCATE `users`");

    $h = fopen($_FILES["file-upload"]["tmp_name"], "r");
    $ignore_path = 0;

    while (($data = fgetcsv($h, 0, ",")) !== FALSE)
    {
      if($ignore_path > 3) {
        /*
         ** User Information
         ** ORDER (CSV): Worker Name [0], Username [1], Clock Number [2], Employee ID [3], Seniority Date (Eligibility) [4], Department [5], Job Title [6], Last Job Change [7], Shift [8], Plant [9]
         ** ORDER (SQL): Employee ID, Clock Number, Worker Name, Username, Department, Plant, Shift, Job Title, Eligibility, Last Job change
         */
         // Department
         $d = ($data[5] == "MAINTENANCE/TOOL ROOM") ? "Maintenance/Tool Room" : (($data[5] == "FURNANCE/METAL MELT AND HAUL") ? "Furnance/Metal and Haul" : ucfirst(strtolower($data[5])));

         // Insert into user database
         try {
           // TODO: UNCOMMENT FOR THE REAL WEB APP -- THIS IS COMMENTED OUT SO REAL DATA IS NOT STORED ON DB.
           //$sql = DB::prepare("INSERT INTO `users` VALUES (:e, :c, :w, :u, :d, :p, :h, :j, :s, :l)");
           //$sql->execute(['e' => $data[3], 'c' => $data[2], 'w' => $data[0], 'u' => $data[1], 'd' => $d, 'p' => $data[9], 'h' => $data[8], 'j' => $data[6], 's' => $data[4], 'l' => $data[7]]);

         } catch (PDOException $e) {
             //Do your error handling here
             var_dump($e->getMessage());
         }
      }
      $ignore_path++;
    }
    fclose($h);
  }
  else {
    $error_text = "Please make sure the report you are uploading is in the correct <strong>.csv</strong> format.";
    $error_style = ($error_text) ? "style=\"display: block;\"" : "";
  }
}

?>
<div class="col-12"><a href="http://142.93.254.242/hr"><div class="user_profile button">job list</div></a><a href="http://142.93.254.242/addjob"><div class="job_listing button">add job</div></a><a href="http://142.93.254.242/updateworkers"><div class="job_listing button active">update workers</div></a></div>
<div class="col-12">
  <div class="main_block">

    <!-- search bar -->
    <div class="col-12">

      <div class="section welcome">
        <div class="heading">
          Update Workers
        </div>
      </div>

      <div class="section results">
        <div class="col-8 main_content_update_workers">
          <div class="error_block" <?=$error_style?>>
            <?=$error_text?>
          </div>
          <div class="description_heading">
            Please select the .csv file (please double-check that it is indeed .csv) that you received in your e-mail. Before uploading, also check whether or not the csv includes the four heading lines at the top before the worker information begins.
          </div>
          <form method="post" enctype="multipart/form-data">
            <div class="form_section update_workers_file_upload">
              <label for="file-upload" class="custom-file-upload" id="file-upload-text">
                  <i class="fas fa-cloud-upload-alt"></i> Select Report
              </label>
              <input id="file-upload" name="file-upload" type="file"/>
            </div>
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

<script>
  var fileUpload = $("#file-upload");
  fileUpload.change(function() {
    // File selected
    $('#file-upload-text').html("<i class=\"fas fa-check\"></i> File Selected");
  });
</script>
