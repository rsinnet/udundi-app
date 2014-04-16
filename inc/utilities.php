<?php
require_once('logging.php');

$GLOBALS['public_space'] = array("login.php", "register.php", "error404.php", "error500.php", "demo-register.php");

function udundi_connect($dbname, $dbuser, $dbpass)
{
    try
    {
        $con = new PDO("mysql:host=localhost;dbname=$dbname", $dbuser, $dbpass);
    }
    catch (PDOException $ex)
    {
        log_error("Failed to connect: [" . $ex->getCode() . "] " . $ex->getMessage());
        // redirect to error page.
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
        log_error("Failed to connect: " . $ex->getMessage());       
        throw($ex); // rethrow
    }

    try
    {
        $st->execute();
    }
    catch (PDOException $ex)
    {
        log_warn("Failed to execute query: [" . $ex->getCode() . "] " . $ex->getMessage());
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

function add_session_to_database($con, $session_id, $email)
{
    $sql_command = "INSERT INTO sessions (id, email) ".
                   "VALUES (\"$session_id\", \"$email\")";
    try
    {
        $st = execute_query($con, $sql_command);
    }
    catch (PDOException $ex) {
// TODO: If duplicate entry, no big deal, just return false.
// TODO: Other problems should write to the error log.
        log_warn("Couldn't add session to database: {$ex->getMessage()}");
        return false;
    }

    return true
}

function conditional_redirect_from_public_area()
{
    if (in_array(basename($_SERVER['REQUEST_URI']), $GLOBALS['public_space']))
        redirect_to_home();
}

function account_active($email)
{
// Connect to the database.
    $con = udundi_sql_connect();

    $sql_command = "SELECT active FROM users WHERE email=\"$email\"";
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
                    log_notice("User $email is active.");
            return true;
        }
    }

    if ($GLOBALS["debug_mode"])
        log_notice("User $email is inactive.");
    return false;
}

function do_login($email)
{
// TODO: Maximum number of retries.
    $con = udundi_sql_connect();
    // Make sure the id is not a duplicate. This is unlikely. Also store in database.
    while (!add_session_to_database($con, session_id(), $email))
    {
        session_regenerate_id();
    }
}

?>
