<?php
require_once('inc/utilities.php');

$con = udundi_sql_connect();

$token = $_GET["token"];

$sql_command = "SELECT email FROM activations WHERE token=\"$token\"";
$result = execute_query($con, $sql_command);

if ($row = $result->fetch_array())
{
    $email = $row['email'];
    // Enable and activate the account.
    $sql_command = "UPDATE users SET active=TRUE, enabled=TRUE WHERE email=\"$email\"";
    if (!execute_query($con, $sql_command))
    {
        // TODO: Error Handling
        log_error("Unable to activate and enable user `$email` in users table. ".
                  mysqli_errno($scon) . " " . mysqli_error($scon));

        // TODO: How many records were updated? Should be one.
        die();
    }

    // Remove the activation nonce from the database.
    $sql_command = "DELETE FROM activations WHERE token=\"$token\"";
    if (!execute_query($con, $sql_command))
    {
        // TODO: Error Handling
        log_error("Unable to delete nonce from activations table. ".
                  mysqli_errno($scon) . " " . mysqli_error($scon));

        // Technically the account has been activated so this is not the end of
        // the world, but it's bad because we should never be in this state.
    }
}
else
{
// TODO: Wrong activation token or none present, need a custom error page. Log back in to regenerate 
// activation token.
    die();
}

$result->close();
$con->close();

echo "<html>".
"  <body>".
"    Account <b>$email</b> activated. Please <a href=\"login.php\">login</a>.".
"  </body>".
"</html>";

?>
