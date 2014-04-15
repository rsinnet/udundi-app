<?php

require_once("inc/utilities.php");

// Session management
// Authentication is done elsewhere.

// Get a session id.
if (session_id() == '')
    session_start();

// Connect to the database.
$con = udundi_sql_connect();

// If they have a session, see if it's still in the database.
$result = $con->query("SELECT u.email, u.active, u.enabled FROM sessions AS s ".
                      "INNER JOIN users AS u ON u.email = s.email ".
                      "WHERE s.id=\"" . session_id() . "\"");

if ($row = $result->fetch_array())
{
    $udundi_user_email = $row['email'];
    if ($row['active'] && $row['enabled'])
        conditional_redirect_from_public_area();
    else
        login_error();
}
else
    redirect_to_login();

$result->close();


require_once("lib/config.php");

?>