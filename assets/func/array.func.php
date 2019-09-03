<?php

function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
                break;
            case SORT_DESC:
                arsort($sortable_array);
                break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function array_reset($arr) {
    foreach($arr as $key => $val ) {
        $tmp_array = array();
        foreach($arr[$key] as $value) {
            $tmp_array[] = $value;
        }
        $winner[$key] = $tmp_array;
    }
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

?>