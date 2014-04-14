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

    function redirect_to_login()
    {
        if (!isset($is_login_page))
            header( 'Location: http://www.iamphilosopher.com/udundi/app/login.php');
    }
}
$__INCLUDE_UTILITIES_PHP = 1;


// Session management
// Authentication is done elsewhere.

// Check users cookie for a persistent session identifier.
if (isset($_COOKIE['id']))
{
    // If they have a session, see if it's in the database still.
    $con = udundi_sql_connect();
    $result = $con->query("SELECT u.id, u.email FROM sessions AS s ".
                          "INNER JOIN users AS u ON u.id = s.userid ".
                          "WHERE s.id=\"" . $_COOKIE['id'] . "\"");
    
    if ($row = $result->fetch_array())
    {
        $udundi_user_id = $row['id'];
        $udundi_user_email = $row['email'];

        session_id($_COOKIE['id']);
        session_start();
    }
        else
    {
        // If it is NOT, clear out the cookie and redirect the user to the login page.
        unset($_COOKIE['id']);
        setcookie('id', '', time() - 3600);

        redirect_to_login();
    }
    $result->close();
}
else
{
    // No cookie, need to authenticate. Redirect to login probably.    
    redirect_to_login();
}

//if (session_id() == '')
//{
//    session_start();

    // Store session ID in database
    
//}


require_once("lib/config.php");

?>