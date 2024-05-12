<?php 
    @include "config.php";

    session_start();
    if($_POST)
    {
        $usn = $_POST["usn"];
        $password = "";

        if(isset($_POST['hash']))
        {
            $password = $_POST['hash'];
        }
        else
        {
            $password = $_POST['password'];
        }

        $msg = "";
        
        $select = "SELECT * FROM students WHERE student_id = '$usn';";
        $result = mysqli_query($conn, $select);

        if(mysqli_num_rows($result) > 0)
        {
            $user = mysqli_fetch_assoc($result);

            if($password == $user['password'] or password_verify($password, $user['password']))
            {
                $_SESSION['student_id'] = $user['student_id'];
                $msg = "OK";

                echo $msg;
            }
            else
            {
                echo "Invalid Password";
            }
        }
        else
        {
            echo "Invalid username";
        }

        /* $_SESSION["id"] = 1;
        echo "OK"; */
    }
?>