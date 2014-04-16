<?php
require_once('inc/utilities.php');

$con = udundi_sql_connect();

$token = $_GET["token"];

$sql_command = "SELECT email FROM activations WHERE token=\"$token\"";
try
{
    $sth = execute_query($con, $sql_command);
}
catch (PDOException $ex)
{
    log_error("Could not SELECT from activations table: " . $ex->getMessage());
}

if ($row = $sth->fetch(PDO::FETCH_ASSOC))
{
    $email = $row['email'];

    // Enable and activate the account.
    $sql_command = "UPDATE users SET active=TRUE, enabled=TRUE WHERE email=\"$email\"";
    try
    {
        execute_query($con, $sql_command);
    }
    catch (PDOException $ex)
    {
// TODO: Error Handling
        log_error("Unable to activate and enable user `$email` in users table. ".
                  $ex->getMessage());

        // TODO: How many records were updated? Should be one.
        $activation_fail = true;
    }

    // Remove the activation nonce from the database.
    if (!$activation_fail)
    {
        $sql_command = "DELETE FROM activations WHERE token=\"$token\"";

        try
        {
            execute_query($con, $sql_command);
        }
        catch (PDOException $ex)
        {
// TODO: Error Handling
            log_error("Unable to delete nonce from activations table. " . $ex->getMessage());

            // Technically the account has been activated so this is not the end of
            // the world, but it's bad because we should never be in this state.
        }
    }
}
else
{
// TODO: Wrong activation token or none present, need a custom error page. Log back in to regenerate 
// activation token.
    activation_error();
}

echo "<html>".
"  <body>".
"    Account <b>$email</b> activated. Please <a href=\"login.php\">login</a>.".
"  </body>".
"</html>";

?>
