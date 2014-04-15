<?php
function udundi_secure_sql_connect()
{
    $dbuser = 'rsinnet_authuser';
    $dbpass = 'p{}]~H7+em<yBtC';
    $con = mysqli_connect("localhost", $dbuser, $dbpass, "rsinnet_udundi_secure");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect: " . mysqli_connect_error();
        // redirect to error page.
    }
    return $con;
}

?>