<?php
    session_start();
    @include 'config.php';

    $data = array(
        'result' => '',
        'student' => '',
        'email' => '',
        'reports' => '',
    );

    $usn = $_SESSION['student_id'];
    $query = mysqli_query($conn, "SELECT name, email FROM students WHERE student_id = $usn;");
    $student = mysqli_fetch_assoc($query);
    $data['student'] = $student['name'];
    $data['email'] = $student['email'];

    $query = mysqli_query($conn,"SELECT * FROM reports WHERE submitted_by = $usn;");
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
            $computer_id = $r['computer_id'];
            $lab = substr($computer_id, 0, 1);
            $pc = substr($computer_id, 1);
            $desc = $r['issue'];
            $date = $r['date'];
            $status = $r['status'];

            $table .= "<li class='list-group-item border-0 d-flex align-items-center px-2 ";

            if($status == "pending")
            {
                $table .= "bg-warning";
            }
            else if ($status == "resolved")
            {
                $table .= "bg-success";
            }

            $table .= "'>
            <div class='d-flex align-items-start flex-column justify-content-center'>
              <h6 class='mb-0 text-sm'>Lab $lab PC $pc</h6>
              <p class='mb-0 text-xs'>$status</p>
            </div>
            <div class='d-flex align-items-start flex-column justify-content-center ms-auto'>
                <p class='text-xs ms-auto'>$date</p>
                <p class='text-xs ms-auto w-50 vh-3 overflow-hidden'>$desc</p>
            </div>
          </li>";
        }
        $data['result'] = "OK";
        $data['reports'] = $table;
    }
    else
    {
        $data['result'] = "OK";
        $data['reports'] = "<p class='text-xs ms-auto'>No Reports</p>";
    }

    echo json_encode($data);
?>