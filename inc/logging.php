<?php
$debug_mode = true;

function log($msg, $type)
{
    $bt = debug_backtrace();
    $caller = array_shift($bt);
    $today = date("[Y-m-d H:i:s] ") 

    error_log($today.$caller['file'].":".$caller['line']." ($type) $msg", 3, '/home4/rsinnet/udundi/logs/$type');
    error_log($today.$caller['file'].":".$caller['line']." ($type) $msg", 3, '/home4/rsinnet/udundi/logs/ALL');
}

function log_notice($msg)
{
    log($msg, "NOTICE");
}

function log_warn($msg)
{
    log($msg, "WARN");
}


function log_error($msg)
{
    log($msg, "ERROR");
}
?>
