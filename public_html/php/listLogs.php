<?php
    @include 'config.php';

    $lab = $_POST['lab'];
    $pc = $_POST['pc'];
    $date = "";
    $query = "";

    if($_POST['date'] != "" and $pc != "")
    {
      $date = $_POST['date'];
      
      $query = mysqli_query($conn,"SELECT student_id, computer_id, DATE_FORMAT(date, '%Y-%m-%d %h:%i:%s %p') as date 
      FROM logs WHERE computer_id = '$lab$pc' AND DATE_FORMAT(date, '%Y-%m-%d') = '$date' 
      ORDER BY date DESC;");
    }
    else if($_POST['date'] != "" and $pc == "")
    {
      $date = $_POST['date'];

      $query = mysqli_query($conn,"SELECT student_id, computer_id, DATE_FORMAT(date, '%Y-%m-%d %h:%i:%s %p') as date 
      FROM logs WHERE computer_id RLIKE '^$lab' AND DATE_FORMAT(date, '%Y-%m-%d') = '$date' 
      ORDER BY date DESC;");
    }
    else if($_POST['date'] == "" and $pc != "")
    {
      $query = mysqli_query($conn,"SELECT student_id, computer_id, DATE_FORMAT(date, '%Y-%m-%d %h:%i:%s %p') as date 
      FROM logs WHERE computer_id = '$lab$pc' ORDER BY date DESC;");
    }
    else
    {
      $query = mysqli_query($conn,"SELECT student_id, computer_id, DATE_FORMAT(date, '%Y-%m-%d %h:%i:%s %p') as date FROM logs WHERE computer_id RLIKE '^$lab' ORDER BY date DESC;");
    }

    /* $query = mysqli_query($conn,"SELECT * FROM logs WHERE computer_id RLIKE '^$lab' ORDER BY date DESC;"); */
    $logs = array();
    $table = "";

    while($r = mysqli_fetch_assoc($query))
    {
        $logs[] = $r;
    }
    
    if(!empty($logs))
    {
        foreach($logs as $r)
        {
            $usn = $r['student_id'];
            $computer_id = $r['computer_id'];
            $lab = substr($computer_id, 0, 1);
            $pc = substr($computer_id, 1);
            $date = $r['date'];

            $query = mysqli_query($conn,"SELECT * FROM students WHERE student_id = '$usn';");
            $row = array();

            while($r = mysqli_fetch_assoc($query))
            {
                $row[] = $r;
            }

            $name = $row[0]['name'];
            $course = $row[0]['course'];

            $table .= "<tr>
            <td>
              <div class='d-flex px-2 py-1'>
                <div class='d-flex flex-column justify-content-center'>
                  <h6 class='mb-0 text-sm'>$name</h6>
                  <!-- <p class='text-xs text-secondary mb-0'>john@creative-tim.com</p> -->
                </div>
              </div>
            </td>
            <td>
              <p class='text-xs font-weight-bold mb-0'>$course</p>
            </td>
            <td>
              <p class='text-xs font-weight-bold mb-0'>Lab $lab</p>
              <p class='text-xs text-secondary mb-0'>PC$pc</p>
            </td>
            <td class='align-middle text-center'>
              <span class='text-secondary text-xs font-weight-bold'>$date</span>
            </td>
            
          </tr>";
        }
    }
    else
    {
      $table = "<h6>No Matching Logs</h6>";
    }
    $file = fopen("logs.txt", "w");
    fwrite($file, $table);
    fclose($file);
?>