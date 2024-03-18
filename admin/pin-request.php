<?php
require('php-includes/connect.php');
include('php-includes/check-login.php');
$email = $_SESSION['userid'];
?>
<?php
//pin request 
if(isset($_GET['pin_request'])){
	$amount = mysqli_real_escape_string($con,$_GET['amount']);
  $paymentmode = mysqli_real_escape_string($con,$_GET['payment_mode']);
	$date = date("y-m-d");
	
	
	if($amount!=''){
		//Inset the value to pin request
		$query = mysqli_query($con,"insert into pin_request(`email`,`amount`,`payment_mode`,`date`) values('$email','$amount','$paymentmode','$date')");
		if($query){
			echo '<script>alert("Pin request sent successfully");window.location.assign("pin-request.php");</script>';
		}
		else{
			//echo mysqli_error($con);
			echo '<script>alert("Unknown error occure.");window.location.assign("pin-request.php");</script>';
		}
	}
	else{
		echo '<script>alert("Please fill all the fields");</script>';
	}
	
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mlm Website  - Pin Request</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">

    <!-- Pin Request CSS -->
    <link href="./admincss/pin-request.css" rel="stylesheet">

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

 

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include('php-includes/menu.php'); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                
                    <div class="col-lg-12">
                        <h1 class="page-header" style="color:black">Pin Request</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="card">
                <div class="row">
                	
                    
                    	<form method="get">	
                        	<div class="form-group">
                            	<label>Amount</label>
                               <!--  <input type="text" name="amount" class="form-control" required placeholder="Max amount 1500"> -->
                                <select name="amount" class="form-control" required>
                                <option value="">Please Select</option>
                                <option value="2000">2000</option>
                                <option value="4000">4000</option>
                                </select>

                            </div>

                                <div class="form-group">
                                    <label for="Gender" class="select">Payment Mode</label>
                                    <select name="payment_mode" id="payment" class="form-control">
                                       <option value="Cash">Cash</option> 
                                        <option value="Net Banking">NetBanking</option>
                                       
                                       <!--  <option value="Product Buy">Product Buy</option>  -->
                                    </select>
                                </div>
                            

                            
                            <div class="form-group">
                            	<input type="submit" name="pin_request" class="btn btn-success" value="Pin Request">
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="row">
                	
                    	<br><br>
                       
                    	<table class="table table-bordered table-striped">
                        	<tr>
                           
                            	<th>S.n.</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th >Payment Mode </th>
                                </tr>
                               
                            <?php 
							$i=1;
							$query = mysqli_query($con,"select * from pin_request where email='$email' order by id desc");
							if(mysqli_num_rows($query)>0){
								while($row=mysqli_fetch_array($query)){
									$amount = $row['amount'];
									$date = $row['date'];
									$status = $row['status'];
                  $paymentmode = isset($row['payment_mode'])?$row['payment_mode']:"";
                                    
								?>
                                 
                                	<tr>
                                    	<td><?php echo $i; ?></td>
                                        <td><?php echo $amount; ?></td>
                                        <td><?php echo $date; ?></td>
                                        <td><?php echo $status; ?></td>
                                        <td><?php echo $paymentmode; ?></td>
                                        
                                    </tr>
                                <?php
									$i++;
								}
							}
							else{
							?>
                            	<tr>
                                	<td colspan="5">You have no pin request yet.</td>
                                </tr>
                            <?php
							}
							?>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    <footer id="sticky-footer" class="flex-shrink-0 py-4 bg-dark text-white-50">
            <div class="container text-center" style="padding:12px;">
              <small>Copyright &copy; Kitecareer.com</small>
            </div>
        </footer>

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
