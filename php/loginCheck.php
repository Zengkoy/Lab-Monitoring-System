<?php
    @include "config.php";

    if(!isset($_SESSION['id'])) {
                      
        if(isset($_COOKIE['user_str_session'])) {
            $user_string = $_COOKIE['user_str_session'] ;
                  
            $query = "SELECT username FROM login_log WHERE string = '$user_string';";
            $result = mysqli_query($conn, $query);
            
            if(mysqli_num_rows($result) > 0){

                $row = mysqli_fetch_array($result);

                $_SESSION['id'] = $row['username'];
                echo "OK";
            }
            else{echo "no user";}
        }
        else{echo "no user";}
    }
    else {echo "OK";}
?>