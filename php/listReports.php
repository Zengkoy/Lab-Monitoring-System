<?php
    @include 'config.php';

    $query = mysqli_query($conn,"SELECT * FROM reports;");
    $row = array();

    while($r = mysqli_fetch_assoc($query))
        {
            $row[] = $r;
        }
    
    if(!empty($row))
    {
        $table = "<table class='table align-items-center mb-0'>
        <thead>
          <tr>
            <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Report ID</th>
            <th class='text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2'>Computer ID</th>
            <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Issue</th>
            <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Submitted</th>
            <th class='text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7'>Status</th>
            <th class='text-secondary opacity-7'></th>
          </tr>
        </thead>
        <tbody>";
        foreach($row as $r)
        {
            $table .= "<tr>";

            foreach($r as $c)
            {
                $table .= "<td class='align-middle text-center'>
                <span class='text-secondary text-xs font-weight-bold'>$c</span>
                </td>";
            }
            $table .= "<td class='align-middle'>
            <a href='javascript:;' class='text-secondary font-weight-bold text-xs' data-toggle='tooltip' data-original-title='Edit user'>
              Edit
            </a>
            </td>";
            $table .= "</tr>";
        }

        $table .= "</tbody>
        </table>";
        echo $table;
    }
?>