<?php
  @include 'config.php';

/* function url(){
  return sprintf(
    "%s://%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME']
  );
} */

if($_POST)
{
    $username = trim(stripslashes($_POST['username']));
    $email = trim(stripslashes($_POST['email']));
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $password = trim(stripslashes($_POST['password']));
    $hash = password_hash($password, PASSWORD_DEFAULT);

    if(!$email)
    {
      echo "Invalid Email";
    }
    else
    {
      $select = "SELECT * FROM admin WHERE username = '$username';";
      $result = mysqli_query($conn, $select);

      if((mysqli_num_rows($result) > 0))
      {
        echo "Username already exists.";
      }
      else
      {
        $select = "SELECT * FROM admin WHERE email = '$email';";
        $result = mysqli_query($conn, $select);

        if(mysqli_num_rows($result) > 0)
        {
          echo "Email already in use";
        }
        else
        {
          $query = "INSERT INTO admin(username, password, email) VALUES('$username', '$hash', '$email');";
          if (mysqli_query($conn, $query)) { echo "OK"; }
          else { echo mysqli_error($conn); }
        }
      }
    }
    
}

?>