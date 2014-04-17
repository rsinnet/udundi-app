<?php

require_once("inc/utilities.php");
require_once("inc/exceptions.php");

// Get a session id.
if (session_id() == '')
  session_start();

// Connect to the database.
$con = udundi_sql_connect();

// Delete session if in database.
try
{
  $st = execute_query($con, "DELETE FROM sessions WHERE id=\"" . session_id() . "\"");
}
catch (PDOException $ex)
{
  // TODO: Error Handling
  // Can't think of any problems here. If the session id is not in the database, no big deal, redirect to login.
}

  redirect_to_login();

?>
