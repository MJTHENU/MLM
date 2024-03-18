<?php
include('php-includes/connect.php');
include('php-includes/check-login.php');
$userid = $_SESSION['userid'];
$capping = 100000;

// Define available sides
$availableSides = array('left', 'right');

// Check if the user clicked on join
if (isset($_GET['join_user'])) {
  // Extract form inputs
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
                //insert into user
                $query = mysqli_query($con, "INSERT INTO user(email, password, mobile, address, account, under_userid, user_type, side) VALUES ('$email', '$hashedPassword', '$mobile', '$address', '$account', '$under_userid', '$user_type', '$side')");

                    if ($query) {
                        // Insert into tree table
                        $query1 = mysqli_query($con, "INSERT INTO tree(user_id) VALUES ('$email')");
                        if ($query1) {  
                                        // Check if either the 'left' or 'right' side is empty in the tree table
                                        $userData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$under_userid'"));
                                
                                          // Initialize counts
                                        $leftCount = 0;
                                        $rightCount = 0;

                                        // Check if 'left' side is available
                                        if (empty($userData['left'])) {
                                            $sideAvailable = 'left';
                                            $leftCount++;
                                        } 
                                        // Check if 'right' side is available
                                        elseif (empty($userData['right'])) {
                                            $sideAvailable = 'right';
                                            $rightCount++;
                                        }else
                                        {
                                          '<script>alert("both side are available");</script>';
                                        }
                               }
                               else{
                              echo '<script>alert("error occur in inserting user in tree table");</script>';
                                }

                                //leftcount
                                if ($sideAvailable== 'left') {
                                  echo '<script>alert("User added successfully. Side available: '.$sideAvailable.'");</script>';
                                  // Continue with updating tree table
                                  $update_query = mysqli_query($con, "UPDATE tree SET `$sideAvailable` = '$email', parent_id = '$under_userid', reference_id = '$userid', leftcount = leftcount + '$leftCount' WHERE user_id = '$under_userid'");


                                  $query = mysqli_query($con, "UPDATE user SET side='$sideAvailable' ,parent_id = '$under_userid', reference_id = '$userid' WHERE email = '$email'");

                                    // Assign reference id from tree table to reference id in wallet table
                                    $query2 = mysqli_query($con, "INSERT INTO wallet (reference_id, user_id, balance, date) 
                                    SELECT reference_id, user_id, 250, NOW() FROM tree 
                                    WHERE user_id = '$under_userid'");

                                    // Update pin status to close
                                    $query6 = mysqli_query($con, "UPDATE pin_list SET status = 'close' WHERE pin = '$pin'");
                         
                                }
                              elseif ($sideAvailable=="right") {
                                  echo '<script>alert("User added successfully. Side available: '.$sideAvailable.'");</script>';
                                  // Continue with updating tree table
                                  $update_query = mysqli_query($con, "UPDATE tree SET `$sideAvailable` = '$email', parent_id = '$under_userid', reference_id = '$userid', rightcount = rightcount + '$rightCount' WHERE user_id = '$under_userid'");
                                 // Update the user table
                                  $query = mysqli_query($con, "UPDATE user SET  side='$sideAvailable' ,parent_id = '$under_userid', reference_id = '$userid'  WHERE email = '$email'");
                                  if($query)
                                  {
                                    $result = mysqli_query($con, "UPDATE user SET plan_id = '1' WHERE parent_id = '$under_userid'");
                                  }
                                  // Check if the plan_id is 1 in the plan table
                                  $plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = 1");

                                  if(mysqli_num_rows($plan_query) > 0) {
                                      // Fetch plan details
                                      $plan_row = mysqli_fetch_assoc($plan_query);
                                      $plan_name = $plan_row['plan_name'];
                                      $plan_level = $plan_row['plan_level'];
                                      
                                      // Update the tree table
                                      $update_tree_query = mysqli_query($con, "UPDATE tree SET level = '$plan_level', plan_name = '$plan_name' WHERE user_id = '$under_userid'");
                                  }

                                 // Fetch the level_cost from the plan table based on the user's level
                                 $level_cost_query = mysqli_query($con, "SELECT level_cost FROM plan WHERE plan_id = 1");

                                 if ($level_cost_query) {
                                     // Fetch the level_cost value
                                     $level_cost_row = mysqli_fetch_assoc($level_cost_query);
                                     $level_cost = $level_cost_row['level_cost'];
                                 } else {
                                     // Handle the case when the level_cost for the user's level is not found
                                     echo "Error: Level cost not found for level $user_level";
                                 }
                                 // Get the parent_id from the tree table based on the user's email
                                 $parentQuery = mysqli_query($con, "SELECT parent_id, reference_id FROM tree WHERE user_id = '$email'");

                              
                                
                                 if ($parentQuery) {
                                     $parentData = mysqli_fetch_assoc($parentQuery);
                                 
                                     // Check if parent_id and reference_id are not NULL
                                     if ($parentData['parent_id'] !== null && $parentData['reference_id'] !== null) {
                                         $parent_id = $parentData['parent_id'];
                                         $reference_id = $parentData['reference_id'];

                                         $query7 = mysqli_query($con, "INSERT INTO income (user_id) VALUES ('$parent_id')");

                                         // Determine the day_bal value based on the side
                                         if ($parent_id === $reference_id) {

                                          $leveldata=mysqli_query($con,"SELECT * from income where user_id = '$parent_id'");
                                             // Insert into income table with parent_id
                                             $query7 = mysqli_query($con, "UPDATE income SET date = NOW(), level_bal = '$level_cost', day_bal = '250', current_bal = '500', total_bal = '500'");

                                         } else {
                                             // Insert into income table with reference_id
                                             $query7 = mysqli_query($con, "UPDATE income SET date = NOW(), level_bal = '$level_cost', day_bal = '250', current_bal = '250', total_bal = '250'");

                                             // Insert into wallet table with user_id
                                             $query8 = mysqli_query($con, "INSERT INTO wallet (user_id, balance, date) VALUES ('$email', 250, NOW())");
                                         }
                                     } else {
                                         echo "Error: parent_id or reference_id is NULL";
                                     }
                                 } else {
                                     echo "Error in fetching parent_id and reference_id from tree table";
                                 }
                                 
                                     
                                  // Update pin status to close
                                 $query6 = mysqli_query($con, "UPDATE pin_list SET status = 'close' WHERE pin = '$pin'");
                                }
                              elseif ($sideAvailable=="bothside") {
                                  echo '<script>alert("Both Side are available.please select any other under userid");</script>';
                                  $delete_query = mysqli_query($con, "DELETE FROM tree WHERE user_id = '$email'");
                                  if($delete_query)
                                  {
                                    $delete_query = mysqli_query($con, "DELETE FROM user WHERE email = '$email'");
                                  echo '<script>window.location.href = "join.php";</script>';
                                  }
                          } }


                    }
              }
          }
     }
}

