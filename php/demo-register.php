<?php

require_once('../inc/utilities.php');
require_once('../inc/secure.php');

if (isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['terms']))
{
    $email = $_POST['email'];
    
    if ($_POST['terms'])
    {

        $duplicate = false;
        $unhandled_exception = false;

	// Connect to both databases.
        $con = udundi_sql_connect();
        $scon = udundi_secure_sql_connect();
        
	// Hash the password with bcrypt.
        require_once('../lib/password.php');
        $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        
	// Add user to database, but with account not activated.
        try
        {
            $sql_command = "INSERT INTO users_secure (email, password) VALUES (\"$email\", \"$hash\")";
            log_notice($sql_command);
            execute_query($scon, $sql_command);
            log_notice('woa');
        }
        catch (PDOException $ex)
        {
            log_warn("Unable to insert user with email `$email` into users_secure table. {$ex->getMessage()}");
            // TODO: Need to deal with disabled accounts as well. Check if inactive and
            // go to resend email page if so.

            if ($ex->getCode() == 1062)
                $duplicate = true;
            else
                $unhandled_exception = true;
        }

        // Try to add the user to the database.

        if (!$duplicate && !$unhandled_exception)
        {
            try
            {
                $sql_command = "INSERT INTO users (email, created) VALUES (\"$email\", NULL)";
                execute_query($con, $sql_command);
            } catch (PDOException $ex) {
                log_warn("Unable to insert user with email `$email` into users_secure table. {$ex->getMessage()}");
                
                log_error("It seems user `$email` tried to register and his e-mail address already ".
                          "existed in the users_secure table but not in the users table.");

                if ($ex->getCode() == 1062)
                    $duplicate = true;
                else
                    $unhandled_exception = true;
            }
        }

        if (!$duplicate && !$unhandled_exception)
        {
            // Generate an activation token.
            $token = get_activation_token();
        
            // Add activation code to database with expiration time three days from now.
            $sql_command = "INSERT INTO activations (email, token) VALUES (\"$email\", \"$token\")";
            
            try
            {
                execute_query($con, $sql_command);
            }
            catch (PDOException $ex)
            {
                log_warn("Unable to insert activation token for user `$email` {$ex->getMessage()}");

                if ($ex->getCode() == 1062)
                    $duplicate = true;
                else
                    $unhandled_exception = true;
            }
        }
        
        // TODO: Link to pretty, templated HTML e-mail.
        if (!$duplicate && !$unhandled_exception)
        {
            send_activation_email($email, $token);
            // Going to need a cronjob or something to clean up the database.
            // Display thank you page.
            echo "<html><body>Thank you for registering. An e-mail message has been sent to $email with instructions for activating your account.</body></html>";
        }
    }
    else
    {
        // User did not accept the terms.
        // TODO: Redirect to login page, preferably filling in certain values by passing POST data.
        redirect_to_login();
    }
}
else
{
    // TODO: Not all required entries were present, this should not really happen with POST, but go to the register page with the user's e-mail if it is present.
    redirect_to_registration($_POST['email']);
}

?>
