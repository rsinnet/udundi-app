<?php

require_once("lib/password.php");
require_once("inc/utilities.php");
require_once("inc/secure.php");

function do_login($email)
{
    $con = udundi_sql_connect();
    // Make sure the id is not a duplicate. This is unlikely. Also store in database.
    while (!add_session_to_database($con, session_id(), $email))
        session_regenerate_id();
    redirect_to_home();
}

// Get a session id.
if (session_id() == '')
    session_start();

if (empty($_POST['email']) || empty($_POST['password']) )
    login_error();

// Connect to the database.
$scon = udundi_secure_sql_connect();

// DO AUTHENTICATION HERE!
// Get the hash from the database.
$sql_command = "SELECT password FROM users_secure WHERE email=\"" . $_POST['email'] . "\"";

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
    if (password_verify($_POST['password'], $row['password']))
        do_login($_POST['email']);
    else
        login_error();
}
else
    login_error();

?>
