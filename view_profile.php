<?php
include('php-includes/connect.php');
include('php-includes/check-login.php');
$userid = $_SESSION['userid'];

// Fetch data from different tables
$profileQuery = mysqli_query($con, "SELECT name FROM profile WHERE userid='$userid'");
$userQuery = mysqli_query($con, "SELECT email FROM user WHERE userid='$userid'");
//$treeQuery = mysqli_query($con, "SELECT parent_id, reference_id FROM tree WHERE userid='$userid'");
$parentQuery = mysqli_query($con,"SELECT id, userid, `left` AS parent_id FROM tree WHERE `left` IS NOT NULL");
$referenceQuery = mysqli_query($con,"SELECT id, userid, `right` AS reference_id FROM tree WHERE `right` IS NOT NULL");

// Retrieve the data
$profileData = mysqli_fetch_assoc($profileQuery);
$userData = mysqli_fetch_assoc($userQuery);
$parentData = mysqli_fetch_assoc($treeQuery);
$referenceData = mysqli_fetch_assoc($referenceQuery);

// Assign the retrieved values to variables
$name = $profileData['name'];
$email = $userData['email'];
$parentId = $parentData['parent_id'];
$referenceId = $referenceData['reference_id'];
?>

<!-- Your existing HTML code -->

<form class="form-horizontal form-material">
  <div class="form-group">
    <label class="col-md-12">Full Name</label>
    <div class="col-md-12">
      <input type="text" placeholder="Johnathan Doe" class="form-control form-control-line" value="<?php echo $name; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="example-email" class="col-md-12">Email</label>
    <div class="col-md-12">
      <input type="email" placeholder="johnathan@admin.com" class="form-control form-control-line" name="example-email" id="example-email" value="<?php echo $email; ?>">
    </div>
  </div>

  <div class="form-group">
    <label class="col-md-12">Parent ID</label>
    <div class="col-md-12">
      <input type="text" placeholder="Parent ID" class="form-control form-control-line" value="<?php echo $parentId; ?>">
    </div>
  </div>
  <div class="form-group">
    <label class="col-md-12">Referal ID</label>
    <div class="col-md-12">
      <input type="text" placeholder="Referal ID" class="form-control form-control-line" value="<?php echo $referenceId; ?>">
    </div>
  </div>

  <!-- Remaining form fields -->

  <div class="form-group">
    <div class="col-sm-12">
      <a href="update-profile.php" class="btn btn-success">Update Profile</a>
    </div>
  </div>
</form>
