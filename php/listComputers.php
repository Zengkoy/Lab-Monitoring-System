<?php
    @include 'config.php';

    $lab = "";
    if(isset($_POST))
    {
        $lab = $_POST['lab-select-dd'];
    }
    else
    {
        $lab = 1;
    }

    $query = mysqli_query($conn,"SELECT computer_id, status FROM computers WHERE lab = '$lab' ORDER BY computer_id;;");
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
            $computerId = $r['computer_id'];
            $pc = substr($r['computer_id'], 1);
            $usability = $r['status'];

            $table .= "<li class='list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg'>
              <div class='d-flex align-items-center'>";
            
            if($usability == "usable")
            {
                $table .= "<button class='btn btn-icon-only btn-rounded btn-outline-success mb-0 me-3 btn-sm d-flex align-items-center justify-content-center'><i class='fas fa-arrow-up'></i></button>
                    <div class='d-flex flex-column'>
                    <h6 class='mb-1 text-dark text-m'>PC$pc</h6>
                    <span class='text-xs text-success'>Usable</span>
                    </div>";
            }
            else if($usability == "unusable")
            {
                $table .= "<button class='btn btn-icon-only btn-rounded btn-outline-danger mb-0 me-3 btn-sm d-flex align-items-center justify-content-center'><i class='fas fa-arrow-down'></i></button>
                    <div class='d-flex flex-column'>
                    <h6 class='mb-1 text-dark text-m'>PC$pc</h6>
                    <span class='text-xs text-danger'>Unusable</span>
                    </div>";
            }
            else
            {
                $table .= "<button class='btn btn-icon-only btn-rounded btn-outline-warning mb-0 me-3 btn-sm d-flex align-items-center justify-content-center'><i class='fas fa-exclamation'></i></button>
                    <div class='d-flex flex-column'>
                    <h6 class='mb-1 text-dark text-m'>PC$pc</h6>
                    <span class='text-xs text-warning'>Usable with issues</span>
                    </div>";
            }
            
            $table .= "</div>
              <div class='d-flex align-items-center text-sm font-weight-bold'>
                    <button class='p-0 btn btn-link text-info' onclick='show_computer_details(this)' data-pcid='$computerId'>View Status</button>
                    <p>|</p>
                    <button class='p-0 btn btn-link text-danger' onClick='remove_computer(this)' data-pcid='$computerId'>Remove</button>
                  </div>
            </li>";
        }
    }
    else
    {
        $table .= "<li>There are no computers in Lab $lab.</li>";
    }
    $file = fopen("comp-table.txt", "w");
    fwrite($file, $table);
    fclose($file);
?>