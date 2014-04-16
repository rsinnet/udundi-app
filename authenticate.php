<?php

require_once("lib/password.php");
require_once("inc/utilities.php");
require_once("inc/secure.php");
require_once("inc/exceptions.php");

if (empty($_POST['email']) || empty($_POST['password']) )
    login_error();
else
{
    try {
        $authentic = do_authentication($_POST["email"], $_POST["password"]);
    }
    catch (InvalidLoginException $ex)
    {
// TODO: Redirect to login page with email field filled out.
        echo $ex->getMessage();
    }

    if ($authentic)
    {
        try {
            account_active($_POST["email"]);
        }
        catch (InactiveAccountException $ex)
        {
// TODO: Redirect to resend e-mail page.
            echo $ex->getMessage();
        }
    }
}


?>
