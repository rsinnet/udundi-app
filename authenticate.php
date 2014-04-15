<?php

require_once("lib/password.php");
require_once("inc/utilities.php");
require_once("inc/secure.php");


function redirect_to_home()
{
    header('Location: http://dev.iamphilosopher.com/index.php');
}

function do_login()
{
    $con = udundi_sql_connect()
    // Make sure the id is not a duplicate. This is unlikely. Also store in database.
    while (!add_session_to_database($con, session_id()))
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
$result = $scon->query($sql_command);

if ($row = $result->fetch_array()) {
    if (password_verify($_POST['password'], $row['password']))
        do_login();
    else
        login_error();
}
else
    login_error();

$result->close();

?>
