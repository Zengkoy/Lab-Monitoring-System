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
    $password = trim(stripslashes($_POST['password']));
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $select = "SELECT * FROM admin WHERE username = '$username';";
    $result = mysqli_query($conn, $select);

    if((mysqli_num_rows($result) > 0))
    {
        echo "Username already exists.";
    }
    else
    {
        $query = "INSERT INTO admin(username, password) VALUES('$username', '$hash');";
        if (mysqli_query($conn, $query)) { echo "OK"; }
        else { echo mysqli_error($conn); }
    }
}

?>