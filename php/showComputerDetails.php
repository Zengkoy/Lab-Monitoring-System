<?php
    @include 'config.php';

    $pcid = $_POST['pc'];
    $query = mysqli_query($conn,"SELECT * FROM computers WHERE computer_id = '$pcid';");
    $row = array();
    $table = "";

    while($r = mysqli_fetch_assoc($query))
    {
        $row[] = $r;
    }

    $issues = array();
    $query = mysqli_query($conn, "SELECT * FROM reports WHERE computer_id='$pcid' AND status='pending';");

    while($i = mysqli_fetch_assoc($query))
    {
        $issues[] = $i;
    }
    
    if(!empty($row))
    {
        $computer = $row[0];
        $pc = substr($computer['computer_id'], 1);
        $lab = $computer['lab'];
        $usability = $computer['status'];
        $issueCount = count($issues);

        $table .= "<div class='col-md-7 mt-4'>
        <div class='card'>
          <div class='card-header pb-0 px-3 d-flex'>
            <h6 class='mb-0'>Computer #$pc</h6>
            <button class='btn btn-link text-xs text-secondary ms-auto' onclick='hide_details()'>hide</button>
          </div>
          <div class='card-body pt-4 p-3'>
            <ul class='list-group'>
              <li class='list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg'>
                <div class='d-flex flex-column'>";

        if($usability == "usable")
        {
            $table .= "<h6 class='mb-3 text-sm text-success'>Usable</h6>";
        }
        else if($usability == "unusable")
        {
            $table .= "<h6 class='mb-3 text-sm text-danger'>Unusable</h6>";
        }
        else
        {
            $table .= "<h6 class='mb-3 text-sm text-warning'>Usable with issues</h6>";
        }
        
        $table .= "<span class='mb-2 text-xs'>Laboratory: <span class='text-dark font-weight-bold ms-sm-2'>Lab $lab</span></span>
                  <span class='mb-2 text-xs'>Computer ID: <span class='text-dark ms-sm-2 font-weight-bold'>$pcid</span></span>
                  <span class='text-xs'>Unresolve Issues: <span class='text-dark ms-sm-2 font-weight-bold'>$issueCount</span></span>
                </div>
                <div class='ms-auto text-end'>
                  <button id='show-reports-btn' class='btn btn-link text-dark px-3 mb-0' onclick='show_reports();'><i class='fas fa-book text-dark me-2' aria-hidden='true'></i>View Reports</button>
                </div>
              </li>
            </ul>
          </div>
        </div>";

        $issues = array();
        $query = mysqli_query($conn, "SELECT * FROM reports WHERE computer_id='$pcid';");

        while($i = mysqli_fetch_assoc($query))
        {
            $issues[] = $i;
        }

        if(!empty($issues))
        {
          $table .= "<div id='related-reports' class='mt-4' style='display: none;'>
          <div class='card'>";
          $table .= "<div class='card-header pb-0'>
          <h6>Related Reports</h6>
          </div>";
          $table .= "<table class='table align-items-center mb-0'>
          <thead>
            <tr>
              <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Issue</th>
              <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Submitted</th>
              <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Status</th>
              <th class='text-secondary opacity-7' colspan='2'></th>
            </tr>
          </thead>
          <tbody id='reportsTable'>";
          foreach($issues as $r)
          {

            $desc = $r['issue'];
            $date = $r['date'];
            $status = $r['status'];
            $reportId = $r['report_id'];
            $computer_id = $r['computer_id'];

            $table .= "<tr>";
            $table .= "<td class='align-middle text-center desc'>";
            $table .= "<span class='text-secondary text-xs font-weight-bold'>$desc</span></td>";

            $table .= "<td class='align-middle text-center'>";
            $table .= "<span class='text-secondary text-xs font-weight-bold'>$date</span></td>";

            if($status == "pending")
            {
                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='badge badge-sm bg-gradient-secondary'>$status</span></td>";
                $table .= "<td class='align-middle'>
                        <input data-report='$reportId' data-pcid='$computer_id' class='btn btn-success btn-sm mb-0 me-3' type='button' onclick='resolve(this)' value='Resolve'/>
                    </td>";
            }
            else if($status == "resolved")
            {
                $table .= "<td class='align-middle text-center'>";
                $table .= "<span class='badge badge-sm bg-gradient-success'>$status</span></td>";
                $table .= "<td class='align-middle'>
                <input data-report='$reportId' data-pcid='$computer_id' class='btn btn-secondary btn-sm mb-0 me-3' type='button' onclick='unresolve(this)' value='Unresolve'/>
                </td>";
            }
            $table .= "<td class='w-5 align-middle'>
                    <input data-report='$reportId' data-pcid='$computer_id' class='btn btn-link btn-sm text-danger font-weight-normal mb-0 me-3' type='button' onclick='delete_report(this)' value='Delete'/>
                </td>";
            
            $table .= "</tr>";
        }
        $table .= "</div></div></tbody>";
      }
    }
    else
    {
        $table .= "<li>Error</li>";
    }
    echo $table;
?>