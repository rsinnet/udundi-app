<?php
$debug_mode = true;
$log_dir = "/home4/rsinnet/udundi/logs/";

function do_log($msg, $type)
{
    global $log_dir;
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $today = date("Y-m-d H:i:s");
    $lmsg = "$today $type ".$caller['file'].":".$caller['line'].": $msg\n";

    error_log($lmsg, 3, $log_dir.$type);
    error_log($lmsg, 3, $log_dir."ALL");
}

function log_notice($msg)
{
    do_log($msg, "NOTICE");
}

function log_warn($msg)
{
    do_log($msg, "WARN");
}


function log_error($msg)
{
    do_log($msg, "ERROR");
}
?>
