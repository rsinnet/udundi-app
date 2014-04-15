<?php
  
require_once("lib/password.php");
require_once("inc/utilities.php");
  
// Get a session id.
if (session_id() == '')
    session_start();
  
if (empty($_POST['email']) || empty($_POST['password']) )
    login_error();
  
// Connect to the database.
$con = udundi_sql_connect();
  
// DO AUTHENTICATION HERE!
// Get the hash from the database.
$sql_command = "SELECT password FROM users WHERE email=\"" . $_POST['email'] . "\"";
$result = $con->query($sql_command);
  
if ($row = $result->fetch_array()) {
    $pass_good = password_verify($POST_['password'], $row['password'])
    if ($pass_good)
    {
        // Make sure the id is not a duplicate. This is unlikely. Also store in database.
        while (!add_session_to_database($con, session_id()))
            session_regenerate_id();
        redirect_to_home();
    }
//else
//        login_error();
}
//else
//    login_error();
  
$result->close();
  
?>
<html>
  <body>
    <?php
print $pass_good;
      ?>
  </body>
</html>