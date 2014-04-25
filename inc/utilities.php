<?php
require_once('logging.php');
require_once('exceptions.php');

$GLOBALS['public_space'] = array("login.php",
                                 "register.php",
                                 "error404.php",
                                 "error500.php",
                                 "demo-register.php");

function udundi_connect($dbname, $dbuser, $dbpass)
{
    try
    {
        $con = new PDO("mysql:host=localhost;dbname=$dbname", $dbuser, $dbpass);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $ex)
    {
        log_error("Failed to connect: {$ex->getMessage()}");
        // redirect to error page.
        // Need to rethrow
    }
    
    return $con;
}

function udundi_sql_connect()
{
    return udundi_connect("rsinnet_udundi", "rsinnet_webuser",  "Z?Z07uwL#(4g");
}

function execute_query($con, $sql_command)
{
    // Never log this. We don't want hashes written to a log.
    try
    {
        $st = $con->prepare($sql_command);
    }
    catch (PDOException $ex)
    { 
        log_error("Failed to prepare statement: {$ex->getMessage()}");
        throw($ex); // rethrow
    }

    try
    {
        $st->execute();
    }
    catch (PDOException $ex)
    {
        log_warn("Failed to execute query: {$ex->getMessage()}");
        throw($ex); // rethrow
    }
    return $st;
}

function redirect_to_home()
{
    header('Location: http://dev.iamphilosopher.com/index.php');
}

function redirect_to_login()
{
    if (!in_array(basename($_SERVER['REQUEST_URI']), $GLOBALS['public_space']))
        header('Location: http://dev.iamphilosopher.com/login.php');
}

function redirect_to_registration($email="")
{
    header('Location: http://dev.iamphilosopher.com/register.php');
}

function login_error()
{
    // TODO: Login error page.
    $error_page = "error404.php";
    if (!(basename($_SERVER['REQUEST_URI']) == $error_page))
        header("Location: http://dev.iamphilosopher.com/$error_page");
}

function activation_error()
{
    // TODO: Custom error page.
    login_error();
}

function sql_connect_error()
{
    login_error();
}

function add_session_to_database($con, $session_id, $userid)
{
    try
    {
        $sql_command = "INSERT INTO sessions (id, userid) VALUES (\"$session_id\", \"$userid\")";
        $st = execute_query($con, $sql_command);
    }
    catch (PDOException $ex) {
        log_warn("Couldn't add session to database: {$ex->getMessage()}");
        return false;
    }

    return true;
}

function conditional_redirect_from_public_area()
{
    if (in_array(basename($_SERVER['REQUEST_URI']), $GLOBALS['public_space']))
        redirect_to_home();
}

function account_active($userid)
{
    // Connect to the database.
    $con = udundi_sql_connect();

    $sql_command = "SELECT active FROM users WHERE id=\"$userid\"";
    try
    {
        $sth = execute_query($con, $sql_command);
    }
    catch (PDOException $ex)
    {
        // TODO: Error handling. Send the user to an error page.
        log_error("Problem executing activation status query: {$ex->getMessage()}");
    }

    if ($row = $sth->fetch(PDO::FETCH_ASSOC))
    { 
        // Check if active
        if ($row['active'])
        {
            if ($GLOBALS["debug_mode"])
                log_notice("User `$userid` is active.");
            return true;
        }
    }

    if ($GLOBALS["debug_mode"])
        log_notice("User `$userid` is inactive.");
    return false;
}

function do_login($userid)
{
    $max_retries = 3;
    $retries_left = $max_retries;

    $con = udundi_sql_connect();

    // Make sure the id is not a duplicate. This is unlikely. Also store in database.
    while (($retries_left-- > 0) && (!add_session_to_database($con, session_id(), $userid)))
    {
        session_regenerate_id();
    }
}

function send_activation_email($email, $token)
{
    mail($email,
         "Activate Your Udundi Analytics Account",
         "Please visit http://dev.iamphilosopher.com/activate.php?token=$token to activate your account. The purpose of this step is to deter malicious users from trying to overload our databases.", "From: support@udundi.com");
    log_notice("An activation e-mail has been sent to $email.");
}

function get_userid_from_email($email)
{
    $con = udundi_sql_connect();

    try
    {
        $sql_command = "SELECT id FROM users WHERE email=\"$email\"";
        $st = execute_query($con, $sql_command);
    }
    catch (PDOException $ex) {
        log_warn("Couldn't get user id from database: {$ex->getMessage()}");
        throw($ex);
    }

    return $userid;
}

?>
