<?php

date_default_timezone_set('America/New_York');

$logfile = '/srv/web/libcms/backup/bestbets_log.txt';

if (isset($_GET['event'])) {
    $event = $_GET['event'];

    if ($event !== 'bb_serve' and $event !== 'bb_click') {
        $event = null;
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if (strlen($id) > 30) {
        $id = null;
    }

    if (!ctype_alpha($id)) {
        $id = null;
    }
}

if (isset($id) && isset($event)) {
    $bestbet_loginfo = date('Y-m-d H:i:s');
    $bestbet_loginfo .= "\t" . $event;
    $bestbet_loginfo .= "\t" . $id . "\n";

    //echo $bestbet_loginfo;

    file_put_contents($logfile, $bestbet_loginfo, FILE_APPEND | LOCK_EX);
}



?>