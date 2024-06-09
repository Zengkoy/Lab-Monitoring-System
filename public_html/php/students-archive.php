<?php
    @include 'config.php';

    if(isset($_POST['course']))
    {
        //generate Students Table
        $course = $_POST['course'];
        $search = $_POST['search'];

        $query = "SELECT * FROM students_archive";
        if($course != "" or $search != "") 
        {
            $query .= " WHERE";
            if($course != ""){ $query .= " course = '$course'"; }
            if($course!="" and $search!=""){ $query .= " AND"; }
            if($search != ""){ $query .= " (student_id LIKE '%$search%' OR name LIKE '%$search%')"; }
        }
        $query .= ";";
        $query = mysqli_query($conn, $query);
        $row = array();
        $table = "";

        while($r = mysqli_fetch_assoc($query))
        {
            $row[] = $r;
        }
        
        if(!empty($row))
        {
            foreach($row as $r)
            {
                $usn = $r['student_id'];
                $name = $r['name'];
                $course = $r['course'];
                $email = $r['email'];

                $table .= "<tr>";

                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='text-secondary text-xs font-weight-bold'>$usn</span></td>";
                
                $table .= "<td><span class='text-secondary text-xs font-weight-bold'>$name</span></td>";
                $table .= "<td><span class='text-secondary text-xs font-weight-bold'>$course</span></td>";

                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='text-secondary text-xs font-weight-bold'>$email</span></td>";

                $table .= "<td class='align-middle'>
                        <input data-student='$usn' onClick='restore_student(this)' class='btn btn-success btn-sm font-weight-normal mb-0 px-2' type='button' value='Restore'/>
                        <input data-student='$usn' onClick='remove_student(this)' class='btn btn-danger btn-sm font-weight-normal mb-0 me-3 px-2' type='button' value='Delete'/>
                    </td>";
                
                $table .= "</tr>";
            }
            echo $table;
        }
        else
        {
            echo "No Students Archived.";
        }
    }
    else if(isset($_POST['action']))
    {
        //Button Click on student
        $usn = $_POST['student'];

        if($_POST['action'] == "restore")
        {
            //restore Student to active students database
            $query = mysqli_query($conn, "SELECT * FROM students_archive WHERE student_id = $usn;");
            $student = mysqli_fetch_assoc($query);

            $name = $student['name'];
            $email = $student['email'];
            $course = $student['course'];
            $password = $student['password'];

            $insert = "INSERT INTO students VALUES ('$usn', '$password', '$name', '$course', '$email');";
            if(mysqli_query($conn, $insert))
            {
                $query = "DELETE FROM students_archive WHERE student_id=$usn;";
                if(mysqli_query($conn, $query)){ echo "OK"; }
                else { echo mysqli_error($conn); }
            }
            else
            {
                echo mysqli_error($conn);
            }
        }
        else if($_POST['action'] == "remove")
        {
            //delete student entirely
            $query = "DELETE FROM students_archive WHERE student_id=$usn;";
            if(mysqli_query($conn, $query)){ echo "OK"; }
            else { echo mysqli_error($conn); }
        }
    }
    else
    {
        //generate courses dropdown input
        $query = mysqli_query($conn, "SELECT course FROM students_archive GROUP BY course;");
        $row = array();

        while($r = mysqli_fetch_assoc($query))
        {
            $row[] = $r;
        }

        $options = "<option value='' selected>All</option>";
        foreach($row as $data)
        {
            $course = $data['course'];
            $options .= "<option value='$course'>$course</option>";
        }
        echo $options;
    }
    
?>