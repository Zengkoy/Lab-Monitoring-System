<?php
    @include 'config.php';

    $lab = $_POST['lab'];

    $query = mysqli_query($conn,"SELECT * FROM reports WHERE computer_id RLIKE '^$lab';");
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
            $query = mysqli_query($conn,"SELECT name FROM students WHERE student_id = '$reporter';");
            $reporter = mysqli_fetch_assoc($query)['name'];

            $reportId = $r['report_id'];
            $computer_id = $r['computer_id'];
            $lab = substr($computer_id, 0, 1);
            $pc = substr($computer_id, 1);
            $desc = $r['issue'];
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

            $table .= "<td class='align-middle text-center'>";
            $table .= "<span class='text-secondary text-xs font-weight-bold'>$date</span></td>";

            if($status == "pending")
            {
                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='badge badge-sm bg-gradient-secondary'>$status</span></td>";
                $table .= "<td class='align-middle'>
                        <input data-report='$reportId' data-computer='$computer_id' class='btn btn-success btn-sm mb-0 me-3' type='button' onclick='resolve(this)' value='Resolve'/>
                    </td>";
            }
            else if($status == "resolved")
            {
                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='badge badge-sm bg-gradient-success'>$status</span></td>";
                $table .= "<td class='align-middle'>
                <input data-report='$reportId' data-computer='$computer_id' class='btn btn-secondary btn-sm mb-0 me-3' type='button' onclick='unresolve(this)' value='Unresolve'/>
                </td>";
            }
            $table .= "<td class='align-middle'>
                    <input data-report='$reportId' data-computer='$computer_id' class='btn btn-link btn-sm text-danger font-weight-normal mb-0 me-3' type='button' onclick='delete_report(this)' value='Delete'/>
                </td>";
            
            $table .= "</tr>";
        }
    }
    $file = fopen("table.txt", "w");
    fwrite($file, $table);
    fclose($file);
?>