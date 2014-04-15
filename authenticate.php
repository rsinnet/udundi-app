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
  
  $password = $_POST['password'];
  if ($row = $result->fetch_array()) {
      $hash = $row['password'];
      if (password_verify($POST_['password'], $row['password']))
      {
  // Make sure the id is not a duplicate. This is unlikely. Also store in database.
          while (!add_session_to_database($con, session_id()))
              session_regenerate_id();
  //redirect_to_home();
          
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
      echo "$password<br>$hash<br>" . password_verify($password, $hash);;
    ?>
  </body>
</html>
