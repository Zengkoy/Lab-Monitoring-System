<?php
    @include 'config.php';

    if(isset($_POST['course']))
    {
        $course = $_POST['course'];

        $query = "";
        if($course == "") 
        {
            $query = mysqli_query($conn,"SELECT * FROM students;");
        }
        else
        {
            $query = mysqli_query($conn,"SELECT * FROM students WHERE course = '$course';");
        }
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
                        <input data-student='$usn' onClick='remove_student(this)' class='btn btn-link btn-sm text-danger font-weight-normal mb-0 me-3' type='button' value='Delete'/>
                    </td>";
                
                $table .= "</tr>";
            }
            echo $table;
        }
        else
        {
            echo "No Students Registered.";
        }
    }
    else if(isset($_POST['student']))
    {
        $usn = $_POST['student'];

        $query = "DELETE FROM students WHERE student_id = $usn;";
        if(mysqli_query($conn, $query))
        {
            echo "OK";
        }
        else
        {
            echo mysqli_error($conn);
        }
    }
    else
    {
        $query = mysqli_query($conn, "SELECT course FROM students GROUP BY course;");
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