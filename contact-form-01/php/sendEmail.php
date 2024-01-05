<?php

// Replace this with your own email address
$to = 'louiejiemahusay@gmail.com';

function url(){
  return sprintf(
    "%s://%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME']
  );
}

if($_POST) {

   $lab = trim(stripslashes($_POST['lab']));
   $pc = trim(stripslashes($_POST['pc']));
   $issue = trim(stripslashes($_POST['issue']));


	if ($issue) { echo "OK"; }
   else { echo "Something went wrong. Please try again."; }

}

?>