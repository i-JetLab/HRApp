<?php

function array_sort($arr, $on, $order=SORT_ASC)
{
    $new_arr = array();
    $sortable_arr = array();

    if (count($arr) > 0) {
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_arr[$k] = $v2;
                    }
                }
            } else {
                $sortable_arr[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_arr);
                break;
            case SORT_DESC:
                arsort($sortable_arr);
                break;
        }

        foreach ($sortable_arr as $k => $v) {
            $new_arr[$k] = $arr[$k];
        }
    }

    return $new_arr;
}

function array_reset(&$arr) {
    foreach($arr as $key => $val ) {
        $tmp_array = array();
        foreach($arr[$key] as $value) {
            $tmp_array[] = $value;
        }
        $arr[$key] = $tmp_array;
    }
}

function array_uni(&$arr) {
    do {
        /*
        ** We have a list of winners. The next steps are to:
        ** (1) Determine if the top winner is a winner for another job.
        ** (2) If they are, is that a higher preference?
        ** (3) If so, remove them entirely from the current job's list.
        */


        foreach($arr as $a => $b) {
            // Iterate through each job listing.
            $winner_of_current_job = $b[0]['bid'];
            foreach($arr as $c => $d) {
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
                        unset($arr[$a][0]);
                        break;
                    }
                    else {
                        unset($arr[$c][0]);
                    }
                }
            }
        }

        array_reset($arr);
        $winners_unique = array_check($arr);

    } while(!$winners_unique);
}

function array_check($arr) {
    $winners_unique = true;
    foreach($arr as $e => $f) {
        $winner_of_current_job = $f[0]['bid'];
        foreach($arr as $g => $h) {
            $winner_of_item_job = $h[0]['bid'];
            if(($f[0]['name'] == $h[0]['name']) && ($f[0]['bid'] !== $h[0]['bid'])) {
                $winners_unique = false;
            }
        }
    }
    return $winners_unique;
}

function set_winner(&$arr)
{
    $tmp_arr = array();
    foreach ($arr as $key => $arr_item) {
        for($x = 0; $x < sizeof($arr_item); $x++) {
            $tmp_arr[$key][] = $arr_item[$x]['name'];
        }
    }
    $arr = $tmp_arr;
}

function winner_print(&$arr) {
    foreach($arr as $key => $winner_arr) {
        $i = 1;
        echo "<strong>" . $key . "</strong>: <br />";
        foreach($winner_arr as $winner_user) {
            echo $i . ". " . $winner_user . " <br />";
            $i++;
        }
        echo "<br /><br />";
    }
}

?>