//level two Start//
function getLevel2($email) {
  global $con;
 // Get the parent_id from the tree table based on the user's email
 $levelQuery = mysqli_query($con, "SELECT level, left, right FROM tree WHERE user_id = '$email'");

 if ($levelQuery) {
     $parentData = mysqli_fetch_assoc($levelQuery);
 
     // Check if parent_id and reference_id are not NULL
     if ($parentData['left'] !== null && $parentData['right'] !== null && $parentData['level']== "1")
      {
         $left = $parentData['left'];
         $right = $parentData['right'];
         $level=$parentData['$level'];

         $result = mysqli_query($con, "UPDATE user SET plan_id = '2' WHERE email = '$email'");

         if($result)
         {
           $result = mysqli_query($con, "UPDATE tree SET left_count = '3', right_count = '3' WHERE parent_id = '$email'");
         }
         // Check if the plan_id is 1 in the plan table
         $plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = 2");

         if(mysqli_num_rows($plan_query) > 0) {
             // Fetch plan details
             $plan_row = mysqli_fetch_assoc($plan_query);
             $plan_name = $plan_row['plan_name'];
             $plan_level = $plan_row['plan_level'];
             
             // Update the tree table
             $update_tree_query = mysqli_query($con, "UPDATE tree SET level = '$plan_level', plan_name = '$plan_name' WHERE user_id = '$email'");
         }
        }
        else{
          echo '<script>alert("child is incomplete the level1");</script>';
        }
      }
    }

