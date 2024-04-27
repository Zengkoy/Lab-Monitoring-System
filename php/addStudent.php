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
    $usn = trim(stripslashes($_POST['usn']));
    $name = trim(stripslashes($_POST['name']));
    $course = trim(stripslashes($_POST['course']));
    $password = trim(stripslashes($_POST['password']));
    $qrCode = "<qr-code id='qr-code' contents='$usn' module-color='#C50000' position-ring-color='#C50000' position-center-color='#C50000' class='container-fluid w-100'>
    <img src='../../images/aclc-logo.png' slot='icon' class='w-100'>
</qr-code>";

    $select = "SELECT * FROM students WHERE student_id = '$usn';";
    $result = mysqli_query($conn, $select);

    if((mysqli_num_rows($result) > 0))
    {
        echo "USN already Registered.";
    }
    else
    {
        $query = "INSERT INTO students VALUES('$usn', '$password', '$name', '$course');";
        if (mysqli_query($conn, $query)) 
        { 
            echo "OK" . $qrCode;
        }
        else { echo mysqli_error($conn); }
    }
}

?>