<html>
  <body>
  <?php

  require_once("inc/secure.php");

  $con = udundi_sql_connect();
  $sql_command = "SELECT token FROM activations WHERE email=\"$email\"";
  try
  {
    $sth = execute_query($con, $sql_command);
  }
  catch (PDOException $ex)
  {
    log_warn("Unable to SELECT token from activations for `$email$`. ".
             $ex->getMessage());
  }

  if ($row = $sth->fetch(PDO::FETCH_ASSOC))
    { 
      // Get activation token and resend message.
      if ($row['token'])
	{
	  // TODO: Might consider authenticating the user before sending him an e-mail...
	  send_activation_email($email, $token);
	}
    }
  ?>
  </body>
</html>