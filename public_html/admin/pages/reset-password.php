<?php
    include '../../php/config.php';

    if (isset($_GET["key"]) && isset($_GET["email"]) && isset($_GET["action"]) 
    && ($_GET["action"]=="reset") && !isset($_POST["action"]))
    {
        $error = "";
        $resetForm = "";
        $key = $_GET["key"];
        $email = $_GET["email"];
        $curDate = date("Y-m-d H:i:s");
        $query = mysqli_query($conn, "SELECT * FROM password_reset_temp WHERE keycode='$key' and email='$email';");
        $row = mysqli_num_rows($query);

        if ($row=="")
        {
            $error .= '<h2>Invalid Link</h2>
            <p>The link is invalid/expired. Either you did not copy the correct link
            from the email, or you have already used the key in which case it is 
            deactivated.</p>
            <p><a href="forgot-password.html">
            Click here</a> to reset password.</p>';
        }
        else
        {
            $row = mysqli_fetch_assoc($query);
            $expDate = $row['exp_date'];
            if ($expDate >= $curDate){
                $resetForm = '<form method="post" id="reset-form" name="reset-form" role="form text-left">
                <div class="mb-3">
                  <label for="password">New Password</label>
                  <input id="password" name="password" type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="email-addon" autocomplete="off">
                </div>
                <div class="mb-3">
                  <label for="cpassword">Confirm New Password</label>
                  <input id="cpassword" name="cpassword" type="password" class="form-control" placeholder="Confirm Password" aria-label="Confrirm Password" aria-describedby="email-addon" autocomplete="off">
                </div>
                <input type="hidden" name="email" value="'.$email.'">
                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Reset Password</button>
                </div>
              </form>';
            }
            else
            {
                $error .= "<h2>Link Expired</h2>
                <p>The link is expired. You are trying to use the expired link which 
                as valid only 24 hours (1 days after request).<br /><br /></p>";
            }
        }
        if($error!=""){
            $resetForm = $error;
        }			
    } // isset email key validate end
?>

<!--
=========================================================
* Soft UI Dashboard - v1.0.7
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2023 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="icon" type="image/png" href="../../images/aclc-logo.png">
  <title>
    Password Reset
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.7" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>

<body class="">
  <main class="main-content  mt-0">
    <section class="min-vh-100 mb-8">
      <div class="page-header align-items-start min-vh-50 pt-5 pb-11 m-3 border-radius-lg" style="background-image: url('../assets/img/curved-images/curved14.jpg');">
        <span class="mask bg-gradient-dark opacity-6"></span>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-5 text-center mx-auto">
              <p class="text-lead text-white"></p>
            </div>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10">
          <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
            <div class="card z-index-0">
              <div class="card-header text-center pt-4">
                <h5>Reset Password</h5>
              </div>
              
              <div class="card-body">
                
                <div id="result"></div>
                <?php echo $resetForm; ?>
                <div id="loading" class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    
    <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  </main>
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="../../js/jquery.min.js"></script>
  <script src="../../js/jquery.validate.min.js"></script>
  <script src="../assets/js/reset-password.js"></script>
  <script src="https://unpkg.com/@bitjson/qr-code@1.0.2/dist/qr-code.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/soft-ui-dashboard.min.js?v=1.0.7"></script>
</body>

</html>