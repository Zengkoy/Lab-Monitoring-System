<?php 
    @include "config.php";

    session_start();
    date_default_timezone_set("Asia/Taipei");

    function getIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ipAddr=$_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ipAddr=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ipAddr=$_SERVER['REMOTE_ADDR'];
        }
        return $ipAddr;
        
    }
    $try_time = time();
    $time = $try_time - 60;
    $ip_address = getIpAddr();

    //Count number of attempts
    $query = mysqli_query($conn, "SELECT COUNT(*) as total_count FROM login_log WHERE try_time > $time AND ip_address = '$ip_address';");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];

    //if less than 3 attempts
    if($_POST and $total_count < 3)
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $cookie_value = "";
        
        $select = "SELECT * FROM admin WHERE username = '$username';";
        $result = mysqli_query($conn, $select);

        if(mysqli_num_rows($result) > 0)
        {
            $user = mysqli_fetch_assoc($result);

            if(password_verify($password, $user['password']))
            {
                //setting cookie
                if(isset($_POST["rememberMe"]))
                {
                    $login_string = hash('sha512', $username . $_SERVER['HTTP_USER_AGENT'] . time());
                    $cookie_name  = 'user_str_session';  
                    $cookie_value = $login_string;
                    setcookie($cookie_name, $cookie_value, time() + (86400 * 1), "/");
                }

                $delete = "DELETE FROM login_log WHERE ip_address = '$ip_address';";
                $insert = "INSERT INTO login_log (username, string, try_time) VALUES('$username', '$cookie_value', '$try_time');";

                if(mysqli_query($conn, $delete))
                {
                    if(mysqli_query($conn, $insert)){ 
                        $_SESSION['id'] = $user['admin_id'];
                        echo "OK"; 
                    }
                    else
                    {
                        echo mysqli_error($conn);
                    }
                }
                else
                {
                    echo mysqli_error($conn);
                }
            }
            else
            {
                $insert = "INSERT INTO login_log (ip_address, try_time) VALUES('$ip_address', '$try_time');";
                if(mysqli_query($conn, $insert))
                {
                    echo "Invalid Password";
                }
                else { echo mysqli_error($conn); }
            }
        }
        else
        {
            $insert = "INSERT INTO login_log (ip_address, try_time) VALUES ('$ip_address', '$try_time');";
            if(mysqli_query($conn, $insert))
            {
                echo "Invalid username";
            }
            else { echo mysqli_error($conn); }
        }

        /* $_SESSION["id"] = 1;
        echo "OK"; */
    }
    else
    {
        echo "Too many attempts. Timeout 60 seconds.";
    }
?>