<?php
require_once('inc/utilities.php');

$con = udundi_sql_connect();

$token = $_GET["token"];

$sql_command = "SELECT email FROM activations WHERE token=\"$token\"";
$result = $con->query($sql_command);

if ($row = $result->fetch_array())
{
    echo $row["email"];
    echo "<br>";
}

$result->close();
$con->close();


$sql_command = "UPDATE users SET active=TRUE, enabled=TRUE WHERE email=\"$email\"";
echo $sql_command;
?>
