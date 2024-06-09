<?php
    @include 'config.php';

    if(isset($_POST['action']))
    {
        //add or remove lab
        $lab = $_POST['lab'];
        $query = "";
        $delete = "";
        $select = mysqli_query($conn, "SELECT * FROM labs WHERE lab=$lab;");

        $error = "";
        if($_POST['action'] == "add")
        {
            if(mysqli_num_rows($select) > 0)
            {
                $error = "Lab already exists";
            }
            else
            {
                $query = "INSERT INTO labs VALUES($lab);";
            }
        }
        else if($_POST['action'] == "remove")
        {
            $query = "DELETE FROM labs WHERE lab = '$lab';";
            $delete = "DELETE FROM computers WHERE computer_id RLIKE '^$lab';";
        }

        if($error == "")
        {
            if(mysqli_query($conn, $query))
            {
                if($delete != "")
                {
                    if(mysqli_query($conn, $delete)) { echo "OK"; }
                    else { echo mysqli_error($conn); }
                }
                else { echo "OK"; }
            }
            else
            {
                echo mysqli_error($conn);
            }
        }
        else
        {
            echo $error;
        }
        
    }
    else
    {
        //generate labs table
        $query = mysqli_query($conn,"SELECT * FROM labs;");
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
                $lab = $r['lab'];

                $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM computers WHERE computer_id RLIKE '^$lab';");
                $computers = mysqli_fetch_assoc($query);
                $count = $computers['count'];

                $table .= "<li class='list-group-item border-0 d-flex justify-content-between ps-0 mb-2 bg-light border-radius-lg'>
                <div class='d-flex align-items-center'>";
                
                $table .= "<div class='d-flex flex-column'>
                <h6 class='mb-1 text-dark text-m ps-2'>Lab $lab</h6>
                <span class='text-xs ms-3'>Computers: $count</span>
                </div>";

                $table .= "</div>
                <div class='d-flex align-items-center text-sm font-weight-bold'>
                        <button class='p-0 btn btn-link text-danger' onClick='remove_lab(this)' data-lab='$lab'>Remove</button>
                    </div>
                </li>";
            }
        }
        else
        {
            $table .= "<li class='ms-3'>There are no Laboratories Registered</li>";
        }

        echo $table;
    }
    
?>