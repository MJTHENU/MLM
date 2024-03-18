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
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans+Condensed:300,400,600i&display=swap" rel="stylesheet">


<style>
    .user-bg .overlay-box {
    opacity: 0.9;
    position: absolute;
    top: 0px;
    left: 0px;
    right: 0px;
    height: 100%;
    text-align: center;
    background: purple;
}
.user-bg .overlay-box .user-content {
    margin-top: 20px;
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
    height: 220px;
    position: relative;
    margin: -15px;
    overflow: hidden;
}
.white-box {
    margin-bottom: 25px;
    
    padding: 25px;
}
.user-btm-box {
    padding: 40px 0 10px;
    clear: both;
    overflow: hidden;
}
#upload_label {
  cursor: pointer;
  position: absolute;
  left: 15px;
  top: 12px;
  font-size: 14px;
}
#upload_label:hover,
#upload_label:focus {
  color: #222;
}
.avatar {
  width: 150px;
  height: 150px;
  border-radius: 100%;
  border: 2px solid #fff;
  margin: 10px auto;
  position: relative;
  overflow: hidden;
  z-index: 2;
  transform: translateZ(0);
  transition: border-color 200ms;
}
.avatar--upload-error {
  border-color: #f73c3c;
  animation: shakeNo 300ms 1 forwards;
}
@keyframes shakeNo {
  20%,
  60% {
    transform: translateX(6px);
  }
  40%,
  80% {
    transform: translate(-6px);
  }
}
.avatar:hover .avatar_upload,
.avatar--hover .avatar_upload {
  opacity: 1;
}
.avatar:hover .upload_label,
.avatar--hover .upload_label {
  display: block;
}
#preview::after {
  content: "Loading...";
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  text-align: center;
  z-index: -1;
  line-height: 150px;
  color: #999;
}
.avatar_img--loading {
  opacity: 0;
}
.avatar_img {
  width: 100%;
  height: auto;
  animation: inPop 250ms 150ms 1 forwards
    cubic-bezier(0.175, 0.885, 0.32, 1.175);
  transform: scale(0);
  opacity: 0;
}
@keyframes inPop {
  100% {
    transform: scale(1);
    opacity: 1;
  }
}
.avatar_img--rotate90 {
  transform: rotate(90deg);
}
.avatar_upload {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  text-align: center;
  height: 100%;
  background: #000;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  opacity: 0;
  transition: opacity 500ms;
}
.upload_label {
  color: #111;
  text-transform: uppercase;
  font-size: 14px;
  cursor: pointer;
  white-space: nowrap;
  padding: 4px;
  border-radius: 3px;
  min-width: 60px;
  width: 100%;
  max-width: 80px;
  margin: auto;
  font-weight: 400;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  background: #fff;
  animation: popDown 300ms 1 forwards;
  transform: translateY(-10px);
  opacity: 0;
  display: none;
  transition: background 200ms, color 200ms;
}
@keyframes popDown {
  100% {
    transform: translateY(0);
    opacity: 1;
  }
}
.upload_label:hover {
  color: #fff;
  background: #222;
}
#upload {
  width: 100%;
  opacity: 0;
  height: 0;
  overflow: hidden;
  display: block;
  padding: 0;
  text-align: center;
}


* {
    margin: 0;
    padding: 0
}

html {
    height: 100%
}

p {
    color: grey
}

#heading {
    text-transform: uppercase;
    color: #673AB7;
    font-weight: normal
}

#msform {
    text-align: center;
    position: relative;
    margin-top: 20px
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

.form-card {
    text-align: center
}

#msform fieldset:not(:first-of-type) {
    display: none
}

#msform select {
    padding: 4px 8px 4px 8px;
    border: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    background-color: #ECEFF1;
    font-size: 16px;
    letter-spacing: 1px
}

#msform input,
#msform textarea {
    padding: 4px 8px 4px 8px;
    border: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    background-color: #ECEFF1;
    font-size: 16px;
    letter-spacing: 1px
}

#msform input:focus,
#msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: 1px solid #673AB7;
    outline-width: 0
}

#msform .action-button {
    width: 100px;
    background: #673AB7;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 0px 10px 5px;
    float: right
}

#msform .action-button:hover,
#msform .action-button:focus {
    background-color: #311B92
}

#msform .action-button-previous {
    width: 70px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px 10px 0px;
    float: right
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    background-color: #000000
}

.card {
    z-index: 0;
    border: none;
    position: relative
}

