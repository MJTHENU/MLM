<?php
include('php-includes/connect.php');
include('php-includes/check-login.php');
$userid = $_SESSION['userid'];
$capping =100000;
?>
<?php

// Check if the user clicked on join
if (isset($_GET['join_user'])) {
    $pin = mysqli_real_escape_string($con, $_GET['pin']);
    $email = mysqli_real_escape_string($con, $_GET['email']);
    $mobile = mysqli_real_escape_string($con, $_GET['mobile']);
    $address = mysqli_real_escape_string($con, $_GET['address']);
    $account = "user";
    $under_userid = mysqli_real_escape_string($con, $_GET['under_userid']);
    $referral = mysqli_real_escape_string($con, $_GET['referral']); // Get the selected referral option
    
    // Generate a hashed password
    $hashedPassword = md5("123456");
    // Set user type based on referral
    $user_type = ($referral == 'direct') ? 'direct' : 'indirect';
  

    // Define available sides
    $availableSides = array('left', 'right');

    // Check if all required fields are filled
    if ($pin != '' && $email != '' && $mobile != '' && $address != '' && $account != '' && $under_userid != '') {
        // Check if the pin is valid
        if (pin_check($pin)) {
            // Check if the email is available
            if (email_check($email)) {
                // Check if the under userid is valid
                if (!email_check($under_userid)) {
                    // Check if the side is available for the under userid
                    $side = getNextAvailableSide($con, $under_userid, $availableSides);
                    if ($side !== false) {
                        // Flag to indicate successful join
                        $flag = 1;
                        if ($side !== false) {
                          // Flag to indicate successful join
                          $flag = 1;
                           // Assign user type and increment indirect count if needed
                           $user_type = '';
                           if ($_GET['referral'] == 'direct') {
                             $user_type = 'direct';
                            } elseif ($_GET['referral'] == 'indirect') {
                           $user_type = 'indirect';
                            }else {
                              // Handle the case where the query fails
                              echo "Error executing query: " . mysqli_error($con);
                          }
                      }

                        // Perform the join operation
                        // Insert into user table
                        $query = mysqli_query($con, "INSERT INTO user(email, password, mobile, address, account, under_userid, side) VALUES ('$email', '$password', '$mobile', '$address', '$account', '$under_userid', '$side')");

                        // Insert into tree table
                        $query = mysqli_query($con, "INSERT INTO tree(userid) VALUES ('$email')");

                        // Update tree table with side, email, parent_id, and reference_id
                        //$query = mysqli_query($con, "UPDATE tree SET $side = '$email', parent_id = '$under_userid', reference_id = '$userid' WHERE userid = '$under_userid'");
                        $query = mysqli_query($con, "UPDATE tree SET `$side` = '$email', parent_id = '$under_userid', reference_id = '$userid' WHERE userid = '$under_userid'");

                        // Update pin status to close
                        $query = mysqli_query($con, "UPDATE pin_list SET status = 'close' WHERE pin = '$pin'");

                        // Insert into income
                        $query = mysqli_query($con, "INSERT INTO income (userid) VALUES ('$email')");

                        echo '<script>alert("User added successfully.");</script>';
                    } else {
                        echo '<script>alert("Both sides are not available. Unable to join user.");</script>';
                    }
                } else {
                    // Invalid under userid
                    echo '<script>alert("Invalid Under userid.");</script>';
                }
            } else {
                // Email already exists
                echo '<script>alert("This user id already available.");</script>';
            }
        } else {
            // Invalid pin
            echo '<script>alert("Invalid pin");</script>';
        }
    } else {
        // Fields are not filled
        echo '<script>alert("Please fill all the fields.");</script>';
    }
}



