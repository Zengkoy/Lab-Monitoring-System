<?php
    @include 'config.php';

    $usn = $_POST["usn"];
    $password = $_POST["password"];
    $subject = $_POST["subject"];
    $lab = $_POST["lab"];
    $date = date("Y-m-d H:i:s");

    $query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$usn';");
    $student = mysqli_fetch_assoc($query);

    $query = mysqli_query($conn, "SELECT * FROM pc_assignment WHERE student_id = '$usn' AND subject = '$subject'
                                                                    AND computer_id RLIKE '^$lab';");
    $assignment = array();

    while($r = mysqli_fetch_assoc($query))
    {
        $assignment[] = $r;
    }

    $query = mysqli_query($conn, "SELECT computer_id FROM computers 
                                WHERE computer_id RLIKE '^$lab' AND status != 'unusable' AND computer_id NOT IN 
                                (SELECT computer_id FROM pc_assignment WHERE subject = '$subject');");
    $available_pc = array();

    while($r = mysqli_fetch_assoc($query))
    {
        $available_pc[] = $r;
    }

    $assigned_pc = $available_pc[0]['computer_id'];
    if(!empty($student) and $student['password'] == $password)
    {
        if(empty($assignment))
        {
            $query = "INSERT INTO pc_assignment (student_id, subject, computer_id) VALUES ('$usn', '$subject', '$assigned_pc');";

            if(mysqli_query($conn, $query)) 
            { 
                $query = "INSERT INTO logs (computer_id, student_id, date) VALUES ('$assigned_pc', '$usn', '$date');";

                if(mysqli_query($conn, $query)) { echo "OK". $assigned_pc; }
                else { echo mysqli_error($conn) . "1"; }
            }
            else { echo mysqli_error($conn) . "0"; }
        }
        else
        {
            $assigned_pc = $assignment[0]['computer_id'];
            $query = "INSERT INTO logs (computer_id, student_id, date) VALUES ('$assigned_pc', '$usn', '$date');";

            if(mysqli_query($conn, $query)) { echo "OK". $assigned_pc; }
            else { echo mysqli_error($conn); }
        }
    }
    else
    {
        echo "Student not in Database";
    }
?>