<?php
function udundi_sql_connect()
{
    $dbuser = 'rsinnet_webuser';
    $dbpass = 'Z?Z07uwL#(4g';
    $con = mysqli_connect("localhost", $dbuser, $dbpass, "rsinnet_udundi");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect: " . mysqli_connect_error();
        // redirect to error page.
    }
    return $con;
}


function redirect_to_home()
{
    header('Location: http://dev.iamphilosopher.com/index.php');
}

function redirect_to_login()
{
    $public_space = array("login.php", "register.php", "error404.php", "error500.php");
    if (!in_array(basename($_SERVER['REQUEST_URI']), $public_space))
        header('Location: http://dev.iamphilosopher.com/login.php');
}

function login_error()
{
    header('Location: http://dev.iamphilosopher.com/error404.php');
}

function add_session_to_database($con, $session_id)
{
    $user_id = 1;
    $sql_command = "INSERT INTO sessions (id, userid) ".
                   "VALUES (\"" . $session_id . "\", \"" . $user_id."\")";
    return $con->query($sql_command);
}

function conditional_redirect_from_public_area()
{
    $public_space = array("login.php", "register.php", "error404.php", "error500.php");
    if (in_array(basename($_SERVER['REQUEST_URI']), $public_space))
        redirect_to_home();
}

?>