?><!--/join user-->
<?php 
//functions
// Function to get the next available side for the given under_userid
function getNextAvailableSide($con, $under_userid, $availableSides) {
    // Fetch the existing side for the under_userid
    $query = mysqli_query($con, "SELECT side FROM user WHERE email = '$under_userid'");
    $result = mysqli_fetch_assoc($query);

    // Check if the side exists
    if ($result && isset($result['side'])) {
        // Alternate the side
        $currentSide = $result['side'];
        if ($currentSide === 'left') {
            return 'right'; // If the current side is left, assign right
        } else {
            return 'left'; // If the current side is right or empty, assign left
        }
    } else {
        // Default to the first available side if no side is found
        return $availableSides[0];
    }
}

function getLevel1($startId) {
  global $con;
  $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE userid = '$startId'");
  //$count = mysqli_num_rows($directDescendents);
  while($row = mysqli_fetch_array($directDescendents))
  {
      //$count += getLevel1($row['email']);
      $leftNode = $row['left'];
      $rightNode = $row['right'];
    if($leftNode!='' && $rightNode!='') {
      return "true";
    } else {
      return "false";
    }
  }
}
function getLevel2($startId) {
  global $con;
  $tempLeft = $tempRight = 'false';
  $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE userid = '$startId'"); //annam
  //$count = mysqli_num_rows($directDescendents);
  while($row = mysqli_fetch_array($directDescendents))
  {
      //$count += getLevel1($row['email']);
      $leftNode = $row['left']; // User1
      $rightNode = $row['right']; //User2
    if($leftNode!='' && $rightNode!='') {
      $tempLeft = getLevel1($row['left']);
      $tempRight = getLevel1($row['right']);
    }
  }
  return $tempLeft."-".$tempRight;
}
function getLevel3($startId) {
  global $con;
  $tempLeft = $tempRight = 'false';
  $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE userid = '$startId'"); //annam
  //$count = mysqli_num_rows($directDescendents);
  while($row = mysqli_fetch_array($directDescendents))
  {
      //$count += getLevel1($row['email']);
      $leftNode = $row['left']; // User1
      $rightNode = $row['right']; //User2
    if($leftNode!='' && $rightNode!='') {
      $tempLeft = getLevel2($row['left']);
      $tempRight = getLevel2($row['right']);
    }
  }
  return $tempLeft."-".$tempRight;
}
function checkResults($levelResult2)
{
  if(strpos(strtolower($levelResult2), "false") !== false){
      return "false";        
  } else {
      return "true";        
  }
}
function getNodeCounts()
{
  $arr = [];
  $i=7;
  while($i<=10000){
    $incr = (($i*2)+1);
    $arr[] = $incr;
    //echo "-----".(($i*2)+1);
    $i=$incr;
  }
  return $arr;
}
function pin_check($pin){
	global $con,$userid;
	
	$query =mysqli_query($con,"select * from pin_list where pin='$pin' and userid='$userid' and status='open'");
	if(mysqli_num_rows($query)>0){
		return true;
	}
	else{
		return false;
	}
}
function email_check($email){
	global $con;
	
	$query =mysqli_query($con,"select * from user where email='$email'");
	if(mysqli_num_rows($query)>0){
		return false;
	}
	else{
		return true;
	}
}
function side_check($email,$side){
	global $con;
	
	$query =mysqli_query($con,"select * from tree where userid='$email'");
	$result = mysqli_fetch_array($query);
	$side_value = $result[$side];
	if($side_value==''){
		return true;
	}
	else{
		return false;
	}
}
function income($userid){
	global $con;
	$data = array();
	$query = mysqli_query($con,"select * from income where userid='$userid'");
	$result = mysqli_fetch_array($query);
if ($result !== null && isset($result['day_bal'])) {
    $data['day_bal'] = $result['day_bal'];
} else {
    // Handle the case when 'day_bal' is not set or $result is null
    // You can set a default value or handle it in another appropriate way
}

if ($result !== null && isset($result['current_bal'])) {
    $data['current_bal'] = $result['current_bal'];
} else {
    // Handle the case when 'current_bal' is not set or $result is null
    // You can set a default value or handle it in another appropriate way
}

if ($result !== null && isset($result['total_bal'])) {
    $data['total_bal'] = $result['total_bal'];
} else {
    // Handle the case when 'total_bal' is not set or $result is null
    // You can set a default value or handle it in another appropriate way
}

	
	return $data;
}
function tree($userid){
	global $con;
	$data = array();
	$query = mysqli_query($con,"select * from tree where userid='$userid'");
	$result = mysqli_fetch_array($query);
	$data['left'] = $result['left'];
	$data['right'] = $result['right'];
	$data['leftcount'] = $result['leftcount'];
	$data['rightcount'] = $result['rightcount'];
	
	return $data;
}
function getUnderId($userid){
	global $con;
	$query = mysqli_query($con,"select * from user where email='$userid'");
	$result = mysqli_fetch_array($query);
	return $result['under_userid'];
}
function getUnderIdPlace($userid){
	global $con;
	$query = mysqli_query($con,"select * from user where email='$userid'");
	$result = mysqli_fetch_array($query);
	return $result['side'];
}
/*echo "-----".display_children('annathai.r@gmail.com',0);
function display_children($parent, $level) {
  global $con;
  $count = 0;
  $result = mysqli_query($con,"select * from tree where userid='$parent'");
  while ($row = mysqli_fetch_array($result))
  {
         $var = str_repeat(' ',$level).$row['userid']."\n";

                 //echo $var  after remove comment check tree

                 // i call function in while loop until count all user_id 

         $count += 1 + display_children($row['userid'], $level+1);

  }
  return $count; // it will return all user_id count under parent_id
}*/ 
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mlml Website  - Join</title>


    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      .table-responsive a{
        color: white !important; 
      }
      </style>
 

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
  min-height: 600px;
  display: flex;
 
  flex-direction: column;
  justify-content: space-between;
  max-width: 500px;
  height: 300px;
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
  text-color: white;
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
<?php include('php-includes/menu.php'); ?>
<div id="page-wrapper">

    <div id="wrapper">

        <!-- Navigation -->
      

        <!-- Page Content -->
        
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
					
                        <h1 class="page-header"style="color:white">Join</h1>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
				<div class="card">
                <div class="row">
				
                	<div class="col-lg-12">
                    	<form method="get">
						
                            <div class="form-group">
							
                                <label>Pin</label>
                                <input type="text" name="pin" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Mobile</label>
                                <input type="text" name="mobile" class="form-control" value="0987654321" required>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control" value="Street 1" required>
                            </div>
                            <div class="form-group">
                                <label>Account</label>
                                <input type="text" name="account" class="form-control" value="Admin" required disabled>
                            </div>
                            <div class="form-group">
                                <label>Under Userid</label>
                                <input type="text" name="under_userid" id="under_userid" class="form-control" onmouseup="checkReferral()" required>
                            </div>
                            <div class="form-group" id="referral_group" style="display: none;">
                                <label>Referral</label><br>
                                <input type="radio" name="referral" id="direct_referral" value="direct"> Direct
                                <input type="radio" name="referral" id="indirect_referral" value="indirect"> Indirect
                            </div>

                            <script>
                            function checkReferral() {
                                var under_userid = document.getElementById("under_userid").value;
                                var referral_group = document.getElementById("referral_group");
                                var direct_referral = document.getElementById("direct_referral");
                                var indirect_referral = document.getElementById("indirect_referral");

                                if (under_userid.trim() === "") {
                                    referral_group.style.display = "none";
                                } else {
                                    referral_group.style.display = "block";
                                    if (under_userid === <?php echo json_encode($userid); ?>) {
                                        direct_referral.checked = true;
                                    } else {
                                        indirect_referral.checked = true;
                                    }
                                }
                            }
                            </script> 
                            
                            <div class="form-group">
                        	<input type="submit" name="join_user" class="btn btn-primary" value="Join">
                        </div>
                        </form>
                    </div>
                </div><!--/.row-->
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
