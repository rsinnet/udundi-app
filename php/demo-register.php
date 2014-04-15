<html>
<body>
<?php

require_once('../inc/utilities.php');
require_once('../inc/secure.php');

if (isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['confirmPassword']) &&
    isset($_POST['terms']))
{
    $email = $_POST['email'];

    if ($_POST['terms'] &&
        ($_POST['password'] == $_POST['confirmPassword']))
    {

        // Connect to both databases.
        $con = udundi_sql_connect();
        $scon = udundi_secure_sql_connect();

        // Hash the password with bcrypt.
        require_once('../lib/password.php');
        $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);


        // Add user to database, but with account not activated.
        $sql_command = "INSERT INTO users_secure (email, password) VALUES (\"$email\", \"$hash\")";
//$scon->query($sql_command);
//$scon->close();
//unset($hash);
        echo $sql_command;
        echo "<br>";
        
        $sql_command = "INSERT INTO users (email) VALUES (\"$email\")";
        echo $sql_command;
        echo "<br>";

        // Generate an activation token.
        $token = get_activation_token();
        echo $token;
        echo "<br>";

        // Send activation email to registrant.

        // Add activation code to database with expiration time three days from now.

        // Going to need a cronjob or something to clean up the database.

        // Display thank you page.
        echo $password;
        echo "<br>";
        echo $token;
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
