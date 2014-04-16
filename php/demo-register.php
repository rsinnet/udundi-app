<html>
<body>
<?php

$debug_mode = true;

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
        if (!execute_query($scon, $sql_command))
            log_warn('Unable to insert user with email `$email` into users_secure table. '.
                     mysqli_errorno($scon) + . " " . mysqli_error($scon));
    // die here
        $scon->close();
        unset($hash);
        
        $sql_command = "INSERT INTO users (email, created) VALUES (\"$email\", NULL)";
        if (!execute_query($con, $sql_command))
            log_warn('Unable to insert user with email `$email` into users table. '.
                     mysqli_errorno($con) + . " " . mysqli_error($con));
        

        // Generate an activation token.
        $token = get_activation_token();
        
        // Add activation code to database with expiration time three days from now.
        $sql_command = "INSERT INTO activations (email, token) VALUES (\"$email\", \"$token\")";
        $con->query($sql_command);
        if (!execute_query($con, $sql_command))
            log_warn('Unable to insert activation token for user `$email`. '.
                     mysqli_errorno($con) + . " " . mysqli_error($con));
        $con->close();        

        // Send activation email to registrant.

        // Going to need a cronjob or something to clean up the database.

        // Display thank you page.
    }
    else
    {
        // Redirect to login page, preferably filling in certain values by passing
        // POST data.
        redirect_to_login();
    }
}
?>
</body>
</html>
