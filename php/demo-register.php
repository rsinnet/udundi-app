<html>
  <body>
    <?php
      if ( isset($_POST['submit']) && isset($_POST['username']) && isset($_POST['password']) )
      {
          require_once('lib/password.php');
          echo password_hash($_POST['password'], PASSWORD_BCRYPT);
      }
    ?>
  </body>
</html>