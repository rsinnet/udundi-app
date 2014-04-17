<?php

require_once("inc/utilities.php");
require_once("inc/secure.php");
require_once("inc/exceptions.php");

if (empty($_POST['email']) || empty($_POST['password']) )
  login_error();
else
  {
    $email = $_POST["email"];
    $password = $_POST["password"];

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
        if (account_active($email))
	  {
	    do_login($email);
            redirect_to_home();
	  }
	else
	  {
            // TODO: Redirect to resend e-mail page.
	    // TODO: If we send the password and use POST
	    echo "<html><body>".
	      "<form method=\"POST\" action=\"resend_activation.php\" id=\"credentials\">".
	      "<input type=\"hidden\" name=\"email\" value=\"$email\">".
	      "<input type=\"hidden\" name=\"password\" value=\"$password\">".
	      "</form>".
	      "<p>The account $email has not been activated. Please click ".
	      "<a href=\"javascript: document.getElementById('credentials').submit();\">here</a> ".
	      "to send the activation e-mail again.</p>".
	      "</body></html>";
	    
	    
	    // TODO: Duplicate entry
	    // TODO: Need to deal with disabled accounts as well. Check if inactive and go to resend email page if so.
	  }
      }
    else
      {
	// TODO: Redirect to login and populate email field.
	login_error();
      }
  }


?>
