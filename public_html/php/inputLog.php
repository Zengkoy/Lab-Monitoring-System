<?php
    @include 'config.php';

    date_default_timezone_set("Asia/Taipei");

    $usn = $_POST["usn"];
    $password = $_POST["password"];
    $subject = $_POST["subject"];
    $lab = $_POST["lab"];
    $date = date("Y-m-d H:i:s");

    // Get Student
    $query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$usn';");
    $student = mysqli_fetch_assoc($query);

    // Get assigned pc
    $assignment = array();
    $backupAssignment = array();
    $query = mysqli_query($conn, "SELECT * FROM pc_assignment WHERE student_id = '$usn' AND subject = '$subject'
                                                                    AND computer_id RLIKE '^$lab';");
    while($r = mysqli_fetch_assoc($query))
    {
        $assignment[] = $r;
    }

    $query = mysqli_query($conn, "SELECT * FROM backup_assignment WHERE student_id = '$usn' AND subject = '$subject'
                                                                    AND computer_id RLIKE '^$lab';");
    while($r = mysqli_fetch_assoc($query))
    {
        $backupAssignment[] = $r;
    }

    //Check assigned pc availability
    $assignmentAvailable = false;
    $backupAssignAvail = false;
    if(!empty($assignment))
    {
        $assignment = $assignment[0]['computer_id'];
        $query = mysqli_query($conn, "SELECT status FROM computers WHERE computer_id = '$assignment';");
        $status = mysqli_fetch_assoc($query)['status'];
        if($status != "unusable")
        {
            $assignmentAvailable = true;
        }
    }
    if(!empty($backupAssignment))
    {
        $backupAssignment = $backupAssignment[0]['computer_id'];
        $query = mysqli_query($conn, "SELECT status FROM computers WHERE computer_id = '$backupAssignment';");
        $status = mysqli_fetch_assoc($query)['status'];
        if($status != "unusable")
        {
            $backupAssignAvail = true;
        }
    }


    //Get list of available pc
    $query = mysqli_query($conn, "SELECT computer_id FROM computers 
                                WHERE computer_id RLIKE '^$lab' AND status != 'unusable' AND computer_id NOT IN 
                                (SELECT computer_id FROM pc_assignment WHERE subject = '$subject')
                                AND computer_id NOT IN 
                                (SELECT computer_id FROM backup_assignment WHERE subject = '$subject');");
    $available_pc = array();
    while($r = mysqli_fetch_assoc($query))
    {
        $available_pc[] = $r;
    }

    
    if(!empty($student) and $student['password'] == $password)
    {
        if(empty($assignment) and count($available_pc) > 0)
        {
            //if NO Assigned PC
            $assigned_pc = $available_pc[0]['computer_id'];
            $query = "INSERT INTO pc_assignment (student_id, subject, computer_id) VALUES ('$usn', '$subject', '$assigned_pc');";

            if(mysqli_query($conn, $query)) 
            { 
                $query = "INSERT INTO logs (computer_id, student_id, subject, date) VALUES ('$assigned_pc', '$usn', '$subject', '$date');";

                if(mysqli_query($conn, $query)) { echo "OK". $assigned_pc; }
                else { echo mysqli_error($conn) . "1"; }
            }
            else { echo mysqli_error($conn) . "0"; }
        }
        else if($assignmentAvailable)
        {
            //if Assigned PC is Functional
            $assigned_pc = $assignment;

            $query = "INSERT INTO logs (computer_id, student_id, subject, date) VALUES ('$assigned_pc', '$usn', '$subject', '$date');";
            if(mysqli_query($conn, $query)) 
            { 
                $query = "DELETE FROM backup_assignment WHERE student_id = '$usn' AND subject = '$subject' AND computer_id RLIKE '^$lab';";
                if(mysqli_query($conn, $query)) { echo "OK". $assigned_pc; }
                else { echo mysqli_error($conn); }
            }
            else { echo mysqli_error($conn); }
        }
        else if($backupAssignAvail)
        {
            //if Assigned Backup PC is Functional
            $assigned_pc = $backupAssignment;

            $query = "INSERT INTO logs (computer_id, student_id, subject, date) VALUES ('$assigned_pc', '$usn', '$subject', '$date');";
            if(mysqli_query($conn, $query)) { echo "OK". $assigned_pc; }
            else { echo mysqli_error($conn); }
        }
        else if(!empty($assignment) and !$assignmentAvailable and (empty($backupAssignment) or !$backupAssignAvail) and !empty($available_pc))
        {
            //if Assigned PC is NOT functional, there is no backup pc or backup pc is broken, and there are extra pc Available
            $assigned_pc = $available_pc[0]['computer_id'];
            $delQuery = "DELETE FROM backup_assignment WHERE student_id = '$usn' AND subject = '$subject' AND computer_id RLIKE '^$lab';";
            $query = "INSERT INTO backup_assignment (student_id, subject, computer_id) VALUES ('$usn', '$subject', '$assigned_pc');";
            if(mysqli_query($conn, $delQuery) and mysqli_query($conn, $query))
            {
                $query = "INSERT INTO logs (computer_id, student_id, subject, date) VALUES ('$assigned_pc', '$usn', '$subject', '$date');";
                if(mysqli_query($conn, $query)) { echo "OK". $assigned_pc; }
                else { echo mysqli_error($conn); }
            }
            else 
            {
                echo mysqli_error($conn);
            }
        }
        else
        {
            echo "No Available PC";
        }
    }
    else
    {
        echo "Student not in Database";
    }
?>