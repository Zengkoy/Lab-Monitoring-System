<?php
    @include 'config.php';

    $query = mysqli_query($conn,"SELECT * FROM reports;");
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
            $reportId = $r['report_id'];
            $lab = substr($r['computer_id'], 0, 1);
            $pc = substr($r['computer_id'], 1);
            $desc = $r['issue'];
            $date = $r['date'];
            $status = $r['status'];

            $table .= "<tr>";

            $table .= "<td class='align-middle text-center'>";
            $table .= "<span class='text-secondary text-xs font-weight-bold'>$reportId</span></td>";
            
            $table .= "<td><span class='text-secondary text-xs font-weight-bold'>$lab</span></td>";
            $table .= "<td><span class='text-secondary text-xs font-weight-bold'>$pc</span></td>";
            
            $table .= "<td class='align-middle text-center desc'>";
            $table .= "<span class='text-secondary text-xs font-weight-bold'>$desc</span></td>";

            $table .= "<td class='align-middle text-center'>";
            $table .= "<span class='text-secondary text-xs font-weight-bold'>$date</span></td>";

            if($status == "pending")
            {
                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='badge badge-sm bg-gradient-secondary'>$status</span></td>";
                $table .= "<td class='align-middle'>
                    <form id='report-id$reportId' method='POST' action='../../php/resolveIssue.php'>
                        <input type='text' name='key' class='hidden' value='$reportId' />
                        <input class='btn btn-success btn-sm mb-0 me-3' type='submit' onclick='resolve()' value='Resolve'/>
                    </form>
                    </td>";
            }
            else if($status == "resolved")
            {
                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='badge badge-sm bg-gradient-success'>$status</span></td>";
                $table .= "<td class='align-middle'>
                <form id='report-id$reportId' method='POST' action='../../php/unresolveIssue.php'>
                    <input type='text' name='key' class='hidden' value='$reportId' />
                    <input class='btn btn-secondary btn-sm mb-0 me-3' type='submit' onclick='resolve()' value='Unresolve'/>
                </form>
                </td>";
            }
            $table .= "<td class='align-middle'>
                <form id='report-id$reportId' method='POST' action='../../php/deleteIssue.php'>
                    <input type='text' name='key' class='hidden' value='$reportId' />
                    <input class='btn btn-danger btn-sm mb-0 me-3' type='submit' onclick='resolve()' value='delete'/>
                </form>
                </td>";

            
            $table .= "</tr>";
        }
        $file = fopen("table.txt", "w");
        fwrite($file, $table);
        fclose($file);
    }
?>