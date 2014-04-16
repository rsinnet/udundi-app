<?php

require_once("lib/password.php");
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
        {d_login($email);
            redirect_to_home();
         }
         else
         {
            // TODO: Redirect to resend e-mail page.
// TODO: Duplicate entry
// TODO: Need to deal with disabled accounts as well. Check if inactive and go to resend email page if so.
         }

             echo "Account has not been activated. Click <a href="
         }
     }
}

?>
