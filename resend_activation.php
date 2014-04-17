<?php

require_once("inc/secure.php");

if (empty($_POST['email']) || empty($_POST['password']) )
  login_error();
else
  {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // First verify user credentials.
    try
      {
	$authentic = do_authentication($email, $password);
      }
    catch (InvalidLoginException $ex)
      {
        // TODO: Redirect to login page with email field filled out.
        echo $ex->getMessage();
      }

    if ($authentic)
      {
	// Now get the activation token.
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
		echo "Activation e-mail sent again!";
	      }
	  }
      }
    else
      {
	// TODO: This is a bad error, it means the form was submitted wrong or we have a hacker.
	log_warn("resend_activation.php reached without proper credentials. Possible security breach.");
	login_error();
      }
  }
?>