<?php

// time php tests.php

$arr = array();
for ($i = 0; $i < 500000; $i++) {
    $arr[$i] = $i;
}

$tmp = array();
foreach ($arr as $i) {
    if ($i % 2 == 0) {
        $is_exists = array_key_exists($i, $arr);
        if ($is_exists) {
            array_push($tmp, $i);
        }
    }
}