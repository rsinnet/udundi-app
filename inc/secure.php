<?php
// This page is only accessed when writing to the secure database.

require_once('logging.php');

function udundi_secure_sql_connect()
{
    $dbuser = 'rsinnet_authuser';
    $dbpass = 'p{}]~H7+em<yBtC';
    $con = mysqli_connect("localhost", $dbuser, $dbpass, "rsinnet_udundi_secure");
    if (mysqli_connect_errno())
    {
        log_error( "Failed to connect: " . mysqli_connect_error());
        // redirect to error page.
    }
    return $con;
}


// Took the following functions from
//   http://stackoverflow.com/questions/2593807/md5uniqid-makes-sense-for-random-unique-tokens
// This should give the most random tokens possible, probably drawing from /dev/urandom.
function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 0) return $min; // not so random...
    $log = log($range, 2);
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function get_activation_token($length=128){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}

?>