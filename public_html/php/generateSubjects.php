<?php
    @include 'config.php';

    $query = mysqli_query($conn,"SELECT * FROM subjects;");
    $row = array();
    $table = "";

    while($r = mysqli_fetch_assoc($query))
        {
            $row[] = $r;
        }
    
    if(!empty($row))
    {
        $table = "<label class='form-check-label' for='subject'><h6>Select the Subject:</h6></label>
        <select class='form-control-sm' name='subject' id='subject'>";

        foreach($row as $r)
        {
            $subject = $r['subject'];
            $table .= "<option value='$subject' selected>$subject</option>";
        }

        $table .= "</select>";
    }
    
    echo $table;
?>