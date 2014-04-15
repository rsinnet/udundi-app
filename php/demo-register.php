<html>
  <body>
    <?php
echo "hey";
      if (isset($_POST['username']) && isset($_POST['password']) )
      {
          echo $password;
          echo "<br>";
          require_once('../lib/password.php');
          echo password_hash($_POST['password'], PASSWORD_BCRYPT);
      }
    ?>
  </body>
</html>