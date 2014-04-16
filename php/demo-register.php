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
// Connect to both databases.
        $con = udundi_sql_connect();
        $scon = udundi_secure_sql_connect();
        
// Hash the password with bcrypt.
        require_once('../lib/password.php');
        $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        
// Add user to database, but with account not activated.
        $sql_command = "INSERT INTO users_secure (email, password) VALUES (\"$email\", \"$hash\")";
        try
        {
            execute_query($scon, $sql_command);
        } catch (PDOException $ex) {
            log_warn("Unable to insert user with email `$email` into users_secure table. ".
                     $ex->getMessage());
// TODO: Duplicate entry
// TODO: Need to deal with disabled accounts as well. Check if inactive and go to resend email page if so.
        }

        $scon->close();
        unset($hash);
        
        $sql_command = "INSERT INTO users (email, created) VALUES (\"$email\", NULL)";
        try
        {
            execute_query($con, $sql_command);
        } catch (PDOException $ex) {
            log_warn("Unable to insert user with email `$email` into users_secure table. ".
                     $ex->getMessage());            
// TODO: Duplicate entry
            log_error("It seems user `$email` tried to register but his e-mail address already existed in the users_secure table but not in the users table.");
// TODO: Major problem, send error through e-mail to sysadmin.
        }
        
// Generate an activation token.
        $token = get_activation_token();
        
// Add activation code to database with expiration time three days from now.
        $sql_command = "INSERT INTO activations (email, token) VALUES (\"$email\", \"$token\")";
        try
        {
            execute_query($con, $sql_command);
        } catch (PDOException $ex) {
            log_warn("Unable to insert activation token for user `$email`. " .
                     $ex->getMessage());
            
// TODO: Duplicate entry. Goto resend activation email page.
        }

        $con->close();
        
// TODO: Need to deal with reactivation if e-mail exists but is not active.
        
// Send activation email to registrant.
// TODO: Link to pretty, templated HTML e-mail.
        mail($email,
             "Activate Your Udundi Analytics Account",
             "Please visit http://dev.iamphilosopher.com/activate.php?token=$token to activate your account. The purpose of this step is to deter malicious users from trying to overload our databases.", "From: support@udundi.com");
        log_notice("An activation e-mail has been sent to $email.");
      
// Going to need a cronjob or something to clean up the database.
        
// Display thank you page.
        echo "<html><body>Thank you for registering. An e-mail message has been sent to $email with instructions for activating your account.</body></html>";
    }
    else
    {
// Redirect to login page, preferably filling in certain values by passing
// POST data.
        redirect_to_login();
    }
}
else
{
// TODO: Not all required entries were present, this should not really happen with POST, but go to the register page with the user's e-mail if it is present.
    redirect_to_registration($_POST['email']);
}

?>
