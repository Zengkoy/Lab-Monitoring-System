<?php
    @include 'config.php';

    $query = mysqli_query($conn,"SELECT subject FROM subjects;");
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
            $subject = $r['subject'];

            $table .= "<li class='list-group-item border-0 d-flex justify-content-between ps-0 mb-2 bg-light border-radius-lg'>
              <div class='d-flex align-items-center'>";
            
            $table .= "<div class='d-flex flex-column'>
            <h6 class='mb-1 text-dark text-m ps-2'>$subject</h6>
            </div>";

            $table .= "</div>
              <div class='d-flex align-items-center text-sm font-weight-bold'>
                    <button class='p-0 btn btn-link text-danger' onClick='remove_subject(this)' data-subject='$subject'>Remove</button>
                  </div>
            </li>";
        }
    }
    else
    {
        $table .= "<li>There are no Subjects Registered</li>";
    }

    echo $table;
    /* $file = fopen("comp-table.txt", "w");
    fwrite($file, $table);
    fclose($file); */
?>