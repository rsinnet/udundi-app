<?php

require_once("inc/utilities.php");

// Get a session id.
if (session_id() == '')
    session_start();

// Connect to the database.
$con = udundi_sql_connect();

// DO AUTHENTICATION HERE!

// Make sure the id is not a duplicate. This is unlikely. Also store in database.
//while (!add_session_to_database($con, session_id()))
//    session_regenerate_id();

//redirect_to_home();

?>
<html>
  <body>
    <?php
      echo session_id();
      echo "<br>"
 add_session_to_database($con, session_id());
       ?>
  </body>
</html>
