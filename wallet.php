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

    <title>Mlm Website  - Wallet</title>

    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
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
                    <div class="col-lg-3">
                        <h1 class="page-header">Wallet</h1>
                        <div class="col-lg-3">
                        
                                    <?php
                                        $query = mysqli_query($con, "SELECT * FROM wallet WHERE reference_id = '$userid' ORDER BY user_id DESC");
                                        // $stmt = mysqli_prepare($con, "SELECT * FROM wallet WHERE user_id = ? ORDER BY id DESC");
                                        // mysqli_stmt_bind_param($stmt, "s", $userid);
                                        // mysqli_stmt_execute($stmt);
                                        // $query = mysqli_stmt_get_result($stmt);
                                        // mysqli_stmt_close($stmt);
                                    
                                    ?>
                                        <?php
                                        // $query = mysqli_query($con,"insert into wallet(userid,amount,crediteddate) values('$userid','$amount','$crediteddate')");
                                        ?>
                        </div>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>S. No</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $i=1;
                                    while($row = mysqli_fetch_array($query))
                                    { 
                                    //   $type_class = ($row[''] == "credited")? 'Green': "Red";
                                    //  $symbol_class = ($row['income_type'] == "credited")? '+': "-";

                                    $type_class = ($row['income_type'] == "credited") ? 'Green' : 'Red';
                                    $symbol_class = ($row['income_type'] == "credited") ? '+' : '-';


                                      ?>
                                        <tr class="success">
                                            <td><?php echo $i; ?></td>
                                            <td style="color:<?php echo $type_class; ?>"><?php echo $symbol_class." ".$row['balance']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                             <td style="color:<?php echo $type_class; ?>"><?php echo $row['income_type']; ?></td>
                                        </tr>
                                    <?php $i++; } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            </div>
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