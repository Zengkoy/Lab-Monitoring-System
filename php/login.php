<?php 
    @include "config.php";

    session_start();
    if($_POST)
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $date = date("Y-m-d");
        $cookie_value = "";

        $msg = "";
        
        $select = "SELECT * FROM admin WHERE username = '$username' && password = '$password';";
        $result = mysqli_query($conn, $select);

        if(mysqli_num_rows($result) > 0)
        {
            if(isset($_POST["rememberMe"]))
            {
                $login_string = hash('sha512', $username . $_SERVER['HTTP_USER_AGENT'] . time());
                $cookie_name  = 'user_str_session';  
                $cookie_value = $login_string;
                setcookie($cookie_name, $cookie_value, time() + (86400 * 1), "/");
            }

            $insert = "INSERT INTO login_log VALUES('$username', '$cookie_value', '$date');";

            if(mysqli_query($conn, $insert)){
                $row = mysqli_fetch_array($result);
                $_SESSION['id'] = $row['admin_id'];
                $msg = "OK";
            }
            else{
                $msg = mysqli_error($conn);
            }
            
            echo $msg;
        }
        else
        {
            echo "Incorrect username or password";
        }

        /* $_SESSION["id"] = 1;
        echo "OK"; */
    }
?>