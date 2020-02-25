<?php

require '../func/db.func.php';

DB::query("TRUNCATE users");

$h = fopen("bids.csv", "r");
$ignore_path = 0;

while (($data = fgetcsv($h, 0, ",")) !== FALSE)
{
  if($ignore_path > 3) {
    /* User Information
     *
     *  ORDER (CSV): Worker Name [0], Username [1], Clock Number [2], Employee ID [3], Seniority Date (Eligibility) [4], Department [5], Job Title [6], Last Job Change [7], Shift [8], Plant [9]
     *  ORDER (SQL): Employee ID, Clock Number, Worker Name, Username, Department, Plant, Shift, Job Title, Eligibility, Last Job change
     *
     */

     // Department
     $d = ($data[5] == "MAINTENANCE/TOOL ROOM") ? "Maintenance/Tool Room" : (($data[5] == "FURNANCE/METAL MELT AND HAUL") ? "Furnance/Metal and Haul" : ucfirst(strtolower($data[5])));

     // Insert into user database
     try {
       $sql = DB::prepare("INSERT INTO users VALUES (:e, :c, :w, :u, :d, :p, :h, :j, :s, :l)");
       $sql->execute(['e' => $data[3], 'c' => $data[2], 'w' => $data[0], 'u' => $data[1], 'd' => $d, 'p' => $data[9], 'h' => $data[8], 'j' => $data[6],
       's' => $data[4], 'l' => $data[7]]);
     } catch (PDOException $e) {
         //Do your error handling here
         var_dump($e->getMessage());
     }

  }

  $ignore_path++;
}

fclose($h);


?>
