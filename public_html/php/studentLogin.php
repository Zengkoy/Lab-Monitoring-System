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
    $query = mysqli_query($conn, "SELECT COUNT(*) as total_count FROM student_login_logs WHERE try_time > $time AND ip_address = '$ip_address';");
    $check_login_row = mysqli_fetch_assoc($query);
    $total_count = $check_login_row['total_count'];

    if($_POST and $total_count < 3)
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
        
        $select = "SELECT * FROM students WHERE student_id = '$usn';";
        $result = mysqli_query($conn, $select);

        if(mysqli_num_rows($result) > 0)
        {
            $user = mysqli_fetch_assoc($result);

            if($password == $user['password'] or password_verify($password, $user['password']))
            {
                $delete = "DELETE FROM student_login_logs WHERE ip_address = '$ip_address';";

                if(mysqli_query($conn, $delete))
                {
                    $_SESSION['student_id'] = $user['student_id'];
                    echo "OK"; 
                }
                else
                {
                    echo mysqli_error($conn);
                }
            }
            else
            {
                $insert = "INSERT INTO student_login_logs (ip_address, try_time) VALUES('$ip_address', '$try_time');";
                if(mysqli_query($conn, $insert))
                {
                    echo "Invalid Password";
                }
                else { echo mysqli_error($conn); }
            }
        }
        else
        {
            $insert = "INSERT INTO student_login_logs (ip_address, try_time) VALUES('$ip_address', '$try_time');";
                if(mysqli_query($conn, $insert))
                {
                    echo "Invalid USN";
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