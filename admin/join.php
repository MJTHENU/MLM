<?php
include('php-includes/connect.php');
include('php-includes/check-login.php');

$userid = $_SESSION['userid'];
$capping = 100000;

// Define available sides
$availableSides = array('left', 'right');

// Check if the user clicked on join
if (isset($_GET['join_user'])) {
    // Sanitize form inputs
    $pin = mysqli_real_escape_string($con, $_GET['pin']);
    $email = mysqli_real_escape_string($con, $_GET['email']);
    $mobile = mysqli_real_escape_string($con, $_GET['mobile']);
    $address = mysqli_real_escape_string($con, $_GET['address']);
    $account = "user";
    $under_userid = mysqli_real_escape_string($con, $_GET['under_userid']);
    $referral = mysqli_real_escape_string($con, $_GET['referral']);

    // Hash the password
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
                        $user_type = ($_GET['referral'] == 'direct') ? 'direct' : 'indirect';

                        // Insert into user table
                        $query = mysqli_query($con, "INSERT INTO user(email, password, mobile, address, account, under_userid, user_type, side) VALUES ('$email', '$hashedPassword', '$mobile', '$address', '$account', '$under_userid', '$user_type', '$side')");

                        if ($query) {
                            // Insert into tree table
                            $query1 = mysqli_query($con, "INSERT INTO tree(user_id) VALUES ('$email')");

                            if ($query1) {
                                $userData = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$under_userid'"));

                                if (empty($userData['left'])) {
                                    $sideAvailable = 'left';
                                } elseif (empty($userData['right'])) {
                                    $sideAvailable = 'right';
                                } else {
                                    echo '<script>alert("both side are available");</script>';
                                }
                            } else {
                                echo '<script>alert("error occur in inserting user in tree table");</script>';
                            }

                            // Handle different cases based on the side available
                            if ($sideAvailable == 'left') {
                                // Continue with updating tree table
                                $update_query = mysqli_query($con, "UPDATE tree SET $sideAvailable = '$email', parent_id = '$under_userid', reference_id = '$userid', leftcount = leftcount + 1 WHERE user_id = '$under_userid'");

                                $query = mysqli_query($con, "UPDATE user SET side='$sideAvailable' ,parent_id = '$under_userid', reference_id = '$userid' WHERE email = '$email'");

                                day_bal($under_userid, $email);

                                // Update pin status to close
                                $query6 = mysqli_query($con, "UPDATE pin_list SET status = 'close' WHERE pin = '$pin'");
                            } elseif ($sideAvailable == "right") {
                                // Continue with updating tree table
                                $update_query = mysqli_query($con, "UPDATE tree SET $sideAvailable = '$email', parent_id = '$under_userid', reference_id = '$userid', rightcount = rightcount + 1, level = level + 1 WHERE user_id = '$under_userid'");

                                // Update the user table
                                $query = mysqli_query($con, "UPDATE user SET  side='$sideAvailable' ,parent_id = '$under_userid', reference_id = '$userid'  WHERE email = '$email'");

                                // Level upgrade function calling
                                $userLevelQuery = mysqli_query($con, "SELECT level FROM tree WHERE user_id = '$under_userid'");
                                $userLevelResult = mysqli_fetch_assoc($userLevelQuery);
                                $userLevel = $userLevelResult['level'];

                                if ($userLevelQuery) {
                                    getLevel1($under_userid);
                                }

                                // Update pin status to close
                                $query6 = mysqli_query($con, "UPDATE pin_list SET status = 'close' WHERE pin = '$pin'");
                            } elseif ($sideAvailable == "bothside") {
                                echo '<script>alert("Both Side are available.please select any other under userid");</script>';
                                $delete_query = mysqli_query($con, "DELETE FROM tree WHERE user_id = '$email'");
                                if ($delete_query) {
                                    $delete_query = mysqli_query($con, "DELETE FROM user WHERE email = '$email'");
                                    echo '<script>window.location.href = "join.php";</script>';
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}


//function

function getlevel1($under_userid)
{
   // echo '<script>alert("level 1 function start");</script>';
    global $con;
    $tempLeft = $tempRight = 'false'; 
    $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE user_id = '$under_userid'");

    while($row = mysqli_fetch_array($directDescendents))
    {
        //$count += getLevel1($row['email']);
        $leftNode = $row['left']; // User1
        $rightNode = $row['right']; //User2
        $level = $row['level']; 
        

        if($level === '1')
        {   
            $result = mysqli_query($con, "UPDATE user SET plan_id = plan_id + 1 WHERE email = '$under_userid'");
            // $row=mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
            // $email= mysqli_fetch_assoc($row);
            // $email = $email['email'];
             day_bal($under_userid,$rightNode);
             setlevel($under_userid,$rightNode);
                     }

        $row=mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
        $parent= mysqli_fetch_assoc($row);
        $parentnode = $parent['parent_id'];
        if($parentnode != '')
        {
            getlevel2($parentnode);
        }
    }
}



function getlevel2($under_userid)
{
   // echo '<script>alert("level 2 function start for '.$under_userid.'");</script>';

    global $con;
    $tempLeft = $tempRight = 'false'; 
    $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE user_id = '$under_userid'");

    while($row = mysqli_fetch_array($directDescendents))
    {
        //$count += getLevel1($row['email']);
        $leftNode = $row['left']; // User1
        $rightNode = $row['right']; //User2
        $level = $row['level']; 


        $row2_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$leftNode'");
        if ($row2_query) {
            $row2 = mysqli_fetch_assoc($row2_query);
            if ($row2) {
                $leftlevel = $row2['level'];
        
                // Fetching data for $row3
                $row3_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$rightNode'");
                if ($row3_query) {
                    $row3 = mysqli_fetch_assoc($row3_query);
                    if ($row3) {
                        $rightlevel = $row3['level'];
        
                        if ($leftlevel === '1' && $rightlevel === '1') {
                            $result = mysqli_query($con, "UPDATE user SET plan_id = plan_id + 1 WHERE email = '$under_userid'");
                            $update_query = mysqli_query($con, "UPDATE tree SET  level=level+'1', rightcount=rightcount+'1' WHERE user_id = '$under_userid'");
                            echo '<script>alert("set the level  function  for '.$under_userid.' level upgrade ");</script>';

                            $row=mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
                            $email= mysqli_fetch_assoc($row);
                            $email = $email['email'];
                            setlevel($under_userid,$email);                          
                                } else {
                           
                        }
                        
                    } else {
                       // echo "Error: No data found for rightnode.";
                    }
                } else {
                   // echo "Error fetching data for rightnode.";
                }
            } else {
               // echo "Error: No data found for leftnode.";
            }
        } else {
          //  echo "Error fetching data for leftnode.";
        }
        

        $row=mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
        $parent= mysqli_fetch_assoc($row);
        $parentnode = $parent['parent_id'];
        if($parentnode != '')
        {
            getlevel3($parentnode);
        }
    }

}


function getlevel3($under_userid)
{
   // echo '<script>alert("level 3 function start for ' . $under_userid . '");</script>';

    global $con;
    $tempLeft = $tempRight = 'false'; 
    $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE user_id = '$under_userid'");

    while($row = mysqli_fetch_array($directDescendents))
    {
        $leftNode = $row['left']; // User1
        $rightNode = $row['right']; //User2
        $level = $row['level']; 


        $row2_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$leftNode'");
        if ($row2_query) {
            $row2 = mysqli_fetch_assoc($row2_query);
            if ($row2) {
                $leftlevel = $row2['level'];
        
                // Fetching data for $row3
                $row3_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$rightNode'");
                if ($row3_query) {
                    $row3 = mysqli_fetch_assoc($row3_query);
                    if ($row3) {
                        $rightlevel = $row3['level'];
                        if ($leftlevel === '2' && $rightlevel === '2') {
                            $result = mysqli_query($con, "UPDATE user SET plan_id = plan_id + 1 WHERE email = '$under_userid'");
                            $update_query = mysqli_query($con, "UPDATE tree SET  level=level+'1', rightcount=rightcount+'1' WHERE user_id = '$under_userid'");
                           // echo '<script>alert("level 2 function  '.$under_userid.' level upgrade ");</script>';
                       
                           $row=mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
                           $email= mysqli_fetch_assoc($row);
                           $email = $email['email'];
                           setlevel($under_userid,$email);  
                        } else {
                           
                        }
                        
                    } else {
                       /// echo "Error: No data found for rightnode.";
                    }
                } else {
                   // echo "Error fetching data for rightnode.";
                }
            } else {
                //echo "Error: No data found for leftnode.";
            }
        } else {
            //echo "Error fetching data for leftnode.";
        }
    }


}


function getlevel4($under_userid)
{
  //  echo '<script>alert("level 3 function start for ' . $under_userid . '");</script>';

    global $con;
    $tempLeft = $tempRight = 'false'; 
    $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE user_id = '$under_userid'");

    while($row = mysqli_fetch_array($directDescendents))
    {
        $leftNode = $row['left']; // User1
        $rightNode = $row['right']; //User2
        $level = $row['level']; 


        $row2_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$leftNode'");
        if ($row2_query) {
            $row2 = mysqli_fetch_assoc($row2_query);
            if ($row2) {
                $leftlevel = $row2['level'];
        
                // Fetching data for $row3
                $row3_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$rightNode'");
                if ($row3_query) {
                    $row3 = mysqli_fetch_assoc($row3_query);
                    if ($row3) {
                        $rightlevel = $row3['level'];
                        if ($leftlevel === '3' && $rightlevel === '3') {
                            $result = mysqli_query($con, "UPDATE user SET plan_id = plan_id + 1 WHERE email = '$under_userid'");
                            $update_query = mysqli_query($con, "UPDATE tree SET  level=level+'1', rightcount=rightcount+'1' WHERE user_id = '$under_userid'");
                            //echo '<script>alert("level 2 function  '.$under_userid.' level upgrade ");</script>';
                       
                            $row=mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
                            $email= mysqli_fetch_assoc($row);
                            $email = $email['email'];
                            setlevel($under_userid,$email);  
                        } else {
                           
                        }
                        
                    } else {
                       // echo "Error: No data found for rightnode.";
                    }
                } else {
                    //echo "Error fetching data for rightnode.";
                }
            } else {
               // echo "Error: No data found for leftnode.";
            }
        } else {
           // echo "Error fetching data for leftnode.";
        }
    }


}

function getlevel5($under_userid)
{
    //echo '<script>alert("level 3 function start for ' . $under_userid . '");</script>';

    global $con;
    $tempLeft = $tempRight = 'false'; 
    $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE user_id = '$under_userid'");

    while($row = mysqli_fetch_array($directDescendents))
    {
        $leftNode = $row['left']; // User1
        $rightNode = $row['right']; //User2
        $level = $row['level']; 


        $row2_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$leftNode'");
        if ($row2_query) {
            $row2 = mysqli_fetch_assoc($row2_query);
            if ($row2) {
                $leftlevel = $row2['level'];
        
                // Fetching data for $row3
                $row3_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$rightNode'");
                if ($row3_query) {
                    $row3 = mysqli_fetch_assoc($row3_query);
                    if ($row3) {
                        $rightlevel = $row3['level'];
                        if ($leftlevel === '4' && $rightlevel === '4') {
                            $result = mysqli_query($con, "UPDATE user SET plan_id = plan_id + 1 WHERE email = '$under_userid'");
                            $update_query = mysqli_query($con, "UPDATE tree SET  level=level+'1', rightcount=rightcount+'1' WHERE user_id = '$under_userid'");
                          //  echo '<script>alert("level 2 function  '.$under_userid.' level upgrade ");</script>';
                       
                          $row=mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
                          $email= mysqli_fetch_assoc($row);
                          $email = $email['email'];
                          setlevel($under_userid,$email);  
                        } else {
                           
                        }
                        
                    } else {
                       // echo "Error: No data found for rightnode.";
                    }
                } else {
                  //  echo "Error fetching data for rightnode.";
                }
            } else {
              //  echo "Error: No data found for leftnode.";
            }
        } else {
           // echo "Error fetching data for leftnode.";
        }
    }


}


function getlevel6($under_userid)
{
   // echo '<script>alert("level 3 function start for ' . $under_userid . '");</script>';

    global $con;
    $tempLeft = $tempRight = 'false'; 
    $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE user_id = '$under_userid'");

    while($row = mysqli_fetch_array($directDescendents))
    {
        $leftNode = $row['left']; // User1
        $rightNode = $row['right']; //User2
        $level = $row['level']; 


        $row2_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$leftNode'");
        if ($row2_query) {
            $row2 = mysqli_fetch_assoc($row2_query);
            if ($row2) {
                $leftlevel = $row2['level'];
        
                // Fetching data for $row3
                $row3_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$rightNode'");
                if ($row3_query) {
                    $row3 = mysqli_fetch_assoc($row3_query);
                    if ($row3) {
                        $rightlevel = $row3['level'];
                        if ($leftlevel === '5' && $rightlevel === '5') {
                            $result = mysqli_query($con, "UPDATE user SET plan_id = plan_id + 1 WHERE email = '$under_userid'");
                            $update_query = mysqli_query($con, "UPDATE tree SET  level=level+'1', rightcount=rightcount+'1' WHERE user_id = '$under_userid'");
                           // echo '<script>alert("level 2 function  '.$under_userid.' level upgrade ");</script>';
                       
                           $row=mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
                           $email= mysqli_fetch_assoc($row);
                           $email = $email['email'];
                           setlevel($under_userid,$email);  
                        } else {
                           
                        }
                        
                    } else {
                        //echo "Error: No data found for rightnode.";
                    }
                } else {
                  //  echo "Error fetching data for rightnode.";
                }
            } else {
               // echo "Error: No data found for leftnode.";
            }
        } else {
          //  echo "Error fetching data for leftnode.";
        }
    }


}

function getlevel7($under_userid)
{
   // echo '<script>alert("level 3 function start for ' . $under_userid . '");</script>';

    global $con;
    $tempLeft = $tempRight = 'false'; 
    $directDescendents = mysqli_query($con,"SELECT * FROM tree WHERE user_id = '$under_userid'");

    while($row = mysqli_fetch_array($directDescendents))
    {
        $leftNode = $row['left']; // User1
        $rightNode = $row['right']; //User2
        $level = $row['level']; 


        $row2_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$leftNode'");
        if ($row2_query) {
            $row2 = mysqli_fetch_assoc($row2_query);
            if ($row2) {
                $leftlevel = $row2['level'];
        
                // Fetching data for $row3
                $row3_query = mysqli_query($con, "SELECT * FROM tree WHERE user_id = '$rightNode'");
                if ($row3_query) {
                    $row3 = mysqli_fetch_assoc($row3_query);
                    if ($row3) {
                        $rightlevel = $row3['level'];
                        if ($leftlevel === '6' && $rightlevel === '6') {
                            $result = mysqli_query($con, "UPDATE user SET plan_id = plan_id + 1 WHERE email = '$under_userid'");
                            $update_query = mysqli_query($con, "UPDATE tree SET  level=level+'1', rightcount=rightcount+'1' WHERE user_id = '$under_userid'");
                           // echo '<script>alert("level 2 function  '.$under_userid.' level upgrade ");</script>';
                       
                           $row=mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
                           $email= mysqli_fetch_assoc($row);
                           $email = $email['email'];
                           setlevel($under_userid,$email);  
                        } else {
                           
                        }
                        
                    } else {
                      //  echo "Error: No data found for rightnode.";
                    }
                } else {
                    //echo "Error fetching data for rightnode.";
                }
            } else {
              //  echo "Error: No data found for leftnode.";
            }
        } else {
           // echo "Error fetching data for leftnode.";
        }
    }


}



function day_bal($under_userid,$email)
{
    global $con;
   // global $email;
   $level_cost=0;
   $actual_people=0;

   
    echo '<script>alert("daybal calculation ...using underuserid='.$under_userid.' and email='.$email.'");</script>';
    // //plan query start
    // $planrow = mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
    // //$plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = '$newplanid'");
    // if($planrow)
    // {
    //  $plandata = mysqli_fetch_assoc($planrow);
    //  $planid=$plandata['plan_id'];
    // $plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = '$planid'");
    
            

    // if(mysqli_num_rows($plan_query) > 0) {
    // // Fetch plan details
    // $plan_row = mysqli_fetch_assoc($plan_query);
    // $plan_name = $plan_row['plan_name'];
    // $plan_level = $plan_row['plan_level'];

    // // Update the tree table
    // $update_tree_query = mysqli_query($con, "UPDATE tree SET level = '$plan_level', plan_name = '$plan_name' WHERE user_id = '$under_userid'");
    // //$level_cost_query = mysqli_query($con, "SELECT level_cost FROM plan WHERE plan_id = '$under_userid'");
    
    
    // }

    //          // Fetch the level_cost from the plan table based on the user's level
    //         $level_cost_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = '$planid'");
    //         echo '<script>alert("level cost excute...to the underuser_id='.$under_userid.' ,plan_id='.$planid.' ");</script>';
    //          if ($level_cost_query) {
    //              // Fetch the level_cost value
    //              $level_cost_row = mysqli_fetch_assoc($level_cost_query);
    //              if ($level_cost_row) {
    //                 $cost = $level_cost_row['level_cost'];
    //                 $actual_people=$level_cost_row['actual_people'];
    //                 $level_cost=$cost * $actual_people;
    //                 // Rest of your code...
    //             }
    //          } else {
    //              // Handle the case when the level_cost for the user's level is not found
    //              //echo "Error: Level cost not found for level $user_level";
    //          }
    //          // Get the parent_id from the tree table based on the user's email
    //          //$parentQuery = mysqli_query($con, "SELECT parent_id, reference_id FROM user WHERE email = '$under_userid'");
    
        $parentQuery = mysqli_query($con, "SELECT parent_id, reference_id FROM user WHERE email = '$email'");

          
            
             if ($parentQuery) {
                 $parentData = mysqli_fetch_assoc($parentQuery);
             
                 // Check if parent_id and reference_id are not NULL
                 if ($parentData['parent_id'] !== null && $parentData['reference_id'] !== null) {
                     $parent_id = $parentData['parent_id'];
                     $reference_id = $parentData['reference_id'];

                     echo '<script>alert("parent id '.$parent_id.' ref id '.$reference_id.' ");</script>';
                   //  $query7 = mysqli_query($con, "INSERT INTO wallet (user_id) VALUES ('$parent_id')");
                   

                     // Determine the day_bal value based on the side
                     if ($parent_id == $reference_id) {
                        echo '<script>alert("parent and reference are equal.so it add the calculation for '.$reference_id.' ...levelbal='.$level_cost.' daybal=250 current_bal=500 totalbal=500");</script>';
                       // $query7 = mysqli_query($con, "UPDATE wallet SET date = NOW(), level_bal = '$level_cost', day_bal = day_bal+'250', current_bal = '500', total_bal = '500'");


                       $query2 = mysqli_query($con, "INSERT INTO wallet (reference_id, user_id, balance, date) 
                       SELECT reference_id, email, 250, NOW() FROM user 
                       WHERE email = '$email'");


                       //insert daybalance of current underuserid
                      // Assuming $con is your database connection object
                    $mail = mysqli_query($con, "SELECT user_id FROM income WHERE user_id = '$reference_id'");

                    if (mysqli_num_rows($mail) > 0) 
                       {
                        echo '<script>alert("'.$reference_id.' is already in income table.so it will update");</script>';
                
                        // $incomequery = mysqli_query($con, "UPDATE income 
                        // SET day_bal = day_bal + 250, level_bal = level_bal + $level_cost, date = NOW()
                        // WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");
                         $incomequery = mysqli_query($con, "UPDATE income 
                         SET day_bal = day_bal + 250
                         WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");


                       }else{

                        echo '<script>alert("'.$reference_id.' is not available in income table.so it will insert");</script>';
                       
                        // $incomequery = mysqli_query($con, "INSERT INTO income (user_id, day_bal, level_bal, date)
                        // VALUES ((SELECT reference_id FROM user WHERE email = '$email'), 250, level_bal+'$level_cost', NOW())");

                            //old insert             
                        $incomequery= mysqli_query($con, "INSERT INTO income (user_id, day_bal,date) 
                        SELECT reference_id, 250,NOW() FROM user 
                        WHERE email = '$email'");
                    
                    // $incomequery1 = mysqli_query($con, "UPDATE income 
                    // SET level_bal = level_bal + $level_cost
                    // WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");

                         }
                        
                     } else {
                        echo '<script>alert("parent and reference are not equal.so it add the calculation for '.$reference_id.' levelbal='.$level_cost.' daybal=250 current_bal=500 totalbal=500");</script>';
                         
                       $query2 = mysqli_query($con, "INSERT INTO wallet (reference_id, user_id, balance, date) 
                       SELECT reference_id, email, 250, NOW() FROM user 
                       WHERE email = '$email'");


                       //insert daybalance of current underuserid
                      // Assuming $con is your database connection object
                    $mail = mysqli_query($con, "SELECT user_id FROM income WHERE user_id = '$reference_id'");

                    if (mysqli_num_rows($mail) > 0) 
                       {
                        echo '<script>alert("'.$reference_id.' is already in income table.so it will update");</script>';
                        
                     
                        // $incomequery = mysqli_query($con, "UPDATE income 
                        // SET day_bal = day_bal + 250, level_bal = level_bal + $level_cost, date = NOW()
                        // WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");

                         $incomequery = mysqli_query($con, "UPDATE income 
                        SET day_bal = day_bal + 250
                        WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");

                       }else{

                        echo '<script>alert("'.$reference_id.' is not available in income table.so it will insert");</script>';
                        $incomequery= mysqli_query($con, "INSERT INTO income (user_id, day_bal,date) 
                        SELECT reference_id, 250,NOW() FROM user 
                        WHERE email = '$email'");

                        // $incomequery1 = mysqli_query($con, "UPDATE income 
                        // SET level_bal = level_bal + $level_cost
                        // WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");

                       }
                     }
                 } else {
                     //echo "Error: parent_id or reference_id is NULL";
                 }
             } else {
                // echo "Error in fetching parent_id and reference_id from tree table";
             }
          //  }
             //plan queryend


}

function setlevel($under_userid,$email)
{
    global $con;
   
    echo '<script>alert("level function...using underuserid='.$under_userid.' and email='.$email.'");</script>';
    //plan query start
    $planrow = mysqli_query($con, "SELECT * FROM user WHERE email = '$under_userid'");
    //$plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = '$newplanid'");
    if($planrow)
    {
     $plandata = mysqli_fetch_assoc($planrow);
     $planid=$plandata['plan_id'];
    $plan_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = '$planid'");
    
            

    if(mysqli_num_rows($plan_query) > 0) {
    // Fetch plan details
    $plan_row = mysqli_fetch_assoc($plan_query);
    $plan_name = $plan_row['plan_name'];
    $plan_level = $plan_row['plan_level'];

    // Update the tree table
    $update_tree_query = mysqli_query($con, "UPDATE tree SET level = '$plan_level', plan_name = '$plan_name' WHERE user_id = '$under_userid'");
    //$level_cost_query = mysqli_query($con, "SELECT level_cost FROM plan WHERE plan_id = '$under_userid'");
    }

             // Fetch the level_cost from the plan table based on the user's level
            $level_cost_query = mysqli_query($con, "SELECT * FROM plan WHERE plan_id = '$planid'");
            echo '<script>alert("level cost excute...to the underuser_id='.$under_userid.' ,plan_id='.$planid.' ");</script>';
             if ($level_cost_query) {
                 // Fetch the level_cost value
                 $level_cost_row = mysqli_fetch_assoc($level_cost_query);
                 if ($level_cost_row) {
                    $cost = $level_cost_row['level_cost'];
                    $actual_people = $level_cost_row['actual_people'];
                    $level_cost = $cost * $actual_people;
                    echo '<script>alert("leve_cost='.$cost.' and actual people='.$actual_people.' so the level_cost is'.$level_cost.' ");</script>';
                    // Rest of your code...
                }
             } else {
                 // Handle the case when the level_cost for the user's level is not found
                 //echo "Error: Level cost not found for level $user_level";
             }
             // Get the parent_id from the tree table based on the user's email
             //$parentQuery = mysqli_query($con, "SELECT parent_id, reference_id FROM user WHERE email = '$under_userid'");
             $parentQuery = mysqli_query($con, "SELECT parent_id, reference_id FROM user WHERE email = '$email'");

          
            
             if ($parentQuery) {
                 $parentData = mysqli_fetch_assoc($parentQuery);
             
                 // Check if parent_id and reference_id are not NULL
                 if ($parentData['parent_id'] !== null && $parentData['reference_id'] !== null) {
                     $parent_id = $parentData['parent_id'];
                     $reference_id = $parentData['reference_id'];

                     echo '<script>alert("parent id '.$parent_id.' ref id '.$reference_id.' ");</script>';
                   //  $query7 = mysqli_query($con, "INSERT INTO wallet (user_id) VALUES ('$parent_id')");
                   

                     // Determine the day_bal value based on the side
                     if ($parent_id == $reference_id) {
                        echo '<script>alert("parent and reference are equal.so it add the calculation for '.$reference_id.' ...levelbal='.$level_cost.' daybal=250 current_bal=500 totalbal=500");</script>';
                       // $query7 = mysqli_query($con, "UPDATE wallet SET date = NOW(), level_bal = '$level_cost', day_bal = day_bal+'250', current_bal = '500', total_bal = '500'");


                    //    $query2 = mysqli_query($con, "INSERT INTO wallet (reference_id, user_id, balance, date) 
                    //    SELECT reference_id, email, 250, NOW() FROM user 
                    //    WHERE email = '$email'");


                       //insert daybalance of current underuserid
                      // Assuming $con is your database connection object
                    $mail = mysqli_query($con, "SELECT user_id FROM income WHERE user_id = '$reference_id'");

                    if (mysqli_num_rows($mail) > 0) 
                       {
                        echo '<script>alert("'.$reference_id.' is already in income table.so it will update");</script>';
                        
                        // $incomequery= mysqli_query($con, "UPDATE income SET (reference_id, user_id, balance, date) VALUES
                        // SELECT reference_id, email, 250, NOW() FROM user 
                        // WHERE email = '$email'");
                        $incomequery = mysqli_query($con, "UPDATE income 
                        SET level_bal = level_bal + $level_cost, date = NOW()
                        WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");


                       }else{

                        echo '<script>alert("'.$reference_id.' is not available in income table.so it will insert");</script>';
                       
                        // $incomequery = mysqli_query($con, "INSERT INTO income (user_id, day_bal, level_bal, date)
                        // VALUES ((SELECT reference_id FROM user WHERE email = '$email'), 250, level_bal+'$level_cost', NOW())");

                            //old insert             
                        // $incomequery= mysqli_query($con, "INSERT INTO income (user_id, day_bal,date) 
                        // SELECT reference_id, 250,NOW() FROM user 
                        // WHERE email = '$email'");


                            $incomequery1 = mysqli_query($con, "UPDATE income 
                            SET level_bal = level_bal + $level_cost
                            WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");

                            }
                        
                     } else {
                        echo '<script>alert("parent and reference are not equal.so it add the calculation for '.$reference_id.' levelbal='.$level_cost.' daybal=250 current_bal=500 totalbal=500");</script>';
                         
                    //    $query2 = mysqli_query($con, "INSERT INTO wallet (reference_id, user_id, balance, date) 
                    //    SELECT reference_id, email, 250, NOW() FROM user 
                    //    WHERE email = '$email'");


                       //insert daybalance of current underuserid
                      // Assuming $con is your database connection object
                    $mail = mysqli_query($con, "SELECT user_id FROM income WHERE user_id = '$reference_id'");

                    if (mysqli_num_rows($mail) > 0) 
                       {
                        echo '<script>alert("'.$reference_id.' is already in income table.so it will update");</script>';
                        
                        // $incomequery= mysqli_query($con, "UPDATE income SET (reference_id, user_id, balance, date) VALUES
                        // SELECT reference_id, email, 250, NOW() FROM user 
                        // WHERE email = '$email'");

                        $incomequery = mysqli_query($con, "UPDATE income 
                        SET level_bal = level_bal + $level_cost, date = NOW()
                        WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");


                       }else{

                        echo '<script>alert("'.$reference_id.' is not available in income table.so it will insert");</script>';
                        // $incomequery= mysqli_query($con, "INSERT INTO income (user_id, day_bal,date) 
                        // SELECT reference_id, 250,NOW() FROM user 
                        // WHERE email = '$email'");

                        $incomequery1 = mysqli_query($con, "UPDATE income 
                        SET level_bal = level_bal + $level_cost
                        WHERE user_id = (SELECT reference_id FROM user WHERE email = '$email')");



                       }
                     }
                 } else {
                     //echo "Error: parent_id or reference_id is NULL";
                 }
             } else {
                // echo "Error in fetching parent_id and reference_id from tree table";
             }
            }
             //plan queryend


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
// function income($userid){
// 	global $con;
// 	$data = array();
// 	$query = mysqli_query($con,"select * from income where user_id='$userid'");
// 	$result = mysqli_fetch_array($query);
// if ($result !== null && isset($result['day_bal'])) {
//     $data['day_bal'] = $result['day_bal'];
// } else {
//     // Handle the case when 'day_bal' is not set or $result is null
//     // You can set a default value or handle it in another appropriate way
// }

// if ($result !== null && isset($result['current_bal'])) {
//     $data['current_bal'] = $result['current_bal'];
// } else {
//     // Handle the case when 'current_bal' is not set or $result is null
//     // You can set a default value or handle it in another appropriate way
// }

// if ($result !== null && isset($result['total_bal'])) {
//     $data['total_bal'] = $result['total_bal'];
// } else {
//     // Handle the case when 'total_bal' is not set or $result is null
//     // You can set a default value or handle it in another appropriate way
// }

	
// 	return $data;
// }
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