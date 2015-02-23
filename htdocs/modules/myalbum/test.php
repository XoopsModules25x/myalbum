<?php
$global_perms = 0;
for ($n=0; $n<270; $n++) {
    echo $global_perms;
    if ($GLOBALS['global_perms'] & 1) {
        echo 'sa marche<br />';
    } elseif ($GLOBALS['global_perms'] & 256) {
        echo 'plan 2<br />';
    } else {
        echo 'plan 3<br />';
    }
    $global_perms++;
}