//level three Start//
function getLevel3($email) {
  global $con;
 // Get the parent_id from the tree table based on the user's email
 $levelQuery = mysqli_query($con, "SELECT level, left, right FROM tree WHERE user_id = '$email'");

 if ($levelQuery) {
     $parentData = mysqli_fetch_assoc($levelQuery);
 
     // Check if parent_id and reference_id are not NULL
     if ($parentData['left'] !== null && $parentData['right'] !== null && $parentData['level']== "2")
      {
         $left = $parentData['left'];
         $right = $parentData['right'];
         $level=$parentData['$level'];

         $result = mysqli_query($con, "UPDATE user SET plan_id = '3' WHERE email = '$email'");

         if($result)
         {
           $result = mysqli_query($con, "UPDATE tree SET left_count = '7', right_count = '7' WHERE parent_id = '$email'");
         }
         // Check if the plan_id is 1 in the plan table
         $plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = 3");

         if(mysqli_num_rows($plan_query) > 0) {
             // Fetch plan details
             $plan_row = mysqli_fetch_assoc($plan_query);
             $plan_name = $plan_row['plan_name'];
             $plan_level = $plan_row['plan_level'];
             
             // Update the tree table
             $update_tree_query = mysqli_query($con, "UPDATE tree SET level = '$plan_level', plan_name = '$plan_name' WHERE user_id = '$email'");
         }
        }
        else{
          echo '<script>alert("child is incomplete the level2");</script>';
        }
      }
    }

    //level four Start//
function getLevel4($email) {
  global $con;
 // Get the parent_id from the tree table based on the user's email
 $levelQuery = mysqli_query($con, "SELECT level, left, right FROM tree WHERE user_id = '$email'");

 if ($levelQuery) {
     $parentData = mysqli_fetch_assoc($levelQuery);
 
     // Check if parent_id and reference_id are not NULL
     if ($parentData['left'] !== null && $parentData['right'] !== null && $parentData['level']== "3")
      {
         $left = $parentData['left'];
         $right = $parentData['right'];
         $level=$parentData['$level'];

         $result = mysqli_query($con, "UPDATE user SET plan_id = '4' WHERE email = '$email'");

         if($result)
         {
           $result = mysqli_query($con, "UPDATE tree SET left_count = '15', right_count = '15' WHERE parent_id = '$email'");
         }
         // Check if the plan_id is 1 in the plan table
         $plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = 4");

         if(mysqli_num_rows($plan_query) > 0) {
             // Fetch plan details
             $plan_row = mysqli_fetch_assoc($plan_query);
             $plan_name = $plan_row['plan_name'];
             $plan_level = $plan_row['plan_level'];
             
             // Update the tree table
             $update_tree_query = mysqli_query($con, "UPDATE tree SET level = '$plan_level', plan_name = '$plan_name' WHERE user_id = '$email'");
         }
        }
        else{
          echo '<script>alert("child is incomplete the level3");</script>';
        }
      }
    }
    //level five Start//
function getLevel5($email) {
      global $con;
     // Get the parent_id from the tree table based on the user's email
     $levelQuery = mysqli_query($con, "SELECT level, left, right FROM tree WHERE user_id = '$email'");
    
     if ($levelQuery) {
         $parentData = mysqli_fetch_assoc($levelQuery);
     
         // Check if parent_id and reference_id are not NULL
         if ($parentData['left'] !== null && $parentData['right'] !== null && $parentData['level']== "4")
          {
             $left = $parentData['left'];
             $right = $parentData['right'];
             $level=$parentData['$level'];
    
             $result = mysqli_query($con, "UPDATE user SET plan_id = '5' WHERE email = '$email'");
    
             if($result)
             {
               $result = mysqli_query($con, "UPDATE tree SET left_count = '31', right_count = '31' WHERE parent_id = '$email'");
             }
             // Check if the plan_id is 1 in the plan table
             $plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = 5");
    
             if(mysqli_num_rows($plan_query) > 0) {
                 // Fetch plan details
                 $plan_row = mysqli_fetch_assoc($plan_query);
                 $plan_name = $plan_row['plan_name'];
                 $plan_level = $plan_row['plan_level'];
                 
                 // Update the tree table
                 $update_tree_query = mysqli_query($con, "UPDATE tree SET level = '$plan_level', plan_name = '$plan_name' WHERE user_id = '$email'");
             }
            }
            else{
              echo '<script>alert("child is incomplete the level4");</script>';
            }
          }
        }

    //level six Start//
