<?php
include('php-includes/connect.php');
include('php-includes/check-login.php');
$userid = $_SESSION['userid'];
$search = $userid;
?>
<?php
function tree_data($userid){
global $con;
$data = array();
$query = mysqli_query($con,"select * from tree where user_id='$userid'");
$result = mysqli_fetch_array($query);
$data['left'] = isset($result['left'])? $result['left']: '';
$data['right'] = isset($result['right'])?$result['right']:'';
$data['leftcount'] = isset($result['leftcount']) ? $result['leftcount']: '';
$data['rightcount'] = isset($result['rightcount'])? $result['rightcount']: '';
return $data;
}
?>
<?php 
if(isset($_GET['search-id'])){
$search_id = mysqli_real_escape_string($con,$_GET['search-id']);
if($search_id!=""){
$query_check = mysqli_query($con,"select * from user where email='$search_id'");
if(mysqli_num_rows($query_check)>0){
$search = $search_id;
}
else{
echo '<script>alert("Access Denied");window.location.assign("tree.php");</script>';
}
}
else{
echo '<script>alert("Access Denied");window.location.assign("tree.php");</script>';
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
<title>Mlml Website - Tree</title>
<!-- Bootstrap Core CSS -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
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
 background: url(https://img.freepik.com/free-vector/gradient-liquid-abstract-background_52683-60469.jpg)
    no-repeat center center;
  background-size: cover;
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
<h1 class="page-header" style="color:white">Tree</h1>
</div>
<!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
<div class="col-lg-12">
<div class="table-responsive">
<table class="table" align="center" border="0" style="text-align:center ;color:white">
<tr height="150">
<?php
$data = tree_data($search);
?>
<td style="color:white"><?php echo $data['leftcount'] ?> Left Count</td>
<td colspan="2"><i class="fa fa-user fa-4x"  style="text-align:center ;color:#E4A0F7"></i><p style="color:white"><?php echo $search; ?></p></td>
<td style="color:white"><?php echo $data['rightcount'] ?>Right Count</td>
</tr>
<tr height="150">
<?php
$first_left_user = $data['left'];
$first_right_user = $data['right'];
?>
<?php 
if($first_left_user!=""){
?>
<td colspan="2"><a href="tree.php?search-id=<?php echo $first_left_user ?>"><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7"></i><p style="color:white"><?php echo $first_left_user ?></p></a></td>
<?php 
}
else{
?>
<td colspan="2"><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7" ></i><p style="color:white"><?php echo $first_left_user ?></p></td>
<?php
}
?>
<?php 
if($first_right_user!=""){
?>
<td colspan="2"><a href="tree.php?search-id=<?php echo $first_right_user ?>"><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7"></i><p style="color:white"><?php echo $first_right_user ?></p></a></td>
<?php 
}
else{
?>
<td colspan="2"><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7"></i><p style="color:white"><?php echo $first_right_user ?></p></td>
<?php
}
?>
</tr>
<tr height="150">
<?php 
$data_first_left_user = tree_data($first_left_user);
$second_left_user = $data_first_left_user['left'];
$second_right_user = $data_first_left_user['right'];

$data_first_right_user = tree_data($first_right_user);
$third_left_user = $data_first_right_user['left'];
$third_right_user = isset($data_first_right_user['right'])?$data_first_right_user['right']:'';
?>
<?php 
if($second_left_user!=""){
?>
<td><a href="tree.php?search-id=<?php echo $second_left_user ?>"><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7"></i><p style="color:white"><?php echo $second_left_user ?></p></a></td>
<?php 
}
else{
?>
<td><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7"></i></td>
<?php
}
?>
<?php 
if($second_right_user!=""){
?>
<td><a href="tree.php?search-id=<?php echo $second_right_user ?>"><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7"></i><p style="color:white"><?php echo $second_right_user ?></p></a></td>
<?php 
}
else{
?>
<td><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7"></i></td>
<?php
}
?>
<?php 
if($third_left_user!=""){
?>
<td><a href="tree.php?search-id=<?php echo $third_left_user ?>"><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7"></i><p style="color:white"><?php echo $third_left_user ?></p></a></td>
<?php 
}
else{
?>
<td><i class="fa fa-user fa-4x" style="text-align:center ;color:#E4A0F7"></i></td>
<?php
}
?>
<?php 
if($third_right_user!=""){
?>
<td><a href="tree.php?search-id=<?php echo $third_right_user ?>"><i class="fa fa-user fa-4x" style="text-align:center ;color:blue"></i><p style="color:#E4A0F7"><?php echo $third_right_user ?></p></a></td>
<?php 
}
else{
?>
<td style="color:white"><i class="fa fa-user fa-4x" style="color:#E4A0F7"></i></td>
<?php
}
?>
</tr>
</table>
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