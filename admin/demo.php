<!DOCTYPE html>
<html>
<head>
  <title>Profile Form</title>
  <style>
    /* CSS styles */
    .form-container {
      width: 300px;
      margin: 0 auto;
    }
    .form-group {
      margin-bottom: 10px;
    }
    .form-group label {
      display: block;
    }
    .form-group input {
      width: 100%;
      padding: 5px;
    }
    .form-group button {
      width: 100%;
      padding: 10px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Profile Form</h2>
    <form id="profileForm" method="post">
      <div class="form-group">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
      </div><div class="form-group">
        <label for="name">Mobile:</label>
        <input type="number" id="mobile" name="mobile" required>
      </div><div class="form-group">
        <label for="name">Address:</label>
        <input type="text" id="address" name="address" required>
      </div><div class="form-group">
        <label for="name">City:</label>
        <input type="text" id="city" name="city" required>
      </div><div class="form-group">
        <label for="name">District:</label>
        <input type="text" id="district" name="district" required>
      </div><div class="form-group">
        <label for="name">State:</label>
        <input type="text" id="state" name="state" required>
      </div><div class="form-group">
        <label for="name">Pincode:</label>
        <input type="text" id="pincode" name="pincode" required>
      </div><div class="form-group">
        <label for="name">Date of Join:</label>
        <input type="text" id="doj" name="doj" required>
      
      </div><div class="form-group">
        <label for="name">Date of Update:</label>
        <input type="text" id="dou" name="dou" required>
      </div>
      <div class="form-group">
        <label for="name">Bank Name:</label>
        <input type="text" id="bank" name="bank" required>
      </div>
      <div class="form-group">
        <label for="name">Bank Account No:</label>
        <input type="text" id="accno" name="accno" required>
      </div>
      <div class="form-group">
        <label for="name">IFSC Code:</label>
        <input type="text" id="ifsc" name="ifsc" required>
      </div>
      <div class="form-group">
        <label for="name">Branch:</label>
        <input type="text" id="branch" name="branch" required>
      </div>
      <div class="form-group">
        <button type="submit">Submit</button>
      </div>
    </form>
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
