<?php
    @include 'config.php';

    if($_POST)
    {
      $lab = $_POST['lab'];
      $pc = $_POST['pc'];
      $subject = $_POST['subject'];
      $course = $_POST['course'];
      $date = "";
      $query = "SELECT student_id, computer_id, subject, DATE_FORMAT(date, '%Y-%m-%d %h:%i:%s %p') as date 
      FROM logs WHERE computer_id RLIKE '^$lab' ";

      if($_POST['date'] != "")
      {
        $date = $_POST['date'];
        $query .= "AND DATE_FORMAT(date, '%Y-%m-%d') = '$date' ";
      }

      if($pc != "")
      {
        $query .= "AND computer_id = '$lab$pc' ";
      }
      
      if($subject != "")
      {
        $query .= "AND subject = '$subject' ";
      }
      
      if($course != "")
      {
        $query .= "AND student_id IN (SELECT student_id FROM students WHERE course = '$course') ";
      }

      $query .= "ORDER BY date DESC;";
      $select = mysqli_query($conn, $query);
      /* $query = mysqli_query($conn,"SELECT * FROM logs WHERE computer_id RLIKE '^$lab' ORDER BY date DESC;"); */
      $logs = array();
      $table = "";

      while($r = mysqli_fetch_assoc($select))
      {
          $logs[] = $r;
      }
      
      if(!empty($logs))
      {
          foreach($logs as $r)
          {
              $usn = $r['student_id'];
              $subject = $r['subject'];
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


              $name = "";
              $course = "";

              if(empty($row))
              {
                $name = "Student Removed<br>USN: $usn";
                $course = "Unknown";
              }
              else
              {
                $name = $row[0]['name'];
                $course = $row[0]['course'];
              }

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
                <p class='text-xs font-weight-bold mb-0'>$subject</p>
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
        $table = "<h6 class='ms-3'>No Matching Logs</h6>";
      }
      echo $table;
    }
    else
    {
      //For filling out options for dropdown inputs
      $options = array("subjects" => "", "courses" => "");

      //Get Subjects
      $query = mysqli_query($conn, "SELECT subject FROM logs GROUP BY subject;");
      $row = array();

      while($r = mysqli_fetch_assoc($query))
      {
        $row[] = $r;
      }

      $subjects = "<option value='' selected>All Subjects</option>";
      foreach($row as $data)
      {
        $sub = $data['subject'];
        $subjects .= "<option value='$sub'>$sub</option>";
      }
      $options['subjects'] = $subjects;

      //Get courses
      $query = mysqli_query($conn, "SELECT course FROM students 
      WHERE student_id IN (SELECT student_id FROM logs GROUP BY student_id) GROUP BY course;");
      $row = array();

      while($r = mysqli_fetch_assoc($query))
      {
        $row[] = $r;
      }

      $courses = "<option value='' selected>All Courses</option>";
      foreach($row as $data)
      {
        $cou = $data['course'];
        $courses .= "<option value='$cou'>$cou</option>";
      }
      $options['courses'] = $courses;
      echo json_encode($options);
    }