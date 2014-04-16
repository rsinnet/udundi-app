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
try {
    $st = execute_query($con, "SELECT u.email, u.active, u.enabled FROM sessions AS s ".
                        "INNER JOIN users AS u ON u.email = s.email ".
                        "WHERE s.id=\"" . session_id() . "\"");
} catch (PDOException $ex) {
    // TODO: Error Handling
    log_error("Unable to SELECT from sessions table while trying to query for user and session info. ".
              $ex->getMessage());
}

if ($row = $st->fetch(PDO::FETCH_ASSOC))
{
    $udundi_user_email = $row['email'];
    if ($row['active'] && $row['enabled'])
        conditional_redirect_from_public_area();
    else
        login_error();
}
else
    redirect_to_login();

require_once("lib/config.php");

?>