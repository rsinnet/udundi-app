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
}
$__INCLUDE_UTILITIES_PHP = 1;


// Authentication and session management

// Check users cookie for a persistent session identifier.
$session_id = $_COOKIE['session_id'];

// If they have a session, see if it's in the database still.
$con = udundi_sql_connect();
$result = $con->query("SELECT email FROM users ORDER BY id ASC");

while ($row = $result->fetch_array())
    $udundi_user_email = $row['name'];
$result->close();

// If it is, load up those permissions.

// If it is NOT, clear out the cookie and redirect the user to the login page.

//if (session_id() == '')
//{
//    session_start();

    // Store session ID in database
    
//}


require_once("lib/config.php");

?>