function getLevel6($email) {
      global $con;
     // Get the parent_id from the tree table based on the user's email
     $levelQuery = mysqli_query($con, "SELECT level, left, right FROM tree WHERE user_id = '$email'");
    
     if ($levelQuery) {
         $parentData = mysqli_fetch_assoc($levelQuery);
     
         // Check if parent_id and reference_id are not NULL
         if ($parentData['left'] !== null && $parentData['right'] !== null && $parentData['level']== "5")
          {
             $left = $parentData['left'];
             $right = $parentData['right'];
             $level=$parentData['$level'];
    
             $result = mysqli_query($con, "UPDATE user SET plan_id = '6' WHERE email = '$email'");
    
             if($result)
             {
               $result = mysqli_query($con, "UPDATE tree SET left_count = '63', right_count = '63' WHERE parent_id = '$email'");
             }
             // Check if the plan_id is 1 in the plan table
             $plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = 6");
    
             if(mysqli_num_rows($plan_query) > 0) {
                 // Fetch plan details
                 $plan_row = mysqli_fetch_assoc($plan_query);
                 $plan_name = $plan_row['plan_name'];
                 $plan_level = $plan_row['plan_level'];
                 
                 // Update the tree table
                 $update_tree_query = mysqli_query($con, "UPDATE tree SET level = '$plan_level', plan_name = '$plan_name' WHERE user_id = '$email'");
             }
            }
            else{
              echo '<script>alert("child is incomplete the level5");</script>';
            }
          }
        }
    //level seven Start//
function getLevel7($email) {
      global $con;
     // Get the parent_id from the tree table based on the user's email
     $levelQuery = mysqli_query($con, "SELECT level, left, right FROM tree WHERE user_id = '$email'");
    
     if ($levelQuery) {
         $parentData = mysqli_fetch_assoc($levelQuery);
     
         // Check if parent_id and reference_id are not NULL
         if ($parentData['left'] !== null && $parentData['right'] !== null && $parentData['level']== "6")
          {
             $left = $parentData['left'];
             $right = $parentData['right'];
             $level=$parentData['$level'];
    
             $result = mysqli_query($con, "UPDATE user SET plan_id = '7' WHERE email = '$email'");
    
             if($result)
             {
               $result = mysqli_query($con, "UPDATE tree SET left_count = '127', right_count = '127' WHERE parent_id = '$email'");
             }
             // Check if the plan_id is 1 in the plan table
             $plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = 7");
    
             if(mysqli_num_rows($plan_query) > 0) {
                 // Fetch plan details
                 $plan_row = mysqli_fetch_assoc($plan_query);
                 $plan_name = $plan_row['plan_name'];
                 $plan_level = $plan_row['plan_level'];
                 
                 // Update the tree table
                 $update_tree_query = mysqli_query($con, "UPDATE tree SET level = '$plan_level', plan_name = '$plan_name' WHERE user_id = '$email'");
             }
            }
            else{
              echo '<script>alert("child is incomplete the level1");</script>';
            }
          }
        }
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
      // Check if $availableSides is not empty
      if (!empty($availableSides)) {
          return $availableSides[0]; // Return the first available side
      } else {
          // Handle the case when $availableSides is empty or null
          // For example, you could return a default value or throw an error
          return 'default'; // Example: return a default value
      }
  }
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
	$query = mysqli_query($con,"select * from income where user_id='$userid'");
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
	$query = mysqli_query($con,"select * from tree where user_id='$userid'");
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
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Mlml Website  - Join</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">

<!--joinuser CSS -->
<link href=".\admincss/joinuser.css" rel="stylesheet">
<!-- Bootstrap Core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- MetisMenu CSS -->
<link href="vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="dist/css/sb-admin-2.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!-- Join CSS -->
<link href="vendor/css/join.css" rel="stylesheet">
 

</head>


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