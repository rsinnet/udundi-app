<?php
if (!isset($__INCLUDE_UTILITIES_PHP))
{
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
    
    function add_session_to_database($con, $session_id)
    {
        $sql_command = "INSERT INTO session (id, userid) ".
                       "VALUES (\"" . $session_id . "\", \"" . $user_id."\")";
        return $con->query($sql_command);
    }

    function redirect_to_login()
    {
        if (basename($_SERVER['REQUEST_URI']) != "login.php")
            header('Location: http://dev.iamphilosopher.com/login.php');
    }

    function redirect_to_home()
    {
        header('Location: http://dev.iamphilosopher.com/index.php');
    }
}
$__INCLUDE_UTILITIES_PHP = 1;
?>
