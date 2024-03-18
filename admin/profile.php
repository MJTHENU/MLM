<?php
include('php-includes/connect.php');
include('php-includes/check-login.php');
$userid = $_SESSION['userid'];
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mlml Website  - Profile</title>

    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

<style>
    .user-bg .overlay-box {
    opacity: 0.9;
    position: absolute;
    top: 0px;
    left: 0px;
    right: 0px;
    height: 100%;
    text-align: center;
    
}
.user-bg .overlay-box .user-content {
    margin-top: 30px;
    padding: 15px;
}
a {
    color: rgb(51, 122, 183);
    text-decoration: none;
}
.text-white {
    color: rgb(255, 255, 255);
}
img {
    vertical-align: middle;
}
.user-bg {
    height: 260px;
    position: relative;
    margin: -15px;
    overflow: hidden;
}
.white-box {
    margin-bottom: 20px;
    background: rgb(255, 255, 255);
    padding: 15px;
}
.user-btm-box {
    padding: 40px 0 10px;
    clear: both;
    overflow: hidden;
}


#page-wrapper {
  font: 1em/1.618 Inter, sans-serif;
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  padding: 0px;
  color: #224;
  background: url(https://source.unsplash.com/E8Ufcyxz514/2400x1823) center/cover no-repeat fixed;
}



.card {
  max-width: 300px;
  min-height: 620px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  max-width: 500px;
  height: 100px;
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
  height: 100px;
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
  height: 200px;
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
                        <h1 class="page-header">Profile</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
          <div class="col-md-4 col-xs-12">
           <div class="card">
              <div class="user-bg">
                
                <div class="overlay-box">
                  <div class="user-content">
                    <a href="javascript:void(0)"><img src="./assests//profile.jpg" width="50%" class="thumb-lg img-circle" alt="img"></a>
          
                    <h4 class="text-black">User Name</h4>
                    <h5 class="text-black">info@myadmin.com</h5>
                  </div>
                </div>
              </div>
              <div class="user-btm-box">
              <div class="row">
                	 <?php
						$query = mysqli_query($con,"select * from income where userid='$userid'");
						$result = mysqli_fetch_array($query);
					?>
                	<div class="col-lg-6">
                    	<div class="panel panel-info">
                        	<div class="panel-heading">
                            	<h4 class="panel-title">Today's Income</h4>
                            </div>
                            <div class="panel-body">
                            	<?php 
								echo $result['day_bal']
								?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                    	<div class="panel panel-success">
                        	<div class="panel-heading">
                            	<h4 class="panel-title">Current Income</h4>
                            </div>
                            <div class="panel-body">
                            	<?php 
								echo $result['current_bal']
								?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                    	<div class="panel panel-danger">
                        	<div class="panel-heading">
                            	<h4 class="panel-title">Total Income</h4>
                            </div>
                            <div class="panel-body">
                            	<?php 
								echo $result['total_bal']
								?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                    	<div class="panel panel-warning">
                        	<div class="panel-heading">
                            	<h4 class="panel-title">Available Pin</h4>
                            </div>
                            <div class="panel-body">
                            	<?php 
								echo  mysqli_num_rows(mysqli_query($con,"select * from pin_list where userid='$userid' and status='open'"));
								?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
</div>
<div class="card">
          <div class="col-md-8 col-xs-12">
            
              <form class="form-horizontal form-material">
                <div class="form-group">
                  <label class="col-md-12">Full Name</label>
                  <div class="col-md-12">
                    <input type="text" placeholder="Johnathan Doe" class="form-control form-control-line">
                  </div>
                </div>
                <div class="form-group">
                  <label for="example-email" class="col-md-12">Email</label>
                  <div class="col-md-12">
                    <input type="email" placeholder="johnathan@admin.com" class="form-control form-control-line" name="example-email" id="example-email">
                  </div>
                </div>
               
                <div class="form-group">
                  <label class="col-md-12">Parent ID</label>
                  <div class="col-md-12">
                    <input type="text" placeholder="Parent ID" class="form-control form-control-line">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-12">Referal ID</label>
                  <div class="col-md-12">
                  <input type="text" placeholder="Referal ID" class="form-control form-control-line">
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-12">Account</label>
                  <div class="col-md-12">
                    <input type="text" placeholder="User" class="form-control form-control-line" disabled>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-12">Address</label>
                  <div class="col-md-12">
                  <textarea id="" class="form-control form-control-line" name="" type="text" rows="4" cols="50"></textarea>                  </div>
                </div>
                <div class="form-group">
                  <div class="col-sm-12">
                    <button<a href="update-profile.php" class="btn btn-success">Update Profile</button> 
                  </div>
                </div>
              </form>
            </div>
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
