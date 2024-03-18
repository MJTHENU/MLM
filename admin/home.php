<?php
include('php-includes/check-login.php');
require('php-includes/connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KBM  - Home</title>

    <!-- Dashboard CSS -->
    <link href="admin/admincss/dashboard.css" rel="stylesheet">

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.2.96/css/materialdesignicons.min.css" integrity="sha512-LX0YV/MWBEn2dwXCYgQHrpa9HJkwB+S+bnBpifSOTO1No27TqNMKYoAn6ff2FBh03THAzAiiCwQ+aPX+/Qt/Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include('php-includes/menu.php'); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="c1">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Admin Home</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="../images/circle.png" class="card-img-absolute" alt="circle">
                                <h4 class="font-weight-normal mb-3">Total User<i class="mdi mdi-account-group mdi-24px float-right"></i></h4>
                                <h2 class="mb-5"><?php 
								echo  mysqli_num_rows(mysqli_query($con,"select * from user"));
								?></h2>
                               
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="../images/circle.png" class="card-img-absolute" alt="circle">
                                <h4 class="font-weight-normal mb-3">Pin Request<i class="mdi mdi-account-supervisor-circle mdi-24px float-right"></i></h4>
                                <h2 class="mb-5"><?php 
								echo  mysqli_num_rows(mysqli_query($con,"select * from pin_request"));
								?></h2>
                               
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="../images/circle.png" class="card-img-absolute" alt="circle">
                                <h4 class="font-weight-normal mb-3">Total pin<i class="mdi mdi-cart-arrow-down mdi-24px float-right"></i></h4>
                                <h2 class="mb-5"><?php 
								echo  mysqli_num_rows(mysqli_query($con,"select * from pin_list"));
								?></h2>
                             
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 stretch-card grid-margin">
                        <div class="card bg-gradient-last card-img-holder text-white">
                            <div class="card-body">
                                <img src="../images/circle.png" class="card-img-absolute" alt="circle">
                                <h4 class="font-weight-normal mb-3">Total Clients<i class="mdi mdi-cart-arrow-down mdi-24px float-right"></i></h4>
                                <h2 class="mb-5"><?php 
								echo  mysqli_num_rows(mysqli_query($con,"select * from income_received"));
								?>
								</h2>
                                
                            </div>
                        </div>
                    </div>
</div>
                </div> 
            <!-- /.container-fluid -->
        </div>
       
        
        <!-- /#page-wrapper -->

    </div>
        <footer id="sticky-footer" class="flex-shrink-0 py-4 bg-dark text-white-50">
            <div class="container text-center" style="padding:12px;">
              <small>Copyright &copy; Kitecareer.com</small>
            </div>
        </footer>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
