<?php
require_once('inc/utilities.php');

$con = udundi_sql_connect();

$token = $_GET["token"];

$sql_command = "SELECT email FROM activations WHERE token=\"$token\"";
$result = $con->query($sql_command);

if ($row = $result->fetch_array())
{
    // Enable and activate the account.
    $sql_command = "UPDATE users SET active=TRUE, enabled=TRUE WHERE email=\"$email\"";
    $con->query($sql_command);
    echo $sql_command;

    // Remove the activation nonce from the database.
    $sql_command = "DELETE FROM activations WHERE token=\"$token\"";
    $con->query($sql_command);
    echo "<br>";
    echo $sql_command;
    
    echo $row["email"];
    echo "<br>";
}

$result->close();
$con->close();


?>