.fs-title {
    font-size: 25px;
    color: #673AB7;
    margin-bottom: 15px;
    font-weight: normal;
    text-align: center
}

.purple-text {
    color: #673AB7;
    font-weight: normal
}

.steps {
    font-size: 25px;
    color: gray;
    margin-bottom: 10px;
    font-weight: normal;
    text-align: right
}

.fieldlabels {
    color: gray;
    text-align: left
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
}

#progressbar .active {
    color: #673AB7
}

#progressbar li {
    list-style-type: none;
    font-size: 15px;
    width: 25%;
    float: left;
    position: relative;
    font-weight: 400
}

#progressbar #account:before {
    font-family: FontAwesome;
    content: "\f13e"
}

#progressbar #personal:before {
    font-family: FontAwesome;
    content: "\f007"
}

#progressbar #payment:before {
    font-family: FontAwesome;
    content: "\f030"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
    content: "\f00c"
}

#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 20px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: #673AB7
}

.progress {
    height: 20px
}

.progress-bar {
    background-color: #673AB7
}

.fit-image {
    width: 100%;
    object-fit: cover
}



.profile-pic {
  color: transparent;
  transition: all 0.3s ease;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  transition: all 0.3s ease;
}
.profile-pic input {
  display: none;
}
.profile-pic img {
  position: absolute;
  object-fit: cover;
  width: 165px;
  height: 165px;
  box-shadow: 0 0 10px 0 rgba(255, 255, 255, 0.35);
  border-radius: 100px;
  z-index: 0;
}
.profile-pic .-label {
  cursor: pointer;
  height: 165px;
  width: 165px;
}
.profile-pic:hover .-label {
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.8);
  z-index: 10000;
  color: #fafafa;
  transition: background-color 0.2s ease-in-out;
  border-radius: 100px;
  margin-bottom: 0;
}
.profile-pic span {
  display: inline-flex;
  padding: 0.2em;
  height: 2em;
}


:root {
  font-size: 20px;
  box-sizing: inherit;
}

*,
*:before,
*:after {
  box-sizing: inherit;
}

p {
  margin: 0;
}

p:not(:last-child) {
  margin-bottom: 1.5em;
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
  min-height: 1000px;
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
/*TODO: animate copy main transition style for info*/



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
                    <div class="col-lg-3">
                       
                      
                        
  
    </div>
                    </div>
           

  </div>
                	<div class="col-lg-9">
                    	<div class="panel panel-info">
                        
                           
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
          <div class="col-md-4 col-xs-12">
           
              <div class="user-btm-box">
              <div class="row">
                	 <?php
						$query = mysqli_query($con,"select * from income where userid='$userid'");
						$result = mysqli_fetch_array($query);
					?>
                	<div class="row">
                	 <?php
						$query = mysqli_query($con,"select * from income where userid='$userid'");
						$result = mysqli_fetch_array($query);
					?>
                <!-- /.row -->

                   
                </div>
                </div>
            </div>
          </div>
</div>
<div class="card">
<div class="form-container">
    <h2>Add user</h2>
    <form id="profileForm" method="post">
      <div class="form-group">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br>
      </div><div class="form-group">
        <label for="name">Mobile:</label><br>
        <input type="number" id="mobile" name="mobile" required>
      </div><div class="form-group">
        <label for="name">Address:</label><br>
        <input type="text" id="address" name="address" required>
      </div><div class="form-group">
        <label for="name">City:</label><br>
        <input type="text" id="city" name="city" required>
      </div><div class="form-group">
        <label for="name">District:</label><br>
        <input type="text" id="district" name="district" required>
      </div><div class="form-group">
        <label for="name">State:</label><br>
        <input type="text" id="state" name="state" required>
      </div><div class="form-group">
        <label for="name">Pincode:</label><br>
        <input type="text" id="pincode" name="pincode" required>
      </div><div class="form-group">
        <label for="name">Date of Join:</label><br>
        <input type="text" id="doj" name="doj" required>
      
      </div><div class="form-group">
        <label for="name">Date of Update:</label><br>
        <input type="text" id="dou" name="dou" required>
      </div>
      <div class="form-group">
        <label for="name">Bank Name:</label><br>
        <input type="text" id="bank" name="bank" required>
      </div>
      <div class="form-group">
        <label for="name">Bank Account No:</label><br>
        <input type="text" id="accno" name="accno" required>
      </div>
      <div class="form-group">
        <label for="name">IFSC Code:</label><br>
        <input type="text" id="ifsc" name="ifsc" required>
      </div>
      <div class="form-group">
        <label for="name">Branch:</label><br>
        <input type="text" id="branch" name="branch" required>
      </div>
      <div class="form-group">
        <button type="submit">Submit</button>
        

      </div>
    </form>
    <footer id="sticky-footer" class="flex-shrink-0 py-4 bg-dark text-white-50">
    <div class="container text-center" style="padding:12px;">
      <small></small>
    </div>
  </footer>
</div>
<div>

</div>





  <script>
    // JavaScript code

  /*  var gender;
if (document.getElementById("female").checked) {
  gender = document.getElementById("female").value;
} else if (document.getElementById("male").checked) {
  gender = document.getElementById("male").value;
}*/
    document.getElementById("profileForm").addEventListener("submit", function(event) {
      event.preventDefault(); // Prevent form submission

      // Retrieve form data
      var name = document.getElementById("name").value;
      //var gender = document.getElementById("gender").value;
      var mobile = document.getElementById("mobile").value;
      var address = document.getElementById("address").value;
      var city = document.getElementById("city").value;
      var district = document.getElementById("district").value;
      var state = document.getElementById("state").value;
      var pincode = document.getElementById("pincode").value;
      var doj = document.getElementById("doj").value;
      var dou = document.getElementById("dou").value;
      var bank = document.getElementById("bank").value;
      var accno = document.getElementById("accno").value;
      var ifsc = document.getElementById("ifsc").value;
      var branch = document.getElementById("branch").value;

      // Create a new FormData object and append form data
      var formData = new FormData();
      formData.append("name", name);
      //formData.append("gender", gender);
      formData.append("mobile", mobile);
      formData.append("address", address);
      formData.append("city", city);
      formData.append("district", district);
      formData.append("state", state);
      formData.append("pincode", pincode);
      formData.append("doj", doj);
      formData.append("dou", dou);
      formData.append("bank", bank);
      formData.append("accno", accno);
      formData.append("ifsc", ifsc);
      formData.append("branch", branch);


      // Send form data to a PHP file using AJAX
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "demo.php");
      xhr.onload = function() {
        if (xhr.status === 200) {
          alert("Profile saved successfully!");
          // You can redirect or perform additional actions here if needed
        }
      };
      xhr.send(formData);
    });
  </script>
