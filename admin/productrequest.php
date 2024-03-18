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

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

 

</head>
<style>
#page-wrapper {
  font: 1em/1.618 Inter, sans-serif;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: 0px;
  color: #224;
background: url('https://img.freepik.com/free-vector/gradient-glassmorphism-background_23-2149433263.jpg?w=740&t=st=1687941861~exp=1687942461~hmac=644ec1d788300c49933f0a2c3e0d344af72d1b991f9b151da8b7e2c667dd3417')
    no-repeat center center;
  background-size: cover;
}

.card {
  max-width: 300px;
  min-height: 300px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  max-width: 500px;
  height: 400px;
  padding: 35px;
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 20px;
  background-color: rgba(255, 255, 255, 0.45);
  box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(15px);
}

.card1 {
  max-width: 50px;
  min-height: 50px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  max-width: 200px;
  height: 400px;
  padding: 35px;
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 20px;
  background-color: rgba(255, 255, 255, 0.45);
  box-shadow: 0 0 10px 1px rgba(0, 0, 0, 0.25);
  backdrop-filter: blur(15px);
}

.card-footer {
  font-size: 0.65em;
  color: #446;
}




.infocardContainer * {
  font-family: 'Fira Sans Condensed', sans-serif;
  font-weight: 300;
}
h2 {
  font-weight: 600; font-style: italic; font-family: "Fira Sans Condensed", sans-serif;
}
header {
  height: 2em;
  background-color: #111122;
  margin: 0 0 0 0;
  padding: auto;
  font-size: 2em;
  text-align: center;
  line-height: 2em;
  color: white;
}
a {
  text-decoration: none;
}
a:visited {
  color: #555566;
}
a:hover {
  text-decoration: underline;
}
.infocardContainer {
  display: flex;
  height: 300px;
  width: 200px;
  border-radius: 100px;
  background: rgb(0,159,255);
  background: linear-gradient(121deg, rgba(255,255,255,0) 13%, rgba(0,159,255,1) 100%);
  transition: all 500ms ease-in;
  transition-delay: 1s;
  margin: auto;
  margin-top: 100px;
  --margin-top: 100px;
}
.infocardContainer:hover {
  width: 500px;
  border-radius: 100px 10px 100px 100px;
  transition: all 1s ease-out;
}

.infocardContainer div {
  
  flex-shrink: 1;
  width: 100%;
  --background-color: green;
}
.infocardContainer div * {
  display: flex;
  --flex: inherit;
  overflow: hidden;
  text-overflow: hidden;
  --background-color: yellow;
  color: white;
  white-space: nowrap;
  width: 0;
  height: auto;
  transition: all 450ms ease-in;
  transition-delay: 1s;
}
.infocardContainer:hover div *{
  --background-color: purple;
  display: flex;
  visibility: visible;
  transition: all 1s ease-out;
  transition-delay: 500ms;
  width: 100%;
  height: auto;
}

.infocardContainer #main, .infocardContainer #main img{
  --background-color: red;
  height: 200px;
  width: 200px;
  padding-right: 10px;
  border-radius: 100%;
  flex-shrink: 0;
  object-fit: cover;
}
.infocardContainer #main img{
  height: 180px;
  width: 180px;
  transition: none;
  display: float;
  position: relative;
  border: 10px solid white;
  margin: 0 0 0 0; padding: 0 0 0 0;
}
.infocardContainer #textbois {
  position: relative;
}
.infocardContainer #textbois #hotlinks {
  max-width: 60%;
  max-height: 30px;
  
  --background-color: white;
  position:absolute;
  bottom: 5px;
  display: flex;
  justify-content: space-between;
  border-radius: 13px;
}
.infocardContainer #textbois #hotlinks * {
  background-color: white;
  max-width: 30px;
  --margin: 0 1px 0 1px;
  border-radius: 25px;
}

</style>
<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include('php-includes/menu.php'); ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                
                    <div class="col-lg-12">
                        <h1 class="page-header" style="color:white">Product Request</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="card">
                <div class="row">
                	
                    
                    	<form method="get">	
                        	<div class="form-group">
                            	<label>Amount</label>
                                <input type="text" name="amount" class="form-control" required placeholder="type the wallet amount"> 
                                
                            </div>

                            <div class="form-group">
                                    <label for="#" class="select">Product Name</label>
                                    <select name="productname" id="productname" class="form-control">
                                        <option value="">Please Select ex 01 t-shirt</option>
                                        <option value="01">01</option>
                                        <option value="02">02</option> 
                                        <option value="03">03</option> 
                                        <option value="04">04</option> 
                                        <option value="05">05</option> 
                                        <option value="06">06</option> 
                                        <option value="07">07</option> 
                                        <option value="08">08</option> 
                                        <option value="09">09</option> 
                                        <option value="10">10</option> 
                                        <option value="11">11</option> 
                                        <option value="12">12</option> 
                                        <option value="13">13</option> 
                                        <option value="14">14</option> 
                                        <option value="15">15</option> 
                                        <option value="16">16</option> 
                                        <option value="17">17</option> 
                                        <option value="18">18</option> 
                                        <option value="19">19</option> 
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option> 
                                        <option value="23">23</option> 
                                        <option value="24">24</option> 
                                    </select>
                                    	<label>Quantity</label>
                                <input type="number" name="amount" class="form-control" required placeholder="type the Quantity"> 
                                    
                                </div>
                                <div class="form-group">
                                    <label for="Gender" class="select">Payment Mode</label>
                                    <select name="payment_mode" id="payment" class="form-control">
                                        <option value="">Please Select</option>
                                        <option value="Net Banking">NetBanking</option>
                                        <option value="Cash">Cash</option> 
                                        <option value="Product Buy">Product Buy</option> 
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
                           
                            	<th scope="col">S.n.</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Payment Mode </th>
                                <th scope="col">Product Name </th>
                                <th scope="col">Quantity </th>
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
                 
                  $productname = $row['productname'];
                   $Quatity = $row['Quantity'];
                                    
								?>
                                 
                                	<tr>
                                    	<td scope="row"><?php echo $i; ?></td>
                                        <td scope="row"><?php echo $amount; ?></td>
                                        <td scope="row"><?php echo $date; ?></td>
                                        <td scope="row"><?php echo $status; ?></td>
                                        <td scope="row"><?php echo $paymentmode; ?></td>
                                        <td scope="row"><?php echo $productname; ?></td>
                                        <td scope="row"><?php echo $Quantity; ?></td>
                                        
                                    </tr>
                                <?php
									$i++;
								}
							}
							else{
							?>
                            	<tr>
                                	<td colspan="5">You have no product  request yet.</td>
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
