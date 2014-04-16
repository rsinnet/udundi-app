<?php
// This page is only accessed when writing to the secure database.

require_once('logging.php');
require_once('utilities.php');
require_once('exceptions.php');

function udundi_secure_sql_connect()
{
    return udundi_connect("rsinnet_udundi_secure", "rsinnet_authuser", "p{}]~H7+em<yBtC");
}


// Took the following functions from
//   http://stackoverflow.com/questions/2593807/md5uniqid-makes-sense-for-random-unique-tokens
// This should give the most random tokens possible, probably drawing from /dev/urandom.
function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 0) return $min; // not so random...
    $log = log($range, 2);
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do
    {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function get_activation_token($length=128)
{
    $token = "";
    $codeAlphabet  = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet .= "0123456789";
    for ($i = 0; $i < $length; $i++)
    {
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}

function do_login($email)
{
    $con = udundi_sql_connect();
    // Make sure the id is not a duplicate. This is unlikely. Also store in database.
    while (!add_session_to_database($con, session_id(), $email))
        session_regenerate_id();
}

function do_authentication($email, $password)
{

// Get a session id.
    if (session_id() == '')
        session_start();

// Connect to the database.
    $scon = udundi_secure_sql_connect();

// DO AUTHENTICATION HERE!
// Get the hash from the database and compare.
    $sql_command = "SELECT password FROM users_secure WHERE email=\"$email\"";

    try
    {
        $sth = execute_query($scon, $sql_command);
    }
    catch (PDOException $ex)
    {
        log_error("Problem executing authentication query: [" . $ex->getCode() . "] " . $ex->getMessage());
    }

    if ($row = $sth->fetch(PDO::FETCH_ASSOC))
    {
// Verify password against stored hash.
        if (password_verify($password, $row['password']))
        {
            log_notice("Password verified for `$email`.");
            do_login($email);
        }
        else
            throw new InvalidLoginException();
    }
    else
        throw new InvalidLoginException();


    return true;
}


?>