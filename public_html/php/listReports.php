<?php
    @include 'config.php';

    date_default_timezone_set("Asia/Taipei");

    $lab = $_POST['lab'];
    $status = $_POST['status'];
    $type = $_POST['type'];
    $recentFilter = $_POST['recent'];
    $specDate = $_POST['spec-date'];

    $query = "SELECT * FROM reports WHERE computer_id RLIKE '^$lab' ";
    if($status != "") { $query .= "AND status = '$status' "; }
    if($type != ""){ $query .= "AND prob_type = '$type' "; }
    if($recentFilter != "")
    {
        $pastDate = "";
        if($recentFilter == "past week"){ $pastDate = date("Y-m-d", strtotime("-1 week")); }
        else if($recentFilter == "past month"){ $pastDate = date("Y-m-d", strtotime("-1 month")); }
        else if($recentFilter == "past year"){ $pastDate = date("Y-m-d", strtotime("-1 year")); }
        $query .= "AND DATE_FORMAT(date, '%Y-%m-%d') > '$pastDate' ";
    }
    if($specDate != "")
    {
        $query .= "AND DATE_FORMAT(date, '%Y-%m') = '$specDate' ";
    }
    $query .= "ORDER BY date DESC;";
    echo $query;
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
            $reporter = $r['submitted_by'];
            $isAdmin = false;

            $query = "";
            if(substr($reporter, 0, 5) == "admin")
            {
                $isAdmin = true;
                $admin = substr($reporter, 5);
                $query = mysqli_query($conn,"SELECT username FROM admin WHERE admin_id = '$admin';");
                $reporter = "Administrator: ";
            }
            else
            {
                $query = mysqli_query($conn,"SELECT name FROM students WHERE student_id = '$reporter';");
            }

            $user = mysqli_fetch_assoc($query);

            if(empty($user))
            {
                $reporter = "Student Removed<br>USN:$reporter";
            }
            else
            {
                if($isAdmin){ $reporter .= $user[array_keys($user)[0]]; }
                else { $reporter = $user[array_keys($user)[0]]; }
            }

            $reportId = $r['report_id'];
            $computer_id = $r['computer_id'];
            $lab = substr($computer_id, 0, 1);
            $pc = substr($computer_id, 1);
            $desc = $r['issue'];
            $type = $r['prob_type'];
            $date = $r['date'];
            $status = $r['status'];

            $table .= "<tr>";

            $table .= "<td class='align-middle text-center'>";
            $table .= "<span class='text-secondary text-xs font-weight-bold'>$reporter</span></td>";
            
            $table .= "<td><span class='text-secondary text-xs font-weight-bold'>$lab</span></td>";
            $table .= "<td><span class='text-secondary text-xs font-weight-bold'>$pc</span></td>";
            
            $table .= "<td id='desc$reportId' class='align-middle text-center'>
            <div class='desc'>
            <button onClick='show_description(this)' class='btn btn-link m-0 p-0 me-2' data-id='$reportId'><i class='fa fa-eye'></i></button>";
            $table .= "<span class='text-secondary text-xs font-weight-bold'>$desc</span>
            </div></td>";

            $table .= "<td><span class='text-secondary text-xs font-weight-bold'>$type</span></td>";

            $table .= "<td class='align-middle text-center'>";
            $table .= "<span class='text-secondary text-xs font-weight-bold'>$date</span></td>";

            if($status == "pending")
            {
                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='badge badge-sm bg-gradient-secondary'>$status</span></td>";
                $table .= "<td class='justify-content-center'>
                        <input data-report='$reportId' data-computer='$computer_id' class='btn btn-warning btn-sm mb-0 p-1 py-2 w-80' type='button' onclick='resolve(this)' value='Resolve'/>
                    </td>";
            }
            else if($status == "resolved")
            {
                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='badge badge-sm bg-gradient-success'>$status</span></td>";
                $table .= "<td class='justify-content-center'>
                <input data-report='$reportId' data-computer='$computer_id' class='btn btn-secondary btn-sm mb-0 p-1 py-2' type='button' onclick='unresolve(this)' value='Unresolve'/>
                </td>";
            }
            $table .= "<td class='align-middle'>
                    <input data-report='$reportId' data-computer='$computer_id' class='btn btn-link btn-sm text-danger font-weight-normal mb-0 me-3 px-1' type='button' onclick='delete_report(this)' value='Delete'/>
                </td>";
            
            $table .= "</tr>";
        }
    }
    $file = fopen("table.txt", "w");
    fwrite($file, $table);
    fclose($file);
?>