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
        do_authentication($_POST["email"], $_POST["password"]);
    }
    catch (UdundiException $ex)
    {
        if ($ex->getCode() == 1)
        {
// TODO: Goto resend activation e-mail.
            echo "Goto resend activation e-mail.";
        }
        elseif ($ex->getCode() == 2)
        {
            echo "Invalid login credentials."
        }
    }
}


?>