</body>
</html>

<?php
// PHP code for saving form data into the profile table
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $name = $_POST['name'];
 // $gender = $_POST['gender'];
  $mobile = $_POST['mobile'];
  $address = $_POST['address'];
  $city = $_POST['city'];
  $district = $_POST['district'];
  $state = $_POST['state'];
  $pincode = $_POST['pincode'];
  $doj = $_POST['doj'];
  $dou = $_POST['dou'];
  $bank = $_POST['bank'];
  $accno = $_POST['accno'];
  $ifsc = $_POST['ifsc'];
  $branch = $_POST['branch'];

  // Insert the data into the profile table (replace this with your own database code)
  // Example using MySQLi:
  $mysqli = new mysqli("localhost", "thenu", "thenu123", "multilevel");
  if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
  }
  $query = "INSERT INTO profile (name, mobile, address, city, district, state, pincode, doj, dou, bank, accno, ifsc, branch)
   VALUES ('$name','$address', '$mobile', '$city', '$district', '$state', '$pincode', '$doj', '$dou', '$bank', '$accno', '$ifsc', '$branch')";
  if ($mysqli->query($query) === TRUE) {
    // Profile saved successfully
    $mysqli->close();
    exit();
  } else {
    echo "Error: " . $mysqli->error;
    $mysqli->close();
    exit();
  }
}
?>

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
<script type="text/javascript" src="post.js"></script>

<script>
  
  $(document).ready(function(){

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;
var current = 1;
var steps = $("fieldset").length;

setProgressBar(current);

$(".next").click(function(){

current_fs = $(this).parent();
next_fs = $(this).parent().next();

//Add Class Active
$("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

//show the next fieldset
next_fs.show();
//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
next_fs.css({'opacity': opacity});
},
duration: 500
});
setProgressBar(++current);
});

$(".previous").click(function(){

current_fs = $(this).parent();
previous_fs = $(this).parent().prev();

//Remove class active
$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
previous_fs.show();

//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
previous_fs.css({'opacity': opacity});
},
duration: 500
});
setProgressBar(--current);
});

function setProgressBar(curStep){
var percent = parseFloat(100 / steps) * curStep;
percent = percent.toFixed();
$(".progress-bar")
.css("width",percent+"%")
}

$(".submit").click(function(){
return false;
})

});

</script>
</body>

</html>
